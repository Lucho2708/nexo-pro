<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Unidad;

class UnidadController extends Controller
{
    /**
     * Bulk generate mathematical units per tower.
     */
    public function bulkGenerate(Request $request)
    {
        $request->validate([
            'torre' => 'required|string|max:50',
            'pisos' => 'required|integer|min:1|max:150',
            'aptos_por_piso' => 'required|integer|min:1|max:100',
            'default_coeficiente' => 'nullable|numeric|min:0',
            'custom_settings' => [
                'nullable',
                'string',
                function ($attribute, $value, $fail) {
                    if (!empty($value) && !json_validate($value)) {
                        $fail("El campo {$attribute} debe ser un JSON válido.");
                    }
                },
            ],
        ]);

        $user = $request->user();
        if (!$user->current_copropiedad_id) {
            abort(403, 'No tienes un conjunto activo seleccionado.');
        }

        $copropiedadId = $user->current_copropiedad_id;
        $unidadesAInsertar = [];
        $now = now();

        for ($piso = 1; $piso <= $request->pisos; $piso++) {
            for ($apto = 1; $apto <= $request->aptos_por_piso; $apto++) {
                
                // Formato seguro con cero inicial si es menor a 10: ej 101, 102
                $numeroApto = str_pad($apto, 2, '0', STR_PAD_LEFT);
                $nombreUnidad = "{$piso}{$numeroApto}";

                $unidadesAInsertar[] = [
                    'copropiedad_id' => $copropiedadId,
                    'nombre' => $nombreUnidad,
                    'torre' => $request->torre,
                    'piso' => $piso,
                    'coeficiente' => $request->default_coeficiente ?? 1.0,
                    'saldo_actual' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Chunk insert/update para eficiencia (masivo) y evitar Duplicate Error
        foreach (array_chunk($unidadesAInsertar, 200) as $chunk) {
            Unidad::upsert($chunk, 
                ['copropiedad_id', 'torre', 'nombre'], // Unique By
                ['piso', 'coeficiente', 'updated_at']  // Update if exists
            );
        }

        return back()->with('success', count($unidadesAInsertar) . ' unidades registradas exitosamente en la ' . $request->torre);
    }
}
