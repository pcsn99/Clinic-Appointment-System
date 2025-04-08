<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Clinic Admin',
            'username' => 'clinicadmin',
            'email' => 'admin@clinic.com',
            'password' => Hash::make('admin123'), 
            'course' => null,
            'year' => null,
            'contact_number' => null,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
