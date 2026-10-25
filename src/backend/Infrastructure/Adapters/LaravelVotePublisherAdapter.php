<?php

declare(strict_types=1);

namespace NexoPro\Infrastructure\Adapters;

use NexoPro\Assembly\Contracts\VotePublisherInterface;
use NexoPro\Assembly\DTOs\CastVoteDTO;
use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Asamblea\Models\Pregunta;
use App\Modules\Asamblea\Events\VoteCast;

/**
 * LaravelVotePublisherAdapter
 *
 * [LÓGICA DE NEGOCIO]
 * Adaptador de publicación en tiempo real que emite eventos de votación mediante Laravel Reverb WebSockets.
 * Notifica a los residentes y administradores presentes en la sala virtual sobre la llegada de un nuevo voto.
 *
 * [ASPECTO TÉCNICO]
 * Implementa VotePublisherInterface.
 * Dispara el evento VoteCast hacia el canal privado asamblea.{asambleaId}.
 * Latencia estimada en LAN/Localhost: < 15 ms.
 */
final class LaravelVotePublisherAdapter implements VotePublisherInterface
{
    public function publishVoteCast(string $asambleaId, string $preguntaId, CastVoteDTO $voteDTO, float $pesoTotal): void
    {
        $asamblea = Asamblea::find($asambleaId);
        $pregunta = Pregunta::find($preguntaId);

        if ($asamblea && $pregunta) {
            broadcast(new VoteCast($asamblea, $pregunta, [
                'user_id'         => $voteDTO->userId,
                'unidades_count'  => count($voteDTO->unidades),
                'total_peso'      => $pesoTotal,
            ]))->toOthers();
        }
    }
}
