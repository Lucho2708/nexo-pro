<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request and inject security headers.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers from OWASP Top 10 recommendations
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // Permissions-Policy: Permitimos cámara y micro para las asambleas virtuales
        $response->headers->set('Permissions-Policy', 'camera=(self), microphone=(self), geolocation=()');
        
        // Content-Security-Policy (CSP) - Blindaje contra XSS e inyecciones
        $scriptSources = "'self' 'unsafe-inline' 'unsafe-eval'";
        $styleSources = "'self' 'unsafe-inline' https://fonts.googleapis.com";
        $connectSources = "'self' ws: wss: *.livekit.cloud https://*.livekit.cloud *.trycloudflare.com localhost:7880 127.0.0.1:7880";
        
        // Permitir Vite en desarrollo para evitar pantalla en blanco (incluyendo puerto dinámico en public/hot)
        if (config('app.debug')) {
            [$httpList, $wsList] = $this->resolveViteDevelopmentOrigins();

            $scriptSources .= " {$httpList}";
            $styleSources .= " {$httpList}";
            $connectSources .= " {$httpList} {$wsList}";
        }

        $upgradeInsecure = (! config('app.debug') || str_starts_with((string) config('app.url'), 'https://')) ? "upgrade-insecure-requests; " : "";

        $csp = "default-src 'self'; " .
               $upgradeInsecure .
               "script-src {$scriptSources}; " .
               "style-src {$styleSources}; " .
               "font-src 'self' https://fonts.gstatic.com data:; " .
               "img-src 'self' data: blob: *; " .
               "connect-src {$connectSources}; " .
               "frame-src 'none'; " .
               "object-src 'none';";
        
        $response->headers->set('Content-Security-Policy', $csp);

        if ($request->isSecure() || app()->environment('production', 'staging') || str_starts_with((string) config('app.url'), 'https://')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    /**
     * Resolve allowed HTTP and WebSocket origins for the Vite development server.
     *
     * @return array{0: string, 1: string}
     */
    private function resolveViteDevelopmentOrigins(): array
    {
        $viteHttpOrigins = ['http://127.0.0.1:5173', 'http://localhost:5173'];
        $viteWsOrigins = ['ws://127.0.0.1:5173', 'ws://localhost:5173'];

        $hotPath = public_path('hot');
        if (is_file($hotPath)) {
            $hotUrl = rtrim(trim((string) file_get_contents($hotPath)), '/');
            if (filter_var($hotUrl, FILTER_VALIDATE_URL)) {
                $parsed = parse_url($hotUrl);
                $port = $parsed['port'] ?? 5173;
                $scheme = $parsed['scheme'] ?? 'http';
                $wsScheme = $scheme === 'https' ? 'wss' : 'ws';

                $viteHttpOrigins[] = "{$scheme}://127.0.0.1:{$port}";
                $viteHttpOrigins[] = "{$scheme}://localhost:{$port}";
                $viteWsOrigins[] = "{$wsScheme}://127.0.0.1:{$port}";
                $viteWsOrigins[] = "{$wsScheme}://localhost:{$port}";
            }
        }

        return [
            implode(' ', array_unique($viteHttpOrigins)),
            implode(' ', array_unique($viteWsOrigins)),
        ];
    }
}
