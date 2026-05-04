<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Asamblea;
use App\Models\Copropiedad;
use App\Services\Tenant\StandaloneOnboardingService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StandaloneAsambleaController extends Controller
{
    public function __construct(
        protected StandaloneOnboardingService $onboardingService
    ) {}

    /**
     * Show the onboarding wizard.
     */
    public function create()
    {
        return Inertia::render('Admin/Asambleas/StandaloneOnboarding', [
            'default_settings' => Copropiedad::defaultSettings(),
        ]);
    }

    /**
     * Process the onboarding data and CSV.
     */
    public function store(Request $request)
    {
        $request->validate([
            'property_name' => 'required|string|max:255',
            'nit' => 'required|string|max:20|unique:copropiedades,nit',
            'direccion' => 'required|string|max:255',
            'ciudad' => 'required|string|max:100',
            'assembly_title' => 'required|string|max:255',
            'voting_method' => 'required|in:coeficiente,unidad',
            'units_data' => 'required|array|min:1',
        ]);

        // 1. Create a REAL Copropiedad (but with standalone restriction logic in UI)
        $copropiedad = Copropiedad::create([
            'nit' => $request->nit,
            'nombre' => $request->property_name,
            'direccion' => $request->direccion,
            'ciudad' => $request->ciudad,
            'unidades_totales' => count($request->units_data),
            'settings' => array_merge(Copropiedad::defaultSettings(), [
                'asamblea_virtual_enabled' => true,
                'voting_method' => $request->voting_method,
                'is_standalone' => true // Flag context here
            ]),
            'status' => 'active',
        ]);

        // 2. Create the Assembly
        $asamblea = Asamblea::create([
            'copropiedad_id' => $copropiedad->id,
            'titulo' => $request->assembly_title,
            'fecha' => now(),
            'status' => 'scheduled',
        ]);

        // 3. Import the data using the service
        $this->onboardingService->importExternalData($asamblea, $request->units_data);

        return redirect()->route('admin.asambleas.index')
            ->with('success', 'Asamblea Standalone configurada y cápsula generada exitosamente.');
    }
}
