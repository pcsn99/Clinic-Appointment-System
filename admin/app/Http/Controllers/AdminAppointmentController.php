<?php



namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        return redirect()->route('admin.dashboard')->with('success', 'Appointment booked successfully.');
    }



    public function index()
    {
        // cancel past unmarked appointments
        $pastAppointments = Appointment::whereHas('schedule', function ($query) {
            $query->where('end_time', '<', now()->format('H:i'))
                  ->whereDate('date', now());
        })->where('is_present', false)->get();
    
        foreach ($pastAppointments as $appt) {
            $appt->status = 'cancelled';
            $appt->save();
        }
    
        
        $appointments = Appointment::join('schedules', 'appointments.schedule_id', '=', 'schedules.id')
            ->join('users', 'appointments.user_id', '=', 'users.id')
            ->whereDate('schedules.date', '>=', now()->toDateString())
            ->orderBy('schedules.date')
            ->orderBy('schedules.start_time')
            ->select('appointments.*')
            ->with(['user', 'schedule'])
            ->get();
    
        return view('appointments.index', [
            'appointments' => $appointments
        ]);
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
    
}
