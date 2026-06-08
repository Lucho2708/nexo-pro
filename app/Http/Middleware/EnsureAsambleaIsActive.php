<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAsambleaIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // If no user or no copropiedad, we can't check
        if (!$user || !$user->current_copropiedad_id) {
            abort(403, 'Acceso denegado: Copropiedad no identificada.');
        }

        $copropiedad = $user->currentCopropiedad;

        if (!$copropiedad || !$copropiedad->hasFeature('asamblea_virtual_active')) {
            abort(403, 'El módulo de Asamblea Virtual no está activo para este conjunto. Por favor, contacte a soporte para activarlo.');
        }

        return $next($request);
    }
}
