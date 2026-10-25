<?php

namespace App\Modules\IAM\Services;

use App\Modules\IAM\Contracts\RoleRedirectionServiceInterface;
use App\Modules\IAM\Models\User;

class RoleRedirectionService implements RoleRedirectionServiceInterface
{
    /**
     * Resuelve la URL canónica inicial a la que debe ingresar el usuario según su rol.
     */
    public function getCanonicalUrl(User $user, bool $absolute = false): string
    {
        return match (true) {
            $user->isSuperAdmin() => route('superadmin.dashboard', [], $absolute),
            $user->isBackoffice() => route('backoffice.leads.index', [], $absolute),
            $user->isAdmin()      => route('admin.dashboard', [], $absolute),
            $user->isSecurity()    => route('porteria.dashboard', [], $absolute),
            default               => route('owner.dashboard', [], $absolute),
        };
    }

    /**
     * Sanitiza la URL 'intended' almacenada en sesión para prevenir desvíos cruzados entre entornos (OWASP A01).
     * Si la URL previa pertenece a un entorno incompatible con el rol actual, la purga de la sesión y retorna null.
     */
    public function sanitizeIntendedUrl(User $user, ?string $intendedUrl): ?string
    {
        if (empty($intendedUrl)) {
            return null;
        }

        $path = parse_url($intendedUrl, PHP_URL_PATH) ?? $intendedUrl;

        $isCompatible = match (true) {
            $user->isAdmin() => !str_starts_with($path, '/portal')
                                && !str_starts_with($path, '/super-admin')
                                && !str_starts_with($path, '/backoffice')
                                && !str_starts_with($path, '/porteria')
                                && !str_starts_with($path, '/login'),

            $user->isSuperAdmin() => str_starts_with($path, '/super-admin'),

            $user->isBackoffice() => str_starts_with($path, '/backoffice'),

            $user->isSecurity() => str_starts_with($path, '/porteria'),

            default => (str_starts_with($path, '/portal') || str_starts_with($path, '/reservas') || str_starts_with($path, '/pqrs'))
                       && !str_starts_with($path, '/admin')
                       && !str_starts_with($path, '/super-admin')
                       && !str_starts_with($path, '/backoffice')
                       && !str_starts_with($path, '/porteria'),
        };

        if (!$isCompatible) {
            session()->forget('url.intended');
            return null;
        }

        return $intendedUrl;
    }
}
