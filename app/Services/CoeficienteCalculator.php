<?php

namespace App\Services;

use App\Models\Copropiedad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoeficienteCalculator
{
    /**
     * Calcula y actualiza automáticamente los coeficientes de todas las unidades
     * de una copropiedad usando la fórmula:
     * Coeficiente (%) = (Área privada del inmueble / Área Construida Total del conjunto) * 100
     */
    public function calculateForCopropiedad(Copropiedad $copropiedad): void
    {
        if (!$copropiedad->area_construida_total || $copropiedad->area_construida_total <= 0) {
            Log::warning("No se pudo calcular coeficientes para Copropiedad {$copropiedad->id}: area_construida_total no definida o inválida.");
            return;
        }

        $areaTotal = (float) $copropiedad->area_construida_total;

        DB::transaction(function () use ($copropiedad, $areaTotal) {
            $unidades = $copropiedad->unidades()->with('tipoUnidad')->get();

            foreach ($unidades as $unidad) {
                // By default the area comes from the model (tipoUnidad)
                // If there's an exceptional individual area we would use it here
                $areaInmueble = $unidad->tipoUnidad ? (float) $unidad->tipoUnidad->area_m2 : 0;

                if ($areaInmueble > 0) {
                    $coeficiente = ($areaInmueble / $areaTotal) * 100;
                    
                    // Solo actualizamos si no ha sido sobreescrito manualmente (futuro: bandera is_custom_coeficiente)
                    $unidad->update([
                        'coeficiente' => round($coeficiente, 4)
                    ]);
                }
            }
        });

        Log::info("Coeficientes calculados para la Copropiedad {$copropiedad->id} basados en área total: {$areaTotal} m2");
    }
}
