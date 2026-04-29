<?php

namespace App\Http\Middleware;

use App\Models\FeatureUsageLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * LogFeatureUsage — Registra silenciosamente el uso de módulos clave.
 * Se aplica a rutas específicas via alias en las route definitions.
 * SRP: solo registra, nunca bloquea.
 */
class LogFeatureUsage
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $response = $next($request);

        // Only log for authenticated users with a copropiedad
        $user = $request->user();
        if ($user && $user->current_copropiedad_id && $response->isSuccessful()) {
            FeatureUsageLog::create([
                'user_id'        => $user->id,
                'copropiedad_id' => $user->current_copropiedad_id,
                'feature'        => $feature,
            ]);
        }

        return $response;
    }
}
