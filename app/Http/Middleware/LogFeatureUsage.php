<?php

namespace App\Http\Middleware;

use App\Modules\Operations\Models\FeatureUsageLog;
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

        // Only log for authenticated users with a valid copropiedad
        $user = $request->user();
        if ($user && $user->current_copropiedad_id && $response->isSuccessful()) {
            try {
                // Check if copropiedad exists to avoid FK violations during testing/edge cases
                if (\App\Modules\Property\Models\Copropiedad::where('id', $user->current_copropiedad_id)->exists()) {
                    FeatureUsageLog::create([
                        'user_id'        => $user->id,
                        'copropiedad_id' => $user->current_copropiedad_id,
                        'feature'        => $feature,
                    ]);
                }
            } catch (\Exception $e) {
                // Fail silently to not disrupt user experience for a non-critical log
                \Log::warning("Could not log feature usage: " . $e->getMessage());
            }
        }

        return $response;
    }
}
