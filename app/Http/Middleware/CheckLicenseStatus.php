<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CheckLicenseStatus
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dump($request->url());
        // Solo aplicar validación si el usuario está autenticado
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // 1. Super Admins always have access
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // 2. Get the current copropiedad
        $copropiedad = $user->currentCopropiedad()->first();

        if ($copropiedad) {
            // 3. Check for Suspended status
            if ($copropiedad->license_status === 'suspended') {
                return Inertia::render('Error/LicenseSuspended', [
                    'message' => 'El servicio para este conjunto ha sido suspendido temporalmente. Contacte a soporte de PH360.'
                ])->toResponse($request)->setStatusCode(403);
            }

            // 4. Check for Expiration
            if ($copropiedad->license_expires_at && now()->isAfter($copropiedad->license_expires_at)) {
                // Si tiene periodo de prueba extendido, permitir
                if ($copropiedad->trial_ends_at && now()->isBefore($copropiedad->trial_ends_at)) {
                    return $next($request);
                }

                return Inertia::render('Error/LicenseSuspended', [
                    'message' => 'La licencia de uso ha vencido. Por favor renueve su suscripción.'
                ])->toResponse($request)->setStatusCode(403);
            }
        }

        return $next($request);
    }
}
