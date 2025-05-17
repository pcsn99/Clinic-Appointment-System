<?php

namespace App\Http\Controllers;

use App\Models\PinCode;

use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\AppointmentBooked;
use App\Notifications\AppointmentCancelled;
use App\Notifications\AppointmentMarkedPresent;

class StudentAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('schedule')
            ->where('user_id', Auth::id())
            ->get();

            $existingBooking = Appointment::with('schedule')
            ->where('user_id', Auth::id())
            ->whereHas('schedule', function ($q) {
                $q->whereDate('date', '>=', now()->toDateString());
            })
            ->where('status', 'booked')
            ->first();

        return view('student.appointments.index', compact('appointments', 'existingBooking'));
    }

    public function calendarEvents()
    {
        $schedules = Schedule::withCount('appointments')->get();

        $grouped = $schedules->groupBy('date');

        $events = $grouped->map(function ($slots, $date) {
            $totalSlots = $slots->sum('slot_limit');
            $totalBooked = $slots->sum('appointments_count');

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
        $date = $request->input('date');

        $schedules = Schedule::where('date', $date)
            ->withCount('appointments')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'start_time' => $s->start_time,
                    'end_time' => $s->end_time,
                    'slot_limit' => $s->slot_limit,
                    'booked' => $s->appointments_count,
                ];
            });

        return response()->json($schedules);
    }

    public function book(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'pin' => 'nullable|string',
        ]);

        $userId = Auth::id();

        
        $existing = Appointment::where('user_id', $userId)
            ->where('status', 'booked')
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a booking. Please cancel it first.');
        }


        $schedule = Schedule::withCount('appointments')->findOrFail($request->schedule_id);

        $isFull = $schedule->appointments_count >= $schedule->slot_limit;
        if ($isFull) {
            $validPin = PinCode::where('purpose', 'slot_limit_override')
                ->where('type', 'hourly')
                ->whereDate('created_at', now())
                ->orderByDesc('created_at')
                ->first();
    
            if (!$validPin || $validPin->pin_code !== $request->pin) {
                return back()->with('error', 'Slot is full. Please enter a valid PIN to override.');
            }
        }

        Appointment::create([
            'user_id' => $userId,
            'schedule_id' => $schedule->id,
            'status' => 'booked',
        ]);

        Auth::user()->notify(new AppointmentBooked($schedule));

        return back()->with('success', 'Appointment successfully booked.');
    }

    public function cancel($id)
    {
        $appt = Appointment::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($appt->is_present) {
            return back()->with('error', 'Cannot cancel an appointment already marked as present.');
        }

        Auth::user()->notify(new AppointmentCancelled);

        $appt->delete();

        return back()->with('success', 'Appointment cancelled.');
    }

    public function reschedule(Request $request, $id)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'pin' => 'nullable|string',
        ]);

        $appt = Appointment::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($appt->is_present) {
            return back()->with('error', 'Cannot reschedule an appointment already marked as present.');
        }

        $schedule = Schedule::withCount('appointments')->findOrFail($request->schedule_id);

        if ($schedule->appointments_count >= $schedule->slot_limit) {
            // TODO: Validate pin 
            return back()->with('error', 'Slot is full. Please enter valid PIN to override.');
        }

        $appt->schedule_id = $schedule->id;
        $appt->save();

        return back()->with('success', 'Appointment rescheduled.');
    }

    public function markAsPresent(Request $request, Appointment $appointment)
    {
        $request->validate([
            'pin' => 'required|string'
        ]);

        $validPin = \App\Models\PinCode::where('purpose', 'appointment_attendance')
            ->where('type', 'hourly')
            ->whereDate('created_at', now())
            ->orderByDesc('created_at')
            ->first();

        if (!$validPin || $validPin->pin_code !== $request->pin) {
            return back()->with('error', 'Invalid PIN.');
        }

        $appointment->update([
            'is_present' => true,
            'status' => 'completed'
        ]);

        $appointment->user->notify(new AppointmentMarkedPresent);

        return back()->with('success', 'You have been marked as present.');
    }

}
