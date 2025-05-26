<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment; 
use App\Models\Schedule; 
use App\Models\User;
use Carbon\Carbon;
use App\Notifications\WalkinSlotAvailable;
use Notification; 

class WalkinNotificationController extends Controller
{
    public function create()
    {
        
        $today = Carbon::today();


        $appointments = Schedule::whereDate('date', $today)
            ->orderBy('start_time')
            ->get();

        return view('admin.walkin.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $selectedSchedule = Schedule::findOrFail($request->schedule_id);
        $today = now()->toDateString();

        $students = User::whereHas('appointments.schedule', function ($q) use ($today) {
            $q->whereDate('date', $today);
        })
        ->whereHas('appointments', function ($q) use ($today) {
            $q->whereIn('status', ['booked', 'cancelled'])
            ->whereHas('schedule', function ($s) use ($today) {
                $s->whereDate('date', $today);
            });
        })
        ->get();

        


        foreach ($students as $student) {
            //dd($student->id);
            $student->notify(new WalkinSlotAvailable($request->message));
        }

        return redirect()->route('admin.walkin.create')->with('success', 'Notifications sent to all students with pending/cancelled appointments today.');
    }

}
