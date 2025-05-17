<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AppointmentBookedByAdmin extends Notification
{
    use Queueable;

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Appointment Booked by Admin',
            'message' => 'You have been booked for an appointment by the clinic.',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Booked by Admin')
            ->greeting("Hello {$notifiable->name},")
            ->line('You have been booked for an appointment by the clinic.')
            ->line('Please log in to your portal to view more details.')
            ->salutation('Thank you.');
    }
}