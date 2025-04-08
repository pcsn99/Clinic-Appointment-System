<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Schedule;
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

        session(['admin' => $user]);

        $schedules = Schedule::orderBy('date')->orderBy('start_time')->get();
        return view('dashboard', compact('schedules'));
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
        return view('dashboard');
    }
}
