<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Notifications\MarkedAsAbsentNotification;

class MarkMissedAppointments extends Command
{
    protected $signature = 'app:mark-missed-appointments';

    protected $description = 'Marks past booked appointments as absent if not marked present';

    public function handle()
    {
        $now = now();

        
        $missedAppointments = Appointment::where('status', 'booked')
        
            ->where('is_present', false)
            ->whereHas('schedule', function ($query) use ($now) {
                $query->where(function ($q) use ($now) {
                    $q->whereDate('date', '<', $now->toDateString()) 
                      ->orWhere(function ($q2) use ($now) {         
                          $q2->whereDate('date', $now->toDateString())
                             ->where('end_time', '<', $now->format('H:i'));
                      });
                });
            })
            ->with('schedule', 'user')
            ->get();

        foreach ($missedAppointments as $appointment) {
            $appointment->status = 'cancelled'; 
            $appointment->save();

            $appointment->user->notify(new MarkedAsAbsentNotification($appointment->schedule));
        }

        $this->info("Marked {$missedAppointments->count()} appointments as absent.");
    }
}
