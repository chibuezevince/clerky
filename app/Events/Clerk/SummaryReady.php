<?php

namespace App\Events\Clerk;

use App\Models\Clerking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SummaryReady implements ShouldBroadcastNow {
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Clerking $clerking,
        public $generated = true
    ) {
    }

    public function broadcastOn(): Channel {
        return new PrivateChannel("clerking.{$this->clerking->session_id}");
    }

    public function broadcastAs(): string {
        return 'summary.ready';
    }
}