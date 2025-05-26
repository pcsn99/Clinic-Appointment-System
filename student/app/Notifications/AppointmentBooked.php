<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
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
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Appointment Confirmed')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("Your appointment has been successfully booked.")
            ->line("📅 Date: {$this->schedule->date}")
            ->line("⏰ Time: {$this->schedule->start_time}")
            ->line('Thank you for using our Clinic Appointment System!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Appointment Booked',
            'message' => "Your appointment has been successfully booked for {$this->schedule->date} at {$this->schedule->start_time}.",
        ];
    }
}
