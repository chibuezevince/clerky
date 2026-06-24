<?php

namespace App\Notifications\User;

use App\Models\Clerking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SummaryGenerated extends Notification implements ShouldQueue {
    use Queueable;

    public function __construct(
        public Clerking $clerking,
    ) {
        $this->onQueue('emails');
    }

    public function via(object $notifiable): array {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->subject("AI Summary Ready - {$this->clerking->case_number}")
            ->greeting("Dear {$notifiable->name},")
            ->line("Your AI-generated clinical summary for case **{$this->clerking->case_number}** ({$this->clerking->unit->name}) is now ready.")
            ->action('View Summary', route('clerking.summary.edit', $this->clerking))
            ->line('You can review, edit, and confirm the summary before finalising.')
            ->salutation('Best Regards, Clerky');
    }

    public function toArray(object $notifiable): array {
        return [
            'title' => 'Summary Ready',
            'message' => "AI summary for case {$this->clerking->case_number} is ready.",
            'data' => [
                'url' => route('clerking.summary.edit', $this->clerking),
            ],
        ];
    }
}
