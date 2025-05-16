<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return \view('auth.profile', compact('user'));
    }

    public function edit()
    {
        $user = Auth::user();
        dd($user);
        return \view('auth.profile-edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'course' => 'required',
            'year' => 'required',
            'contact_number' => 'required',
            'username' => 'required|unique:users,username,'.$user->id,
        ]);

        // Check if password is being updated
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|confirmed|min:6',
            ]);
            
            $user->password = Hash::make($request->password);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->course = $request->course;
        $user->year = $request->year;
        $user->contact_number = $request->contact_number;
        $user->username = $request->username;
        $user->save;

        return \redirect()->route('account.show')->with('success', 'Profile updated successfully.');
    }
}
