<?php

namespace App\Services\Property;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\TipoUnidad;
use Illuminate\Support\Facades\DB;

class CopropiedadSettingsService
{
    /**
     * Compute tactical metrics and parameters for the copropiedad settings overview.
     */
    public function getSettingsMetrics(Copropiedad $copropiedad): array
    {
        $totalUnidades = $copropiedad->unidades()->count();
        $totalModelos = $copropiedad->tiposUnidad()->count();
        
        $areaConstruida = (float) ($copropiedad->area_construida_total ?? 0);
        $areaPrivadaTotal = (float) $copropiedad->unidades()
            ->join('property.tipos_unidad', 'property.unidades.tipo_unidad_id', '=', 'property.tipos_unidad.id')
            ->sum('property.tipos_unidad.area_m2');

        $settings = $copropiedad->settings ?? Copropiedad::defaultSettings();
        $paymentsEnabled = (bool) ($settings['payments_enabled'] ?? false);
        $unitTypesLocked = (bool) ($copropiedad->unit_types_locked ?? false);

        return [
            'total_unidades' => $totalUnidades,
            'total_modelos' => $totalModelos,
            'area_construida_total' => $areaConstruida,
            'area_privada_total' => $areaPrivadaTotal,
            'unit_types_locked' => $unitTypesLocked,
            'payments_enabled' => $paymentsEnabled,
        ];
    }

    /**
     * Update copropiedad settings, merging defaults and saving global areas.
     */
    public function updateSettings(Copropiedad $copropiedad, array $data): bool
    {
        $currentSettings = $copropiedad->settings ?? Copropiedad::defaultSettings();
        $incomingSettings = $data['settings'] ?? [];

        $mergedSettings = array_merge($currentSettings, $incomingSettings);

        $updatePayload = [
            'settings' => $mergedSettings,
        ];

        if (array_key_exists('area_construida_total', $data) && $data['area_construida_total'] !== null) {
            $updatePayload['area_construida_total'] = $data['area_construida_total'];
        }

        return $copropiedad->update($updatePayload);
    }

    /**
     * Delete an unlinked unit model, detaching its architectural components safely.
     */
    public function deleteTipoUnidad(TipoUnidad $tipoUnidad): bool
    {
        if ($tipoUnidad->unidades()->exists()) {
            throw new \DomainException('No se puede eliminar un modelo arquitectónico que ya tiene unidades asignadas.');
        }

        return DB::transaction(function () use ($tipoUnidad) {
            $tipoUnidad->componentes()->detach();
            return (bool) $tipoUnidad->delete();
        });
    }
}
