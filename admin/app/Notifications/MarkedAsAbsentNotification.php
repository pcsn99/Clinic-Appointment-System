<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Schedule;

class MarkedAsAbsentNotification extends Notification
{
    use Queueable;

    protected $schedule;

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
            'title' => 'Marked as Absent',
            'message' => "You missed your appointment on {$this->schedule->date} at {$this->schedule->start_time}.",
        ];
    }
}
