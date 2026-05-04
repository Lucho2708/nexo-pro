<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectStandaloneAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $copropiedad = $user?->currentCopropiedad;
        $isStandalone = ($user && $user->is_standalone) || ($copropiedad && ($copropiedad->settings['is_standalone'] ?? false));

        if ($user && $user->role === 'admin' && $isStandalone) {
            // Si intenta acceder a cualquier cosa que no sea asambleas, perfil o logout, lo bloqueamos
            $allowedRoutes = [
                'admin.asambleas.*',
                'dashboard',
                'profile.*',
                'logout',
                'notifications.*'
            ];

            $isAllowed = false;
            foreach ($allowedRoutes as $route) {
                if ($request->routeIs($route)) {
                    $isAllowed = true;
                    break;
                }
            }

            if (!$isAllowed) {
                return redirect()->route('admin.asambleas.index')
                    ->with('error', 'Su cuenta está limitada únicamente al módulo de Asambleas Standalone.');
            }
        }

        return $next($request);
    }
}
