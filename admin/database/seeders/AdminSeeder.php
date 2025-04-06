<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Clinic Admin',
            'username' => 'admin@clinic',
            'password' => Hash::make('password123'), // Use a safe default
            'role' => 'clinic',
        ]);

        Admin::create([
            'name' => 'Registrar Viewer',
            'username' => 'registrar@school',
            'password' => Hash::make('password123'),
            'role' => 'registrar',
        ]);

        Admin::create([
            'name' => 'PE Viewer',
            'username' => 'pe@school',
            'password' => Hash::make('password123'),
            'role' => 'pe',
        ]);
    }
}

