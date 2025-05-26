<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Log;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

return function (Schedule $schedule) {
    $schedule->command('app:smart-reschedule')->everyFiveMinutes();
    $schedule->command('app:mark-missed-appointments')->everyTwoHours();
    $schedule->command('app:system-health-check')->daily();
    $schedule->command('inspire')->everyMinute();
    $schedule->command('queue:work --once')->everyMinute();

    $schedule->call(function () {
    Log::info('Crontab is working and Laravel schedule is running.');
    })->everyMinute();
};

// dont forget to add
// * * * * * php /routeToProject/admin/artisan schedule:run >> /dev/null 2>&1
// in crontab



