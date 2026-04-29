<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Copropiedad;

class InitializeTenancyByAuthenticatedUser
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // No inicializar durante los tests para evitar conflictos con SQLite :memory:
        if (app()->environment('testing')) {
            return $next($request);
        }

        $user = Auth::user();

        if ($user && $user->current_copropiedad_id) {
            try {
                $copropiedad = Copropiedad::find($user->current_copropiedad_id);
                
                if ($copropiedad && !tenancy()->initialized) {
                    // Inicializar sin disparar eventos que puedan romper el contexto
                    tenancy()->initialize($copropiedad);
                }
            } catch (\Throwable $e) {
                // Silencioso en caso de error de inicialización
            }
        }

        return $next($request);
    }
}
