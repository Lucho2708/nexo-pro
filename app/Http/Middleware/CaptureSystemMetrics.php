<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CaptureSystemMetrics
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);
        $response = $next($request);
        $duration = (microtime(true) - $start) * 1000; // ms

        // Solo guardamos si no es una petición de assets o de debug para no saturar
        if (!$request->expectsJson() && str_contains($request->path(), 'assets')) {
             return $response;
        }

        try {
            \Illuminate\Support\Facades\DB::table('system_metrics')->insert([
                'latency_ms'  => $duration,
                'status_code' => $response->getStatusCode(),
                'path'        => $request->path(),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        } catch (\Exception $e) {
            // Silencio administrativo para no romper la app si falla la telemetría
        }

        return $response;
    }
}
