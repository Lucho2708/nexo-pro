<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HandRaised implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $asambleaId,
        public array $userData, // [name, unit, id, isRaised]
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("asamblea.{$this->asambleaId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'hand.raised';
    }
}
