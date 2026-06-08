<?php

namespace App\Modules\Property\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Property\Models\TipoUnidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Modules\Property\Models\Unidad;
use App\Modules\Property\Services\CoeficienteCalculator;

class TipoUnidadController extends \App\Http\Controllers\Controller
{
    /**
     * Store a new unit type with its components.
     */
    public function store(Request $request)
    {
        $copropiedad = auth()->user()->currentCopropiedad;

        if ($copropiedad->unit_types_locked && auth()->user()->role !== 'super_admin') {
            return back()->with('error', 'La configuración de unidades está bloqueada.');
        }

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'area_m2' => 'required|numeric|min:0',
            'componentes' => 'required|array|min:1',
            'componentes.*.id' => 'required|exists:componentes_unidad,id',
            'componentes.*.cantidad' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($copropiedad, $validated) {
            $tipo = TipoUnidad::create([
                'copropiedad_id' => $copropiedad->id,
                'nombre' => $validated['nombre'],
                'area_m2' => $validated['area_m2'],
            ]);

            foreach ($validated['componentes'] as $comp) {
                $tipo->componentes()->attach($comp['id'], ['cantidad' => $comp['cantidad']]);
            }
        });

        return back()->with('success', 'Modelo de unidad creado correctamente.');
    }

    /**
     * Generate units in bulk based on topology
     */
    public function generateUnits(Request $request)
    {
        $copropiedad = auth()->user()->currentCopropiedad;

        if ($copropiedad->unit_types_locked && auth()->user()->role !== 'super_admin') {
            return back()->with('error', 'Las unidades ya han sido generadas y bloqueadas.');
        }

        $validated = $request->validate([
            'structure_type' => 'required|in:vertical,horizontal',
            'prefix' => 'required|string|max:50',
            'separator' => 'nullable|string|max:10',
            'towers' => 'required_if:structure_type,vertical|integer|min:1',
            'floors' => 'required_if:structure_type,vertical|integer|min:1',
            'units_per_floor' => 'required_if:structure_type,vertical|integer|min:1',
            'numbering_type' => 'required_if:structure_type,vertical|in:floor,continuous',
            'total_units' => 'required_if:structure_type,horizontal|integer|min:1',
        ]);

        $unitsData = [];
        $now = now();
        // Convert null separator to empty string if needed
        $separator = $request->has('separator') && !is_null($validated['separator']) ? $validated['separator'] : '';
        if ($separator === 'space') {
            $separator = ' ';
        }

        if ($validated['structure_type'] === 'vertical') {
            $towers = $validated['towers'];
            $floors = $validated['floors'];
            $unitsPerFloor = $validated['units_per_floor'];
            $isFloorNumbering = $validated['numbering_type'] === 'floor';

            for ($t = 1; $t <= $towers; $t++) {
                $unitCount = 1;
                for ($f = 1; $f <= $floors; $f++) {
                    for ($u = 1; $u <= $unitsPerFloor; $u++) {
                        $unitNumber = $isFloorNumbering
                            ? ($f * 100) + $u
                            : $unitCount;
                        
                        $nombre = $validated['prefix'] . ' ' . $t . $separator . $unitNumber;

                        $unitsData[] = [
                            'id' => Str::uuid()->toString(),
                            'copropiedad_id' => $copropiedad->id,
                            'nombre' => $nombre,
                            'torre' => $t,
                            'piso' => $f,
                            'saldo_actual' => 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                        $unitCount++;
                    }
                }
            }
        } else {
            $total = $validated['total_units'];
            for ($u = 1; $u <= $total; $u++) {
                $nombre = $validated['prefix'] . $separator . $u;
                
                $unitsData[] = [
                    'id' => Str::uuid()->toString(),
                    'copropiedad_id' => $copropiedad->id,
                    'nombre' => $nombre,
                    'torre' => null,
                    'piso' => null,
                    'saldo_actual' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::transaction(function () use ($copropiedad, $unitsData) {
            foreach (array_chunk($unitsData, 500) as $chunk) {
                Unidad::insert($chunk);
            }
            $copropiedad->update(['unit_types_locked' => true]);
        });

        // Trigger CoeficienteCalculator for initial baseline
        $calculator = new CoeficienteCalculator();
        $calculator->calculateForCopropiedad($copropiedad);

        return back()->with('success', count($unitsData) . ' unidades generadas correctamente.');
    }

    /**
     * Unlock the unit types configuration (Super Admin only).
     */
    public function unlock()
    {
        if (auth()->user()->role !== 'super_admin') {
            abort(403);
        }

        $copropiedad = auth()->user()->currentCopropiedad;
        $copropiedad->update(['unit_types_locked' => false]);

        return back()->with('success', 'Configuración desbloqueada para edición.');
    }
}
