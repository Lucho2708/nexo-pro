<?php

declare(strict_types=1);

namespace NexoPro\Property\Services;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use Illuminate\Support\Facades\DB;
use NexoPro\Property\DTOs\ImportUnitRowDTO;

/**
 * UnitBulkImportExecutor
 *
 * [LÓGICA DE NEGOCIO]
 * Persiste de forma atómica y transaccional el conjunto de unidades validadas.
 * Vincula cada unidad física a su modelo arquitectónico certificado y actualiza
 * el censo total de la copropiedad, certificando la configuración.
 *
 * [PRINCIPIOS SOLID]
 * - SRP: Su única responsabilidad es ejecutar la persistencia masiva atómica de unidades.
 */
class UnitBulkImportExecutor
{
    /**
     * @param Copropiedad $copropiedad
     * @param array<int, ImportUnitRowDTO> $rows
     * @param array<string, array{id: string, area_m2: float}> $modelosCatalog
     * @return int Cantidad de unidades persistidas
     */
    public function execute(Copropiedad $copropiedad, array $rows, array $modelosCatalog): int
    {
        $normalizedCatalog = [];
        foreach ($modelosCatalog as $key => $val) {
            $normalizedCatalog[mb_strtolower(trim((string)$key))] = (string)($val['id'] ?? '');
        }

        return DB::transaction(function () use ($copropiedad, $rows, $normalizedCatalog) {
            $persistedCount = 0;

            foreach ($rows as $row) {
                $key = mb_strtolower($row->modelo);
                $tipoUnidadId = $normalizedCatalog[$key] ?? null;

                Unidad::updateOrCreate(
                    [
                        'copropiedad_id' => $copropiedad->id,
                        'torre' => $row->torre,
                        'nombre' => $row->nombre,
                    ],
                    [
                        'piso' => $row->piso,
                        'tipo_unidad_id' => $tipoUnidadId,
                        'propietario_nombre' => $row->propietarioNombre,
                        'propietario_identificacion' => $row->propietarioIdentificacion,
                        'email_contacto' => $row->emailContacto,
                        'telefono_contacto' => $row->telefonoContacto,
                        'saldo_actual' => $row->saldoActual,
                        'coeficiente' => $row->coeficiente,
                    ]
                );

                $persistedCount++;
            }

            // Actualizar total de unidades y bloquear configuración
            $copropiedad->update([
                'unidades_totales' => $persistedCount,
                'unit_types_locked' => true,
            ]);

            return $persistedCount;
        });
    }
}
