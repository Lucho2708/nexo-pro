<?php

namespace App\Modules\Asamblea\Services;

use App\Modules\Asamblea\Models\Intervencion;
use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\IAM\Models\User;
use App\Modules\Asamblea\Events\IntervencionUpdated;
use Carbon\Carbon;

class IntervencionService
{
    public function __construct(
        protected AsambleaService $asambleaService
    ) {}

    /**
     * Request a new intervention for a user.
     */
    public function request(Asamblea $asamblea, User $user): Intervencion
    {
        $existing = Intervencion::where('asamblea_id', $asamblea->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['pending', 'active'])
            ->first();

        if ($existing) {
            return $existing;
        }

        $intervencion = Intervencion::create([
            'asamblea_id' => $asamblea->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        $this->broadcastUpdate($intervencion);

        return $intervencion;
    }

    /**
     * Grant the word to a user.
     */
    public function grant(Intervencion $intervencion): Intervencion
    {
        if ($this->hasActiveIntervencion($intervencion->asamblea_id)) {
            throw new \Exception('Ya hay una intervención activa. Debe finalizarla primero.');
        }

        $intervencion->update([
            'status' => 'active',
            'started_at' => now(),
            'duration_seconds' => 180, // Default 3 min
        ]);

        $this->broadcastUpdate($intervencion);

        return $intervencion;
    }

    /**
     * Close an active intervention.
     */
    public function close(Intervencion $intervencion, bool $force = false, ?string $notes = null): Intervencion
    {
        $finishedAt = now();
        $startedAt = $intervencion->started_at ?? $finishedAt;
        $duration = (int) abs($finishedAt->diffInSeconds($startedAt));

        $status = $force ? 'forced_close' : 'completed';

        $intervencion->update([
            'status' => $status,
            'finished_at' => $finishedAt,
            'duration_seconds' => $duration,
            'notes' => $notes ?? $intervencion->notes,
        ]);

        $this->broadcastUpdate($intervencion);

        // Audit Log
        $this->asambleaService->logEvent(
            $intervencion->asamblea, 
            $intervencion->user, 
            $this->asambleaService->getAuthenticatedUnit($intervencion->user, $intervencion->asamblea) ?? new \App\Modules\Property\Models\Unidad(), 
            'intervention_completed', 
            [
                'status' => $status,
                'duration' => $duration,
                'notes' => $notes,
            ]
        );

        return $intervencion;
    }

    /**
     * Extend the time of an active intervention.
     */
    public function extend(Intervencion $intervencion, int $seconds = 60): Intervencion
    {
        $intervencion->increment('duration_seconds', $seconds);
        
        $this->broadcastUpdate($intervencion);

        // Audit Log
        $this->asambleaService->logEvent(
            $intervencion->asamblea, 
            $intervencion->user, 
            $this->asambleaService->getAuthenticatedUnit($intervencion->user, $intervencion->asamblea) ?? new \App\Modules\Property\Models\Unidad(), 
            'intervention_extended', 
            ['added_seconds' => $seconds]
        );

        return $intervencion;
    }

    /**
     * Cancel a pending intervention.
     */
    public function cancel(Intervencion $intervencion): Intervencion
    {
        $intervencion->update(['status' => 'cancelled']);
        $this->broadcastUpdate($intervencion);
        return $intervencion;
    }

    /**
     * Helper to check if there is an active intervention in the assembly.
     */
    protected function hasActiveIntervencion(string $asambleaId): bool
    {
        return Intervencion::where('asamblea_id', $asambleaId)
            ->where('status', 'active')
            ->exists();
    }

    /**
     * Broadcast the updated state of an intervention.
     */
    protected function broadcastUpdate(Intervencion $intervencion): void
    {
        $intervencion->load('user.unidades');
        broadcast(new IntervencionUpdated((string) $intervencion->asamblea_id, $intervencion->toArray()));
    }
}
