<?php



namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\Notification;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminAppointmentController extends Controller
{
    public function create(Request $request)
    {
        $users = [];
        $schedules = [];

        if ($request->has('search')) {
            $search = $request->input('search');
            $users = User::where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->get();
        }

        if ($request->has('user_id')) {
            $schedules = Schedule::whereDoesntHave('appointments', function ($query) use ($request) {
                $query->where('user_id', $request->user_id);
            })->orderBy('date')->orderBy('start_time')->get();

            $selectedUser = User::find($request->user_id);
        } else {
            $selectedUser = null;
        }

        return view('appointments.create', compact('users', 'schedules', 'selectedUser'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'schedule_id' => 'required|exists:schedules,id',
        ]);

        $existing = Appointment::where('user_id', $request->user_id)
            ->where('schedule_id', $request->schedule_id)
            ->first();

        if ($existing) {
            return back()->with('error', 'User already booked for this schedule.');
        }

        Appointment::create([
            'user_id' => $request->user_id,
            'schedule_id' => $request->schedule_id,
            'status' => 'booked',
        ]);

        Notification::create([
            'notifiable_id' => $request->user_id,
            'notifiable_type' => 'App\Models\User',
            'title' => 'Appointment Booked by Admin',
            'message' => 'You have been booked for an appointment by the clinic.',
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Appointment booked successfully.');
    }



    public function index()
    {
        $this->handleSmartRescheduling(); 
    
        $appointments = Appointment::join('schedules', 'appointments.schedule_id', '=', 'schedules.id')
            ->join('users', 'appointments.user_id', '=', 'users.id')
            ->whereDate('schedules.date', '>=', now()->toDateString())
            ->orderBy('schedules.date')
            ->orderBy('schedules.start_time')
            ->select('appointments.*')
            ->with(['user', 'schedule'])
            ->get();
    
        return view('appointments.index', compact('appointments'));
    }
  
    public function mark(Request $request, Appointment $appointment)
    {
        $value = filter_var($request->input('is_present'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    
        if (!is_bool($value)) {
            return back()->with('error', 'Invalid presence value.');
        }
    
        $appointment->is_present = $value;
        $appointment->status = $value ? 'completed' : 'booked';
        $appointment->save();

        Notification::create([
            'notifiable_id' => $appointment->user_id,
            'notifiable_type' => 'App\Models\User',
            'title' => $value ? 'Marked Present' : 'Attendance Reverted',
            'message' => $value 
                ? 'You have been marked as present by the clinic.'
                : 'Your attendance status was reverted by the clinic.',
        ]);
    
        return back()->with('success', 'Attendance updated.');
    }
    

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'selected' => 'required|array',
        ]);

        Appointment::whereIn('id', $request->selected)->delete();

        return back()->with('success', 'Selected appointments deleted.');
    }


    public function calendarEvents()
    {
        $grouped = Schedule::all()->groupBy('date');
    
        $events = $grouped->map(function ($schedules, $date) {
            $totalSlots = 0;
            $totalBooked = 0;
    
            foreach ($schedules as $sched) {
                $totalSlots += $sched->slot_limit;
                $totalBooked += $sched->appointments()->count();
            }
    
            $color = $totalBooked >= $totalSlots ? '#e74c3c' : ($totalBooked > 0 ? '#f39c12' : '#2ecc71');
    
            return [
                'start' => $date,
                'end' => $date,
                'display' => 'background',   
                'backgroundColor' => $color, 
                'borderColor' => $color,     
            ];
        })->values();
    
        return response()->json($events);
    }
    

    public function schedulesByDate(Request $request)
    {
        $date = $request->date;
    
        $schedules = Schedule::where('date', $date)
            ->withCount('appointments')
            ->get()
            ->map(function ($s) {
                return [
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                    'slot_limit' => $s->slot_limit,
                    'booked' => $s->appointments_count,
                ];
            });
    
        return response()->json($schedules);
    }
    

    protected function handleSmartRescheduling()
    {
        $now = now();
        $today = $now->toDateString();
    
        $absentAppointments = Appointment::whereHas('schedule', function ($query) use ($now, $today) {
                $query->whereDate('date', $today)
                    ->where('end_time', '<', $now->format('H:i'));
            })
            ->where('is_present', false)
            ->where('status', 'booked')
            ->with(['user', 'schedule'])
            ->get();
    
        Log::info("🟡 Starting smart rescheduling at {$now->toDateTimeString()} | Found {$absentAppointments->count()} absent appointment(s)");
    
        foreach ($absentAppointments as $appointment) {
            $user = $appointment->user;
            $originalSchedule = $appointment->schedule;
    
            Log::info("⏱ Checking appointment for user: {$user->name} | Scheduled at: {$originalSchedule->start_time} - {$originalSchedule->end_time}");
    
            // Look for later available schedule
            $available = Schedule::whereDate('date', $today)
                ->where('start_time', '>', $originalSchedule->end_time)
                ->withCount('appointments')
                ->get()
                ->firstWhere(function ($sched) {
                    return $sched->appointments_count < $sched->slot_limit;
                });
    
            if ($available) {
                $appointment->schedule_id = $available->id;
                $appointment->save();
    
                Log::info("✅ Rebooked user '{$user->name}' to new time slot: {$available->start_time} - {$available->end_time}");
    
                Notification::create([
                    'notifiable_id' => $user->id,
                    'notifiable_type' => 'App\Models\User',
                    'title' => 'Rebooked Due to Absence',
                    'message' => "You missed your appointment and have been rebooked today at {$available->start_time} - {$available->end_time}.",
                ]);
            } else {
                $appointment->status = 'cancelled';
                $appointment->save();
    
                Log::warning("❌ Could not rebook '{$user->name}' - all slots full. Appointment cancelled.");
    
                Notification::create([
                    'notifiable_id' => $user->id,
                    'notifiable_type' => 'App\Models\User',
                    'title' => 'Appointment Cancelled',
                    'message' => "You missed your appointment and all other slots today are full. Please rebook.",
                ]);
            }
        }
    
        Log::info("✅ Smart rescheduling complete.");
    }
    


}
