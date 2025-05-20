<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureStudentIsAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role !== 'student') {
            Auth::logout(); 
            
            return redirect()->route('login')->withErrors(['login' => 'Access denied.']);
        }

        
        $response = $next($request);

        return $response->header('Cache-Control','no-cache, no-store, max-age=0, must-revalidate')
                        ->header('Pragma','no-cache')
                        ->header('Expires','Sat, 01 Jan 1990 00:00:00 GMT');
    }
}