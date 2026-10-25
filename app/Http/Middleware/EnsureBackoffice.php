<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureBackoffice — S: Single Responsibility (solo verifica rol backoffice o superadmin).
 * Bloquea acceso no autorizado al CRM y prospección comercial de NEXO PRO.
 */
class EnsureBackoffice
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !($user->isBackoffice() || $user->isSuperAdmin())) {
            abort(403, 'Acceso denegado. Se requieren credenciales de Backoffice / Comercial.');
        }

        return $next($request);
    }
}
