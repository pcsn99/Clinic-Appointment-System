<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'course' => 'required',
            'year' => 'required',
            'contact_number' => 'required',
        ]);
    
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'course' => $request->course,
            'year' => $request->year,
            'contact_number' => $request->contact_number,
        ]);
    
        return redirect()->route('login')->with('success', 'Registration successful. Please login.');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);
    
        $login = $request->login;
    
        
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    
        if (Auth::attempt([$field => $login, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }
    
        return back()->withErrors(['login' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
