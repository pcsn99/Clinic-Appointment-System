<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LogViewerController extends Controller
{
    public function index()
    {
        //dd();
        $adminLogPath = storage_path('logs/laravel.log');
        $studentLogPath = base_path('../student/storage/logs/laravel.log');
        $healthLogPath = storage_path('logs/system_health.log');
    
        $adminLogs = file_exists($adminLogPath)
            ? array_slice(file($adminLogPath), -50)
            : ['Admin log file not found.'];
    
        $studentLogs = file_exists($studentLogPath)
            ? array_slice(file($studentLogPath), -50)
            : ['Student log file not found.'];
    
        $healthLogs = file_exists($healthLogPath)
            ? array_slice(file($healthLogPath), -50)
            : ['Health log file not found.'];
    
        return view('admin.logs', compact('adminLogs', 'studentLogs', 'healthLogs'));
    }
}