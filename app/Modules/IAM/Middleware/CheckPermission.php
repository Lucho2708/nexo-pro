<?php

namespace App\Modules\IAM\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'No tienes los permisos necesarios (Exige: ' . $permission . ')'], 403);
            }
            
            abort(403, 'No tienes permisos para realizar esta acción.');
        }

        return $next($request);
    }
}
