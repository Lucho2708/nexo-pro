<?php

namespace App\Modules\Asamblea\Events;

use App\Modules\Asamblea\Models\Intervencion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IntervencionUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $asambleaId,
        public array $intervencionData // Incluye status, user_id, duration_seconds, etc.
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel("asamblea.{$this->asambleaId}"),
        ];
    }

    public function broadcastAs(): string
    {
        return 'intervencion.updated';
    }
}
