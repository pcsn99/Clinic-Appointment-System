<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment; 
use App\Models\User;
use Carbon\Carbon;
use App\Notifications\WalkinSlotAvailable;
use Notification; 

class WalkinNotificationController extends Controller
{
    public function create()
    {
        
        $today = Carbon::today();
        $appointments = Appointment::whereHas('schedule', function ($query) use ($today) {
            $query->whereDate('date', $today);
        })->with('schedule')->get();

        return view('admin.walkin.create', compact('appointments'));
    }

    public function store(Request $request)
    {
        $request->validate([

            'appointment_id' => 'required|exists:appointments,id',
            
            'message' => 'nullable|string|max:1000',

        ]);
    
        $appointment = Appointment::findOrFail($request->appointment_id);
    
        $students = User::whereHas('appointments', function ($q) use ($appointment) {
            $q->where('schedule_id', $appointment->schedule_id);

        })->get();
    
        foreach ($students as $student) {

            $student->notify(new WalkinSlotAvailable($request->message));
        }
    
        return redirect()->route('admin.walkin.create')->with('success', 'Notifications sent using Laravel Notification system.');
    }
}
