<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PinCodeSeeder extends Seeder
{
    public function run()
    {
        DB::table('pin_codes')->insert([
            [
                'purpose' => 'appointment_attendance',
                'pin_code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'type' => 'hourly',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'purpose' => 'slot_limit_override',
                'pin_code' => str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'type' => 'hourly',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
