<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Schedule;

class AppointmentBooked extends Notification
{
    use Queueable;

    public $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Appointment Booked',
            'message' => "Your appointment has been successfully booked for {$this->schedule->date} at {$this->schedule->start_time}.",
        ];
    }
}
