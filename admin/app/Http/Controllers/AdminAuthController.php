<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)->where('role', 'admin')->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with('error', 'Invalid credentials.');
        }
        $request->session()->regenerate(); 
        session(['admin' => $user]);

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('admin');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }

    public function dashboard()
    {
        $today = Carbon::today()->toDateString();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();

        
        $appointmentsToday = Appointment::with(['user', 'schedule'])
            ->whereHas('schedule', fn($q) => $q->whereDate('date', $today))
            ->orderBy('schedule_id')
            ->get();

        $attendancePin = \App\Models\PinCode::where('purpose', 'appointment_attendance')->first();
        $overridePin = \App\Models\PinCode::where('purpose', 'slot_limit_override')->first();



        $totalStudents = User::where('role', 'student')->count();
        $successfulToday = Appointment::where('status', 'completed')
            ->whereHas('schedule', fn($q) => $q->whereDate('date', $today))
            ->count();
        $successfulWeek = Appointment::where('status', 'completed')
            ->whereHas('schedule', fn($q) => $q->whereBetween('date', [$startOfWeek, now()]))
            ->count();
        $successfulMonth = Appointment::where('status', 'completed')
            ->whereHas('schedule', fn($q) => $q->whereBetween('date', [$startOfMonth, now()]))
            ->count();

        
        $chartLabels = [];
        $chartData = [];


        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $label = $date->format('M d');
            $count = Appointment::where('status', 'completed')

                ->whereHas('schedule', fn($q) => $q->whereDate('date', $date))
                ->count();

            $chartLabels[] = $label;
            $chartData[] = $count;
        }

        return view('dashboard', compact(
            'appointmentsToday', 'attendancePin', 'overridePin',
            'totalStudents', 'successfulToday', 'successfulWeek', 'successfulMonth',
            'chartLabels', 'chartData'
            
        ));
    }
}
