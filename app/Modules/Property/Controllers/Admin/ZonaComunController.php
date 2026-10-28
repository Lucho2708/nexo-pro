<?php

namespace App\Modules\Property\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Operations\ZonaComunService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ZonaComunController extends \App\Http\Controllers\Controller
{
    public function __construct(
        private ZonaComunService $zonaComunService
    ) {}

    public function index()
    {
        $copropiedadId = auth()->user()->current_copropiedad_id;
        $zonas = $this->zonaComunService->getAllByCopropiedad($copropiedadId);
        $stats = $this->zonaComunService->getMetrics($copropiedadId);

        return Inertia::render('Admin/Zonas/Index', [
            'zonas' => $zonas,
            'stats' => $stats,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'                      => 'required|string|max:255',
            'descripcion'                 => 'nullable|string',
            'capacidad_maxima'            => 'required|integer|min:1|max:5000',
            'costo'                       => 'required|numeric|min:0',
            'imagen_path'                 => 'nullable|string|max:1000',
            'activa'                      => 'nullable|boolean',
            'settings'                    => 'nullable|array',
            'settings.tipo'               => 'nullable|string|max:50',
            'settings.horario_apertura'   => 'nullable|string|max:10',
            'settings.horario_cierre'     => 'nullable|string|max:10',
            'settings.deposito_garantia'  => 'nullable|numeric|min:0',
            'settings.tiempo_maximo_horas'=> 'nullable|integer|min:1|max:24',
            'settings.requiere_aprobacion'=> 'nullable|boolean',
        ]);

        $this->zonaComunService->createZona($validated, auth()->user()->current_copropiedad_id);

        return back()->with('success', 'Zona común creada exitosamente.');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nombre'                      => 'required|string|max:255',
            'descripcion'                 => 'nullable|string',
            'capacidad_maxima'            => 'required|integer|min:1|max:5000',
            'costo'                       => 'required|numeric|min:0',
            'imagen_path'                 => 'nullable|string|max:1000',
            'activa'                      => 'nullable|boolean',
            'settings'                    => 'nullable|array',
            'settings.tipo'               => 'nullable|string|max:50',
            'settings.horario_apertura'   => 'nullable|string|max:10',
            'settings.horario_cierre'     => 'nullable|string|max:10',
            'settings.deposito_garantia'  => 'nullable|numeric|min:0',
            'settings.tiempo_maximo_horas'=> 'nullable|integer|min:1|max:24',
            'settings.requiere_aprobacion'=> 'nullable|boolean',
        ]);

        $this->zonaComunService->updateZona($id, $validated);

        return back()->with('success', 'Zona común actualizada.');
    }

    public function toggleStatus(string $id)
    {
        $this->zonaComunService->toggleStatus($id);
        return back()->with('success', 'Estado de la zona actualizado.');
    }

    public function destroy(string $id)
    {
        $result = $this->zonaComunService->deleteZonaSafely($id);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }
}
