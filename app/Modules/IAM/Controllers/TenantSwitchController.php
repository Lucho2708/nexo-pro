<?php

declare(strict_types=1);

namespace App\Modules\IAM\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;

/**
 * TenantSwitchController
 *
 * [LÓGICA DE NEGOCIO]
 * Permite a usuarios autorizados (administradores con múltiples copropiedades a cargo, o propietarios
 * con apartamentos en más de un conjunto) conmutar de forma segura el contexto activo de la copropiedad.
 * - Valida de forma estricta que el usuario tenga un vínculo contractual o de titularidad legítimo antes del cambio.
 * - Evita que un copropietario vea las expensas o actas de un conjunto donde no reside.
 *
 * [ASPECTO TÉCNICO]
 * - Utiliza withoutGlobalScopes() para poder consultar unidades del usuario fuera del scope de la copropiedad actual.
 * - Utiliza nombres de tabla calificados dinámicamente ($copropiedad->getTable()) para compatibilidad total con PostgreSQL multi-schema.
 * - Persiste el nuevo current_copropiedad_id de forma atómica.
 */
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
            $canAccess = $user->managedCopropiedades()
                ->where($copropiedad->getTable() . '.id', $copropiedad->id)
                ->exists();
        } elseif ($user->isOwner()) {
            // Owners can only switch if they have at least one unit (apartment) in that property
            $canAccess = $user->unidades()
                ->withoutGlobalScopes()
                ->where((new Unidad())->getTable() . '.copropiedad_id', $copropiedad->id)
                ->exists();
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
