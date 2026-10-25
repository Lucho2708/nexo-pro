<?php

declare(strict_types=1);

namespace NexoPro\Assembly\Contracts;

use NexoPro\Assembly\DTOs\CastVoteDTO;

/**
 * VotePublisherInterface
 *
 * [LÓGICA DE NEGOCIO]
 * Contrato de difusión de eventos de sufragio hacia la sala virtual en tiempo real.
 * Notifica a los administradores y copropietarios conectados sobre el avance del escrutinio
 * sin necesidad de recargar la página web.
 *
 * [ASPECTO TÉCNICO]
 * Aplica Inversión de Dependencias (DIP).
 * Desacopla la lógica de votación respecto a la tecnología de transporte (Laravel Reverb,
 * Pusher, Redis Pub/Sub, o Server-Sent Events).
 */
interface VotePublisherInterface
{
    /**
     * [LÓGICA DE NEGOCIO]
     * Publica el evento de un voto emitido hacia el canal de la asamblea.
     *
     * [ASPECTO TÉCNICO]
     * Comunicación asíncrona no bloqueante con latencia esperada < 15 ms en WebSocket.
     */
    public function publishVoteCast(string $asambleaId, string $preguntaId, CastVoteDTO $voteDTO, float $pesoTotal): void;
}
