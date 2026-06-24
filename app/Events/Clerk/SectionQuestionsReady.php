<?php

namespace App\Events\Clerk;

use App\Models\Clerking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SectionQuestionsReady implements ShouldBroadcastNow {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $session_id,
        public bool $valid = true,
        public bool $previousSent = false,
    ) {}

    public function broadcastOn(): Channel {
        return new PrivateChannel("clerking.{$this->session_id}");
    }

    public function broadcastAs(): string {
        return 'section.questions.ready';
    }
}
