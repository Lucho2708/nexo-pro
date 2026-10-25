<?php

namespace App\Modules\IAM\Contracts;

use App\Modules\IAM\Models\User;

interface RoleRedirectionServiceInterface
{
    /**
     * Resuelve la URL canónica inicial a la que debe ingresar el usuario según su rol.
     *
     * @param User $user
     * @param bool $absolute
     * @return string
     */
    public function getCanonicalUrl(User $user, bool $absolute = false): string;

    /**
     * Sanitiza la URL 'intended' almacenada en sesión para prevenir desvíos cruzados entre entornos (OWASP A01).
     * Si la URL previa pertenece a un entorno incompatible con el rol actual, la purga de la sesión y retorna null.
     *
     * @param User $user
     * @param string|null $intendedUrl
     * @return string|null
     */
    public function sanitizeIntendedUrl(User $user, ?string $intendedUrl): ?string;
}
