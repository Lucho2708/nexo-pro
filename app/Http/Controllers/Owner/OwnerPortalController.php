<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Transaccion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OwnerPortalController extends Controller
{
    /**
     * Display the owner portal dashboard.
     */
    public function index()
    {
        $user = auth()->user();
        $currentCopropiedadId = $user->current_copropiedad_id;
        
        // Load units associated with the user via pivot, ONLY for the active copropiedad
        $unidades = $user->unidades()
            ->where('unidades.copropiedad_id', $currentCopropiedadId)
            ->with('copropiedad')
            ->get();
        
        // Calculate consolidated balance for current context
        $totalSaldo = $unidades->sum('saldo_actual');
        
        // Get last 10 transactions across all owned units
        $transacciones = Transaccion::whereIn('unidad_id', $unidades->pluck('id'))
            ->with(['unidad', 'concepto'])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->limit(10)
            ->get();

        // Get active or scheduled assemblies for the current copropiedad
        $asambleas = \App\Models\Asamblea::where('copropiedad_id', $currentCopropiedadId)
            ->whereIn('status', ['in_progress', 'scheduled'])
            ->orderBy('fecha', 'asc')
            ->get();
            
        return Inertia::render('Owner/Dashboard', [
            'unidades' => $unidades,
            'total_saldo' => $totalSaldo,
            'transacciones' => $transacciones,
            'asambleas' => $asambleas,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo_url' => "https://ui-avatars.com/api/?name=" . urlencode($user->name) . "&background=00173c&color=fff",
            ],
            'features' => [
                'payments_enabled' => $unidades->first()?->copropiedad?->hasFeature('payments_enabled') ?? false,
                'gateways' => collect($unidades->first()?->copropiedad?->getSetting('gateways', []))
                    ->filter(fn($g) => $g['enabled'] ?? false)
                    ->toArray(),
            ]
        ]);
    }

    /**
     * Display the owner's payment history and detailed balance.
     */
    public function payments()
    {
        $user = auth()->user();
        $currentCopropiedadId = $user->current_copropiedad_id;
        
        $unidades = $user->unidades()
            ->where('unidades.copropiedad_id', $currentCopropiedadId)
            ->with('copropiedad')
            ->get();
        
        $totalSaldo = $unidades->sum('saldo_actual');
        
        // Get all transactions for current owned units
        $transacciones = Transaccion::whereIn('unidad_id', $unidades->pluck('id'))
            ->with(['unidad', 'concepto'])
            ->orderBy('fecha', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return Inertia::render('Owner/Payments/Index', [
            'unidades' => $unidades,
            'total_saldo' => $totalSaldo,
            'transacciones' => $transacciones,
            'features' => [
                'payments_enabled' => $unidades->first()?->copropiedad?->hasFeature('payments_enabled') ?? false,
                'gateways' => collect($unidades->first()?->copropiedad?->getSetting('gateways', []))
                    ->filter(fn($g) => $g['enabled'] ?? false)
                    ->toArray(),
            ]
        ]);
    }
}
