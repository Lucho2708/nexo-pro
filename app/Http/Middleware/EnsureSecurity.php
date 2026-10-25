<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureSecurity — S: Single Responsibility (solo verifica rol security o admin supervisión).
 * Bloquea acceso no autorizado al módulo de garita / portería.
 */
class EnsureSecurity
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !($user->isSecurity() || $user->isAdmin() || $user->isSuperAdmin())) {
            abort(403, 'Acceso denegado. Se requieren credenciales de Seguridad / Portería.');
        }

        return $next($request);
    }
}
