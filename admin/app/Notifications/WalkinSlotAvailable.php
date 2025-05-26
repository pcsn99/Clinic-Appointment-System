<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class WalkinSlotAvailable extends Notification implements ShouldQueue
{
    use Queueable;

    protected $customMessage;
    protected $schedule;

    public function __construct($customMessage = null, $schedule = null)
    {
        $this->customMessage = $customMessage;
        $this->schedule = $schedule;
    }
    public function via($notifiable)
    {
        return ['database', 'mail']; 
    }

    public function toDatabase($notifiable)
    {
        $time = optional($this->schedule)->start_time ?? 'a scheduled time today';

        return [
            'title' => 'Walk-In Slot Now Available',
            'message' => $this->customMessage
                ?: "A walk-in slot is now available. If it is full, please approach the clinic front desk for an override pin.",
        ];
    }

    public function toMail($notifiable)
    {
        $time = optional($this->schedule)->start_time ?? 'your schedule time';

        return (new MailMessage)
            ->subject('Walk-In Slot Available')
            ->line($this->customMessage
                ?: "A walk-in slot is now available today ")
            ->line('If full, please approach the clinic front desk for an override pin.')
            ->line('Thank you!');
    }
}
