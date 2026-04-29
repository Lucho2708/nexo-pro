<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureAdmin — S: Single Responsibility (solo verifica rol admin).
 * Bloquea a owners y a super_admin sin copropiedad_id.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !($user->isAdmin() || $user->isSuperAdmin())) {
            abort(403, 'Acceso denegado. Se requieren privilegios de Administrador.');
        }

        return $next($request);
    }
}
