<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentMarkedPresentOrReverted extends Notification
{
    use Queueable;

    protected $isPresent;

    public function __construct(bool $isPresent)
    {
        $this->isPresent = $isPresent;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->isPresent ? 'Marked Present' : 'Attendance Reverted',
            'message' => $this->isPresent
                ? 'You have been marked as present by the clinic.'
                : 'Your attendance status was reverted by the clinic.',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject($this->isPresent ? 'Marked Present' : 'Attendance Reverted')
            ->greeting("Hello {$notifiable->name},")
            ->line($this->isPresent
                ? 'You have been marked as present by the clinic.'
                : 'Your attendance status was reverted by the clinic.')
            ->salutation('Thank you.');
    }
}
