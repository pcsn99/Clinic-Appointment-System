<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentMarkedPresent extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('You’ve Been Marked Present')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('✅ You have been successfully marked as present for your clinic appointment.')
            ->line('Thank you for showing up on time!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Marked Present',
            'message' => 'You have been successfully marked as present.',
        ];
    }
}
