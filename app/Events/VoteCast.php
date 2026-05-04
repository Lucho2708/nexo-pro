<?php

namespace App\Events;

use App\Models\Asamblea;
use App\Models\Pregunta;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VoteCast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Asamblea $asamblea,
        public Pregunta $pregunta,
        public $voteData
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('asamblea.' . $this->asamblea->id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'VoteCast';
    }
}
