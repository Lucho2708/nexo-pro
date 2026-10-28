<?php

namespace App\Modules\Property\Controllers\Admin;

use App\Http\Requests\Admin\StoreCopropiedadRequest;
use App\Http\Requests\Admin\UpdateCopropiedadSettingsRequest;
use App\Modules\Property\Actions\AddCopropiedadAction;
use App\Services\Property\CopropiedadSettingsService;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CopropiedadController extends \App\Http\Controllers\Controller
{
    /**
     * Show the form for creating a new copropiedad.
     */
    public function create(): Response
    {
        return Inertia::render('Admin/Copropiedades/Create');
    }

    /**
     * Store a newly created copropiedad and associate it with the current admin.
     */
    public function store(StoreCopropiedadRequest $request, AddCopropiedadAction $addAction)
    {
        $validated = $request->validated();

        $addAction->execute($request->user(), $validated);

        return redirect()->route('dashboard')->with('success', '¡Nuevo conjunto añadido correctamente!');
    }
    /**
     * Show the settings page for the current copropiedad.
     */
    public function settings(CopropiedadSettingsService $settingsService): Response
    {
        $copropiedad = auth()->user()->currentCopropiedad;
        $copropiedad->load('tiposUnidad.componentes');
        
        $componentes = \App\Modules\Property\Models\ComponenteUnidad::orderBy('nombre')->get();
        if ($componentes->isEmpty()) {
            $defaultNames = [
                'Habitación',
                'Baño',
                'Cocina',
                'Sala',
                'Comedor',
                'Balcón',
                'Terraza',
                'Estudio',
                'Zona de Ropas',
                'Patio',
                'Parqueadero',
                'Depósito',
            ];
            foreach ($defaultNames as $nombre) {
                try {
                    \App\Modules\Property\Models\ComponenteUnidad::firstOrCreate(['nombre' => $nombre]);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("Error auto-seeding component {$nombre}: " . $e->getMessage());
                }
            }
            $componentes = \App\Modules\Property\Models\ComponenteUnidad::orderBy('nombre')->get();
        }

        $metrics = $settingsService->getSettingsMetrics($copropiedad);

        return Inertia::render('Admin/Settings/Index', [
            'copropiedad' => $copropiedad,
            'settings' => $copropiedad->settings ?? \App\Modules\Property\Models\Copropiedad::defaultSettings(),
            'componentes_catalogo' => $componentes,
            'is_super_admin' => auth()->user()->role === 'super_admin',
            'metrics' => $metrics,
        ]);
    }

    /**
     * Update the settings for the current copropiedad.
     */
    public function updateSettings(UpdateCopropiedadSettingsRequest $request, CopropiedadSettingsService $settingsService)
    {
        $copropiedad = auth()->user()->currentCopropiedad;

        $settingsService->updateSettings($copropiedad, $request->validated());

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
