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

    public function __construct($customMessage = null)
    {
        $this->customMessage = $customMessage;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Only database for now; mail will work once configured
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Walk-In Slot Now Available',
            'message' => $this->customMessage ?: 'A walk-in slot is now available today. If it is full, please approach the clinic front desk for an override pin.',
        ];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Walk-In Slot Available')
            ->line($this->customMessage ?: 'A walk-in slot is now available today.')
            ->line('If full, please approach the clinic front desk for an override pin.')
            ->line('Thank you!');
    }
}
