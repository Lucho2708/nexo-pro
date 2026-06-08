<?php

namespace App\Services\Tenant;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;

class TenantService
{
    /**
     * Cambia el contexto de copropiedad actual para un usuario.
     */
    public function switchToTenant(User $user, string $copropiedadId): bool
    {
        // Validar que el usuario tiene acceso a esta copropiedad
        $hasAccess = $user->isSuperAdmin() || 
                     $user->managedCopropiedades()->where('copropiedades.id', $copropiedadId)->exists() ||
                     $user->unidades()->where('copropiedad_id', $copropiedadId)->exists();

        if (!$hasAccess) {
            return false;
        }

        $user->update(['current_copropiedad_id' => $copropiedadId]);
        
        return true;
    }

    /**
     * Verifica si la suscripción de un conjunto permite usar una funcionalidad.
     */
    public function canUseFeature(Copropiedad $copropiedad, string $feature): bool
    {
        if ($copropiedad->license_status !== 'active') {
            return false;
        }

        $settings = $copropiedad->settings;
        return isset($settings['modules'][$feature]) && $settings['settings']['modules'][$feature] === true;
    }
}
