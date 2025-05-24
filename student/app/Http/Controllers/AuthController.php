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
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'contact_number' => 'required|string|max:20',
            'college' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'year' => 'required|string|max:255',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_number' => $request->contact_number,
            'college' => $request->college,
            'course' => $request->course,
            'year' => $request->year,
            'role' => 'student', 
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

            $user = Auth::user();

            if ($user->role !== 'student') {
                Auth::logout();
                return back()->withErrors(['login' => 'Access denied.']);
            }

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

    //Kat added function
    public function profile()
    {
        $student = Auth::user();
        return view('auth.profile', compact('student'));
    }

    public function editProfile()
    {
        $student = Auth::user();
        return view('auth.profile-edit', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'course' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'contact_number' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
        ]);

        $student->update($request->only([
            'name',
            'course',
            'year',
            'contact_number',
            'email'
        ]));

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully');
    }
}
