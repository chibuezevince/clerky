<?php

namespace App\Notifications\User;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Otp $otp) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(User $notifiable): MailMessage
    {
        $purpose = $this->otp->purpose->label();
        $subject = "Your $purpose Code";

        return new MailMessage()
            ->subject($subject)
            ->markdown('vendor.mail.html.otp', [
                'otp' => $this->otp,
            ]);
    }
}
