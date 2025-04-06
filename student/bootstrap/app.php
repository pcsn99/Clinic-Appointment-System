<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\EnsureStudentIsAuthenticated;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Global middleware
       

        // Route middleware
        $middleware->alias([
            'auth.student' => EnsureStudentIsAuthenticated::class,
        ]);

        
    })
    ->withExceptions(function (Exceptions $exceptions) {
        
    })->create();

