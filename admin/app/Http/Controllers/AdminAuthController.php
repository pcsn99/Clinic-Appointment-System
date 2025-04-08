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
    
        $appointmentsToday = Appointment::with(['user', 'schedule'])
            ->whereHas('schedule', function ($q) use ($today) {
                $q->whereDate('date', $today);
            })
            ->orderBy('schedule_id')
            ->get();
    
        return view('dashboard', compact('appointmentsToday'));
    }
}
