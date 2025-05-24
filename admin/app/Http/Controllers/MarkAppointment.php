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


use App\Notifications\AppointmentMarkedPresentOrReverted;


class MarkAppointment extends Controller
{
    public function Mark(Request $request, Appointment $appointment)
    {
        $value = filter_var($request->input('is_present'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        //dd($value);
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
}
