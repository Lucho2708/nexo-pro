<?php

declare(strict_types=1);

namespace NexoPro\Infrastructure\Adapters;

use NexoPro\Assembly\Contracts\AssemblyTableManagerInterface;
use App\Services\Tenant\AssemblyTableManager;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * LaravelAssemblyTableManagerAdapter
 *
 * [LÓGICA DE NEGOCIO]
 * Adaptador de infraestructura que conecta el motor de asambleas agnóstico de NexoPro con el gestor
 * de tablas dinámicas de Laravel existente (AssemblyTableManager).
 * Garantiza que las asambleas continúen ejecutándose sobre tablas físicas aisladas en PostgreSQL
 * para eliminar la contención de bloqueos durante votaciones masivas.
 *
 * [ASPECTO TÉCNICO]
 * Aplica el patrón Adapter (GoF) y el Principio de Inversión de Dependencias (DIP).
 * Permite que AtomicVotingEngine consuma AssemblyTableManagerInterface sin acoplarse directamente a Laravel.
 */
final class LaravelAssemblyTableManagerAdapter implements AssemblyTableManagerInterface
{
    public function __construct(
        private readonly AssemblyTableManager $legacyManager
    ) {}

    public function ensureAssemblyEcosystem(string $shortId, string $copropiedadId): void
    {
        $votesTable = "asvotos_{$shortId}";
        if (!Schema::hasTable($votesTable)) {
            $this->legacyManager->createAssemblyEcosystem($shortId, $copropiedadId);
        }
    }

    public function registerVote(string $shortId, array $voteData, ?string $copropiedadId = null): void
    {
        $this->legacyManager->registerVote($shortId, $voteData, $copropiedadId);
    }

    public function hasVoted(string $shortId, string $preguntaId, string $unidadId): bool
    {
        return $this->legacyManager->hasVoted($shortId, $preguntaId, $unidadId);
    }

    public function logEvent(
        string $shortId,
        string $copropiedadId,
        string $userId,
        ?string $unidadId,
        string $eventType,
        array $payload,
        string $ipAddress,
        string $userAgent
    ): void {
        $logTable = "aslog_{$shortId}";

        if (Schema::hasTable($logTable)) {
            DB::table($logTable)->insert([
                'id'             => (string) Str::uuid(),
                'copropiedad_id' => $copropiedadId,
                'user_id'        => $userId,
                'unidad_id'      => $unidadId,
                'event_type'     => $eventType,
                'payload'        => json_encode($payload),
                'ip_address'     => $ipAddress,
                'user_agent'     => $userAgent,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }
}
