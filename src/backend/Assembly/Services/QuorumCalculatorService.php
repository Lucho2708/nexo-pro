<?php

declare(strict_types=1);

namespace NexoPro\Assembly\Services;

use NexoPro\Assembly\DTOs\QuorumStateDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * QuorumCalculatorService
 *
 * [LÓGICA DE NEGOCIO]
 * Servicio de auditoría y cálculo de quórum en tiempo real para asambleas de copropietarios.
 * Evalúa continuamente si la sala virtual cumple los requisitos del Artículo 45 de la Ley 675 de 2001
 * (quórum deliberatorio ordinario > 50% o quórum calificado del 70% para decisiones de trascendencia).
 *
 * [ASPECTO TÉCNICO]
 * Cumple con SRP. Consulta las tablas de snapshot dinámico 'asquorum_{shortId}' para aislar las lecturas
 * intensivas de quórum respecto a la tabla 'property.unidades'.
 * Complejidad de cálculo: O(1) en base de datos mediante índices sobre la columna de presencia 'presente'.
 */
final class QuorumCalculatorService
{
    /**
     * [LÓGICA DE NEGOCIO]
     * Obtiene el estado actual del quórum para una asamblea en curso.
     *
     * [ASPECTO TÉCNICO]
     * Consulta con agregación SQL directa (SUM y COUNT) en la tabla aislada.
     */
    public function calculateQuorum(string $asambleaId, string $copropiedadId): QuorumStateDTO
    {
        $shortId = substr($asambleaId, 0, 8);
        $quorumTable = "asquorum_{$shortId}";

        if (Schema::hasTable($quorumTable)) {
            $stats = DB::table($quorumTable)
                ->selectRaw('
                    COUNT(*) as total_unidades,
                    COALESCE(SUM(coeficiente), 0) as total_coeficiente,
                    COUNT(CASE WHEN presente = true THEN 1 END) as presente_unidades,
                    COALESCE(SUM(CASE WHEN presente = true THEN coeficiente ELSE 0 END), 0) as presente_coeficiente
                ')
                ->first();

            return QuorumStateDTO::fromCalculations(
                asambleaId: $asambleaId,
                totalCoeficiente: (float) ($stats->total_coeficiente ?? 100.0),
                presenteCoeficiente: (float) ($stats->presente_coeficiente ?? 0.0),
                totalUnidades: (int) ($stats->total_unidades ?? 0),
                presenteUnidades: (int) ($stats->presente_unidades ?? 0)
            );
        }

        // Fallback a tabla general de unidades si el ecosistema dinámico no ha iniciado
        $totalUnidades = DB::table('property.unidades')
            ->where('copropiedad_id', $copropiedadId)
            ->count();

        $totalCoef = (float) DB::table('property.unidades')
            ->where('copropiedad_id', $copropiedadId)
            ->sum('coeficiente');

        return QuorumStateDTO::fromCalculations(
            asambleaId: $asambleaId,
            totalCoeficiente: $totalCoef > 0 ? $totalCoef : 100.0,
            presenteCoeficiente: 0.0,
            totalUnidades: $totalUnidades,
            presenteUnidades: 0
        );
    }
}
