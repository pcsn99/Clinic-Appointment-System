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

use App\Notifications\AppointmentBookedByAdmin;
use App\Notifications\AppointmentMarkedPresentOrReverted;
use App\Notifications\AppointmentRebooked;
use App\Notifications\AppointmentCancelledFromReschedule;


class AdminAppointmentController extends Controller
{
    public function create(Request $request)
    {
        $users = [];
        $schedules = [];

        if ($request->has('search')) {
            $search = $request->input('search');
            
            
            if (!empty(trim($search))) {
                $users = User::where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('username', 'like', "%{$search}%")
                            ->get();
            } else {
                
                $users = collect([]);
            }
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

        
        $existingAppointments = Appointment::where('user_id', $request->user_id)
            ->where('status', 'booked')
            ->get();

        foreach ($existingAppointments as $appointment) {
            $appointment->status = 'cancelled';
            $appointment->save();
        }

        
        $appointment = Appointment::create([
            'user_id' => $request->user_id,
            'schedule_id' => $request->schedule_id,
            'status' => 'booked',
        ]);

     
        $user = User::find($request->user_id);
        $user->notify(new AppointmentBookedByAdmin());

        
        Log::info("Admin rebooked appointment for user_id: {$user->id} to schedule_id: {$request->schedule_id}");

        return back()->with('appointment_success', 'Appointment successfully rebooked.');
    }



    public function index()
    {
        
    
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

    

    public function Mark(Request $request, Appointment $appointment)
    {
        $value = filter_var($request->input('is_present'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        dd($value);
        if (!is_bool($value)) {
            return back()->with('error', 'Invalid presence value.');
        }
        //dd($appointment);
        $appointment->is_present = $value;
        $appointment->status = $value ? 'completed' : 'booked';
        $appointment->save();

        $appointment->user->notify(new AppointmentMarkedPresentOrReverted($value));

        Log::info("✅ Marked appointment #{$appointment->id} for user_id: {$appointment->user_id} as " . ($value ? 'present' : 'not present'));

    
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
        
       
        $appointments = Appointment::whereHas('schedule', function($query) use ($date) {
            $query->where('date', $date);
        })
        ->with(['schedule', 'user'])
        ->get()
        ->map(function($appointment) {
            return [
                'id' => $appointment->id,
                'student_name' => $appointment->user->name ?? 'Unknown',
                'start_time' => $appointment->schedule->start_time ?? '',
                'end_time' => $appointment->schedule->end_time ?? '',
                'status' => $appointment->status ?? 'booked', 
                'is_present' => $appointment->is_present ?? false,
            ];
        });
        
        return response()->json($appointments);
    }
    

    public function handleSmartRescheduling()
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
    
                Log::info("Rebooked user '{$user->name}' to new time slot: {$available->start_time} - {$available->end_time}");
    
                $user->notify(new AppointmentRebooked($available));
            } else {
                $appointment->status = 'cancelled';
                $appointment->save();
    
                Log::warning("Could not rebook '{$user->name}' - all slots full. Appointment cancelled.");
    
        $user->notify(new AppointmentCancelledFromReschedule());
            }
        }
    
        Log::info("✅ Smart rescheduling complete.");
    }
    


}
