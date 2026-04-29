<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureOwner — Solo propietarios pueden acceder al portal residente.
 */
class EnsureOwner
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->isOwner()) {
            abort(403, 'Acceso denegado. Solo propietarios pueden acceder a esta sección.');
        }

        return $next($request);
    }
}
