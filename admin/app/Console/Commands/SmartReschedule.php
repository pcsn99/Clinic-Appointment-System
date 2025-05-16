<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SmartReschedule extends Command
{

    protected $signature = 'app:smart-reschedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(\App\Http\Controllers\AdminAppointmentController::class)->handleSmartRescheduling();
    }
}
