<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Schedule;

class AppointmentRebooked extends Notification
{
    use Queueable;

    protected $schedule;

    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Rebooked Due to Absence',
            'message' => "You missed your appointment and have been rebooked today at {$this->schedule->start_time} - {$this->schedule->end_time}.",
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Rebooked')
            ->greeting("Hello {$notifiable->name},")
            ->line("You missed your original appointment.")
            ->line("You’ve been rebooked today at {$this->schedule->start_time} - {$this->schedule->end_time}.")
            ->salutation('Thank you.');
    }
}
