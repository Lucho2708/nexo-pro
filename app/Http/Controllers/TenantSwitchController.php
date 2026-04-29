<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Copropiedad;

class TenantSwitchController extends Controller
{
    /**
     * Switch the user's active copropiedad securely (Multi-Tenant Context Switch).
     */
    public function __invoke(Request $request, Copropiedad $copropiedad)
    {
        $user = $request->user();
        $canAccess = false;

        if ($user->isSuperAdmin()) {
            return back()->with('error', 'Como Super Administrador, gestionas el sistema global y no copropiedades individuales.');
        } elseif ($user->isAdmin()) {
            // Admins can only switch to properties they explicitly manage
            $canAccess = $user->managedCopropiedades()->where('copropiedades.id', $copropiedad->id)->exists();
        } elseif ($user->isOwner()) {
            // Owners can only switch if they have at least one unit (apartment) in that property
            // We use withoutGlobalScopes because the user might be currently scoped to another property
            $canAccess = $user->unidades()->withoutGlobalScopes()->where('unidades.copropiedad_id', $copropiedad->id)->exists();
        }

        if (! $canAccess) {
            abort(403, 'No tienes permiso para gestionar ni visualizar este conjunto.');
        }

        // Apply context switch permanently for this session/user
        $user->current_copropiedad_id = $copropiedad->id;
        $user->save();

        return back()->with('success', "Has cambiado al conjunto: {$copropiedad->nombre}");
    }
}
