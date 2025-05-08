<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LogViewerController extends Controller
{
    public function index()
    {
        $adminLogPath = storage_path('logs/laravel.log');
        $studentLogPath = base_path('../student/storage/logs/laravel.log'); // Adjust if needed

        $adminLogs = file_exists($adminLogPath)
            ? array_slice(file($adminLogPath), -50)
            : ['Admin log file not found.'];

        $studentLogs = file_exists($studentLogPath)
            ? array_slice(file($studentLogPath), -50)
            : ['Student log file not found.'];

        return view('admin.logs', compact('adminLogs', 'studentLogs'));
    }
}
