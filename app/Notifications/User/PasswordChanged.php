<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChanged extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct() {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting("Dear {$notifiable->name}, ")
            ->line('Your password has been changed successfully.')
            ->line('If you did not initiate this change, please contact our support team immediately.')
            ->salutation('Best Regards, Clerky');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Your password was changed',
            'message' => 'Password changed on '.now()->format('d-m-Y H:i:s'),
        ];
    }
}
