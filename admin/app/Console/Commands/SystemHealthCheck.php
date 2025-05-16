<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AdminAppointmentController;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;

class SystemHealthCheck extends Command
{
    protected $signature = 'app:system-health-check';
    protected $description = 'Simulates core appointment booking and rescheduling logic using actual system controllers';

    public function handle()
    {
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/functional_test.log'),
        ])->info("==== SYSTEM HEALTH CHECK START (" . now() . ") ====");

        try {
            DB::beginTransaction();

            //create sched
            $scheduleRequest = new Request([
                'date' => now()->toDateString(),
                'start_time' => '08:00',
                'end_time' => '08:15',
                'slot_limit' => 1
            ]);
            app(ScheduleController::class)->store($scheduleRequest);
            $schedule = Schedule::latest()->first();
            Log::info("✅ Schedule created: ID {$schedule->id}");

            //book sched
            $bookingRequest = new Request([
                'user_id' => 3, //student1
                'schedule_id' => $schedule->id
            ]);
            app(AdminAppointmentController::class)->store($bookingRequest);
            Log::info("✅ Appointment booked for student1.");

            // 3. Simulate missed appointment
            $appointment = Appointment::where('user_id', 3)->where('schedule_id', $schedule->id)->first();
            $appointment->status = 'booked';
            $appointment->is_present = false;
            $appointment->save();
            Log::info("⚠️ Marked appointment as missed.");

            // 4. Create follow-up slot
            $followUpSchedule = Schedule::create([
                'date' => now()->toDateString(),
                'start_time' => '08:30',
                'end_time' => '08:45',
                'slot_limit' => 1,
            ]);
            Log::info("🕒 Follow-up slot created: {$followUpSchedule->start_time} - {$followUpSchedule->end_time}");

            // 5. Run smart rescheduling
            app(AdminAppointmentController::class)->handleSmartRescheduling();

            // 6. Check if student1 was rebooked
            $newAppointment = Appointment::where('user_id', 3)
                ->where('schedule_id', $followUpSchedule->id)
                ->first();

            if ($newAppointment) {
                Log::info("✅ Smart rescheduling successful. New appointment ID: {$newAppointment->id}");
            } else {
                Log::warning("❌ Smart rescheduling failed.");
            }

            DB::rollBack(); // Don't save test data
        } catch (\Exception $e) {
            Log::error("❌ ERROR: " . $e->getMessage());
        }

        Log::info("==== SYSTEM HEALTH CHECK END (" . now() . ") ====\n");
    }
}
