<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Mail\SendNewPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    public function handleReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        $newPassword = str()->random(10);
        $user->password = Hash::make($newPassword);
        $user->save();

        Mail::to($user->email)->send(new SendNewPassword($user, $newPassword));

        return redirect()->route('login')->with('success', 'A new password has been sent to your email.');
    }
}
