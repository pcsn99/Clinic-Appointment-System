<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

return function (Schedule $schedule) {
    $schedule->command('app:smart-reschedule')->everyFiveMinutes();
    $schedule->command('app:mark-missed-appointments')->everyTwoHours();
    $schedule->command('app:system-health-check')->daily();
};





