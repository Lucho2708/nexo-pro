<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SuperAdmin\UpdateLicenseRequest;
use App\Modules\Property\Models\Copropiedad;
use App\Traits\Auditable;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LicenseController extends Controller
{
    use Auditable;

    /**
     * Display the license management dashboard.
     */
    public function index()
    {
        $copropiedades = Copropiedad::withCount(['users', 'unidades'])
            ->with(['administradores' => function($query) {
                $query->take(1); // Traer al menos un admin para soporte
            }])
            ->latest()
            ->get()
            ->map(function ($c) {
                // Ensure settings keys exist
                $c->settings = array_merge(Copropiedad::defaultSettings(), $c->settings ?? []);
                
                // Cálculo de días restantes
                $expiresAt = $c->license_expires_at ? \Carbon\Carbon::parse($c->license_expires_at) : null;
                $c->days_left = $expiresAt ? (int)now()->diffInDays($expiresAt, false) : null;
                
                return $c;
            });

        return Inertia::render('SuperAdmin/Licenses/Index', [
            'copropiedades' => $copropiedades,
        ]);
    }

    /**
     * Update license and module settings for a copropiedad.
     */
    public function update(UpdateLicenseRequest $request, Copropiedad $copropiedad)
    {
        $validated = $request->validated();

        // Separate settings from root model fields
        $settingKeys = ['payments_enabled', 'can_charge_online', 'pqrs_enabled', 'reservas_enabled', 'asamblea_virtual_active'];
        
        $settings = array_merge(
            $copropiedad->settings ?? [],
            $request->only($settingKeys)
        );

        $copropiedad->update([
            'settings'           => $settings,
            'plan'               => $validated['plan'] ?? $copropiedad->plan,
            'license_status'     => $validated['license_status'] ?? $copropiedad->license_status,
            'license_expires_at' => $validated['license_expires_at'] ?? $copropiedad->license_expires_at,
        ]);

        $this->audit('LICENCIAS', 'UPDATE_LICENSE', [
            'copropiedad_id' => $copropiedad->id,
            'copropiedad_nombre' => $copropiedad->nombre,
            'plan' => $validated['plan'] ?? $copropiedad->plan,
            'status' => $validated['license_status'] ?? $copropiedad->license_status,
        ]);

        return back()->with('success', "Licencia de {$copropiedad->nombre} actualizada correctamente.");
    }
}
