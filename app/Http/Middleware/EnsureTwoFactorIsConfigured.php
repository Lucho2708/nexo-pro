<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\Operations\Models\GlobalSetting;

class EnsureTwoFactorIsConfigured
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Global Disable Check
        if (! GlobalSetting::get('2fa_enabled', false)) {
            return $next($request);
        }

        $user = $request->user();

        // Si el usuario está autenticado pero no tiene 2FA habilitado completamente
        if ($user && ! $user->two_factor_secret) {
            
            // Excluir rutas de configuración de 2FA, logout y rutas internas de Fortify
            $exemptUrls = [
                'setup-2fa*', 
                'logout', 
                'two-factor*', 
                'user/two-factor*', 
                'user/confirmed-password-status',
                'user/confirm-password'
            ];

            if (! $request->is($exemptUrls)) {
                return redirect()->route('two-factor.setup');
            }
        }

        return $next($request);
    }
}
