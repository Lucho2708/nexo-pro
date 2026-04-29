<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\StoreCopropiedadRequest;
use App\Http\Requests\Admin\UpdateCopropiedadSettingsRequest;
use App\Actions\Admin\AddCopropiedadAction;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class CopropiedadController extends Controller
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
    public function settings(): Response
    {
        $copropiedad = auth()->user()->currentCopropiedad;
        
        return Inertia::render('Admin/Settings/Index', [
            'copropiedad' => $copropiedad,
            'settings' => $copropiedad->settings ?? \App\Models\Copropiedad::defaultSettings()
        ]);
    }

    /**
     * Update the settings for the current copropiedad.
     */
    public function updateSettings(UpdateCopropiedadSettingsRequest $request)
    {
        $copropiedad = auth()->user()->currentCopropiedad;

        $validated = $request->validated();

        $currentSettings = $copropiedad->settings ?? \App\Models\Copropiedad::defaultSettings();
        
        // Merge settings
        $copropiedad->update([
            'settings' => array_merge($currentSettings, $validated['settings'])
        ]);

        return back()->with('success', 'Configuración actualizada correctamente.');
    }
}
