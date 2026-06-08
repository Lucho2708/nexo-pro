<?php

namespace App\Modules\Property\Services;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CoeficienteCalculator
{
    /**
     * Calcula y actualiza los coeficientes de todas las unidades.
     * Implementa ajuste de residuo para garantizar el 100.00000% legal.
     */
    public function calculateForCopropiedad(Copropiedad $copropiedad): void
    {
        if (!$copropiedad->area_construida_total || $copropiedad->area_construida_total <= 0) {
            Log::warning("Cálculo abortado: Copropiedad {$copropiedad->id} no tiene área total definida.");
            return;
        }

        $areaTotal = (float) $copropiedad->area_construida_total;
        $precision = 4; // Sincronizado con decimal(8, 4) en DB

        DB::transaction(function () use ($copropiedad, $areaTotal, $precision) {
            $unidades = $copropiedad->unidades()->with('tipoUnidad')->get();
            
            if ($unidades->isEmpty()) return;

            $sumaCalculada = 0;
            $unidadesData = [];

            // 1. Primer pase: Cálculo base
            foreach ($unidades as $unidad) {
                $areaInmueble = $unidad->tipoUnidad ? (float) $unidad->tipoUnidad->area_m2 : 0;
                
                if ($areaInmueble > 0) {
                    $coeficiente = round(($areaInmueble / $areaTotal) * 100, $precision);
                    $sumaCalculada += $coeficiente;
                    
                    $unidadesData[] = [
                        'model' => $unidad,
                        'area' => $areaInmueble,
                        'coeficiente' => $coeficiente
                    ];
                }
            }

            // 2. Calcular residuo (lo que falta o sobra para llegar a 100)
            $residuo = round(100 - $sumaCalculada, $precision);

            if ($residuo != 0 && !empty($unidadesData)) {
                $indiceAjuste = $this->identifyAdjustmentUnit($unidadesData);
                $unidadesData[$indiceAjuste]['coeficiente'] = round($unidadesData[$indiceAjuste]['coeficiente'] + $residuo, $precision);
                
                Log::info("Ajuste de coeficiente aplicado", [
                    'copropiedad' => $copropiedad->nombre,
                    'residuo' => $residuo,
                    'unidad_ajustada' => $unidadesData[$indiceAjuste]['model']->nombre
                ]);
            }

            // 3. Persistir en base de datos
            foreach ($unidadesData as $data) {
                $data['model']->update(['coeficiente' => $data['coeficiente']]);
            }
        });
    }

    /**
     * Identifica qué unidad debe recibir el ajuste de residuo.
     * Regla: Si todas son iguales, al azar. Si no, a la más grande.
     */
    protected function identifyAdjustmentUnit(array $unidadesData): int
    {
        $areas = array_column($unidadesData, 'area');
        $todasIguales = count(array_unique($areas)) === 1;

        if ($todasIguales) {
            // Caso 1: Todas iguales -> Azar
            return array_rand($unidadesData);
        }

        // Caso 2: Diferentes -> La unidad con el área más grande
        $maxArea = max($areas);
        $candidatos = array_keys($areas, $maxArea);
        
        // Si hay varias "más grandes" iguales (ej: dos penthouses iguales), elegir una al azar entre ellas
        return $candidatos[array_rand($candidatos)];
    }
}
