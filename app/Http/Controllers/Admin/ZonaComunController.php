<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Operations\ZonaComunService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ZonaComunController extends Controller
{
    public function __construct(
        private ZonaComunService $zonaComunService
    ) {}

    public function index()
    {
        $copropiedadId = auth()->user()->current_copropiedad_id;
        $zonas = $this->zonaComunService->getAllByCopropiedad($copropiedadId);

        return Inertia::render('Admin/Zonas/Index', [
            'zonas' => $zonas
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'capacidad_maxima' => 'required|integer|min:1',
            'costo' => 'required|numeric|min:0',
        ]);

        $this->zonaComunService->createZona($validated, auth()->user()->current_copropiedad_id);

        return back()->with('success', 'Zona común creada exitosamente.');
    }

    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'capacidad_maxima' => 'required|integer|min:1',
            'costo' => 'required|numeric|min:0',
        ]);

        $this->zonaComunService->updateZona($id, $validated);

        return back()->with('success', 'Zona común actualizada.');
    }

    public function toggleStatus(string $id)
    {
        $this->zonaComunService->toggleStatus($id);
        return back()->with('success', 'Estado de la zona actualizado.');
    }
}
