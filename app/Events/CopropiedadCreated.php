<?php

namespace App\Events;

use App\Models\Copropiedad;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CopropiedadCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Copropiedad $copropiedad
    ) {}
}
