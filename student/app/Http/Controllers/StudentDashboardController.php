<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\Schedule;

class StudentDashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

  
        $currentBooking = Appointment::with('schedule')
            ->where('user_id', $user->id)
            ->whereHas('schedule', fn($q) => $q->whereDate('date', '>=', now()))
            ->orderByDesc('schedule_id')
            ->first();

        $todaySchedules = Schedule::whereDate('date', now())
            ->withCount('appointments')
            ->get();

        return view('dashboard', compact('currentBooking', 'todaySchedules'));
    }
}
