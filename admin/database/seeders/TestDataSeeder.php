<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Appointment;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create 30 student users
        $students = collect();
        for ($i = 1; $i <= 30; $i++) {
            $students->push(User::create([
                'name' => "Student {$i}",
                'username' => "student{$i}",
                'email' => "student{$i}@example.com",
                'password' => Hash::make('password'),
                'course' => 'BSIT',
                'year' => '3',
                'contact_number' => '0912345678' . $i,
                'role' => 'student',
            ]));
        }

        // Create schedules
        $schedule1 = Schedule::create([
            'date' => '2025-04-05',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'slot_limit' => 10,
        ]);

        $schedule2 = Schedule::create([
            'date' => '2025-04-23',
            'start_time' => '08:00:00',
            'end_time' => '10:00:00',
            'slot_limit' => 20,
        ]);

        $schedule3 = Schedule::create([
            'date' => '2025-04-23',
            'start_time' => '10:00:00',
            'end_time' => '12:00:00',
            'slot_limit' => 20,
        ]);

        // Assign appointments
        // 10 students for April 5
        for ($i = 0; $i < 10; $i++) {
            Appointment::create([
                'user_id' => $students[$i]->id,
                'schedule_id' => $schedule1->id,
                'status' => 'booked',
                'is_present' => false,
            ]);
        }

        // 10 students for April 23 - first slot
        for ($i = 10; $i < 20; $i++) {
            Appointment::create([
                'user_id' => $students[$i]->id,
                'schedule_id' => $schedule2->id,
                'status' => 'booked',
                'is_present' => false,
            ]);
        }

        // 10 students for April 23 - second slot
        for ($i = 20; $i < 30; $i++) {
            Appointment::create([
                'user_id' => $students[$i]->id,
                'schedule_id' => $schedule3->id,
                'status' => 'booked',
                'is_present' => false,
            ]);
        }
    }
}
