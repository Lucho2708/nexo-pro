<?php

declare(strict_types=1);

namespace NexoPro\Assembly\Services;

use NexoPro\Assembly\Contracts\AssemblyTableManagerInterface;
use NexoPro\Assembly\Contracts\VotePublisherInterface;
use NexoPro\Assembly\DTOs\CastVoteDTO;
use RuntimeException;
use InvalidArgumentException;
use Illuminate\Support\Facades\DB;

/**
 * AtomicVotingEngine
 *
 * [LÓGICA DE NEGOCIO]
 * Motor central de sufragio masivo y atómico para asambleas de propiedad horizontal.
 * Garantiza que cada copropietario o apoderado vote exactamente una vez por cada inmueble representado,
 * ponderando con precisión decimal sus coeficientes, registrando la bitácora legal probatoria de auditoría
 * (Habeas Data y Ley 675) y transmitiendo el avance en tiempo real a los moderadores y sala virtual.
 *
 * [ASPECTO TÉCNICO]
 * Cumple estrictamente con el Principio de Responsabilidad Única (SRP) e Inversión de Dependencias (DIP).
 * - Delega la persistencia aislada a AssemblyTableManagerInterface.
 * - Delega la difusión WebSocket a VotePublisherInterface.
 * - Ejecuta las mutaciones dentro de una transacción ACID con doble cerrojo de concurrencia:
 *   1) Validación lógica en memoria (O(1)).
 *   2) Restricción dura UNIQUE(pregunta_id, unidad_id) en motor PostgreSQL.
 * Complejidad algorítmica: O(U) donde U es el número de unidades apoderadas por el votante (U <= 10).
 */
final class AtomicVotingEngine
{
    public function __construct(
        private readonly AssemblyTableManagerInterface $tableManager,
        private readonly VotePublisherInterface $votePublisher
    ) {}

    /**
     * [LÓGICA DE NEGOCIO]
     * Procesa la emisión de votos para una o múltiples unidades en una moción activa.
     * Si alguna unidad ya votó, la omite; si ninguna puede votar, rechaza la operación con excepción legal.
     *
     * [ASPECTO TÉCNICO]
     * Envuelve la ejecución en DB::transaction. Realiza inserciones atómicas sin bloqueos a nivel de tabla
     * maestra gracias al aislamiento dinámico de tablas asvotos_{shortId}.
     *
     * @param CastVoteDTO $voteDTO DTO inmutable con la información del voto y unidades.
     * @param string $copropiedadId UUID del conjunto residencial titular de la asamblea.
     * @return int Cantidad de votos registrados con éxito.
     * @throws RuntimeException Si la votación ya fue ejercida en su totalidad o falla la atomicidad.
     */
    public function processVote(CastVoteDTO $voteDTO, string $copropiedadId): int
    {
        if (empty($voteDTO->unidades)) {
            throw new InvalidArgumentException('El votante no tiene unidades vinculadas para ejercer el sufragio.');
        }

        $shortId = substr($voteDTO->asambleaId, 0, 8);

        return DB::transaction(function () use ($voteDTO, $shortId, $copropiedadId): int {
            // 1. Asegurar ecosistema dinámico aprovisionado
            $this->tableManager->ensureAssemblyEcosystem($shortId, $copropiedadId);

            $votosExitosos = 0;
            $pesoAcumulado = 0.0;

            foreach ($voteDTO->unidades as $unidad) {
                $unidadId = (string) $unidad['id'];
                $coeficiente = (float) ($unidad['coeficiente'] ?? 0.0);

                // 2. Primer cerrojo: Verificación lógica rápida
                if ($this->tableManager->hasVoted($shortId, $voteDTO->preguntaId, $unidadId)) {
                    continue;
                }

                // 3. Persistencia atómica en tabla aislada (Segundo cerrojo: Restricción UNIQUE)
                $this->tableManager->registerVote($shortId, [
                    'pregunta_id' => $voteDTO->preguntaId,
                    'opcion_id'   => $voteDTO->opcionId,
                    'unidad_id'   => $unidadId,
                    'user_id'     => $voteDTO->userId,
                    'peso'        => $coeficiente,
                ], $copropiedadId);

                // 4. Registro inmutable en bitácora de auditoría probatoria (GIN Index)
                $this->tableManager->logEvent(
                    shortId: $shortId,
                    copropiedadId: $copropiedadId,
                    userId: $voteDTO->userId,
                    unidadId: $unidadId,
                    eventType: 'vote_cast',
                    payload: [
                        'pregunta_id' => $voteDTO->preguntaId,
                        'opcion_id'   => $voteDTO->opcionId,
                        'peso'        => $coeficiente,
                    ],
                    ipAddress: $voteDTO->ipAddress,
                    userAgent: $voteDTO->userAgent
                );

                $votosExitosos++;
                $pesoAcumulado += $coeficiente;
            }

            if ($votosExitosos === 0) {
                throw new RuntimeException('Todas las unidades vinculadas ya han emitido su sufragio para esta moción.');
            }

            // 5. Difusión en tiempo real hacia la sala virtual y pantalla de resultados
            $this->votePublisher->publishVoteCast(
                asambleaId: $voteDTO->asambleaId,
                preguntaId: $voteDTO->preguntaId,
                voteDTO: $voteDTO,
                pesoTotal: round($pesoAcumulado, 5)
            );

            return $votosExitosos;
        });
    }
}
