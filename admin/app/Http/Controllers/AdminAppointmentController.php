<?php



namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;

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
        // past appointments as absent if not already present
        $pastAppointments = Appointment::whereHas('schedule', function ($query) {
            $query->where('end_time', '<', now()->format('H:i'))
                ->whereDate('date', now());
        })->where('is_present', false)->get();

        foreach ($pastAppointments as $appt) {
            $appt->status = 'cancelled';
            $appt->save();
        }

        // only show upcoming appointments
        $appointments = Appointment::with(['user', 'schedule'])
            ->whereHas('schedule', function ($query) {
                $query->where(function ($q) {
                    $q->whereDate('date', '>', now()->toDateString())
                    ->orWhere(function ($q2) {
                        $q2->whereDate('date', now()->toDateString())
                            ->where('end_time', '>', now()->format('H:i'));
                    });
                });
            })->orderBy('schedule_id')
            ->get();

        return view('appointments.index', compact('appointments'));
    }

    public function mark(Request $request, Appointment $appointment)
    {
        $request->validate([
            'is_present' => 'required|boolean',
        ]);

        $appointment->is_present = $request->is_present;
        $appointment->status = $request->is_present ? 'completed' : 'cancelled';
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
}
