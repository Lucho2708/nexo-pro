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
        $connectSources = "'self' ws: wss: *.livekit.cloud https://*.livekit.cloud *.trycloudflare.com localhost:7880 127.0.0.1:7880";
        
        // Permitir Vite en desarrollo para evitar pantalla en blanco
        if (config('app.debug')) {
            $scriptSources .= " http://127.0.0.1:5173 http://localhost:5173";
            $connectSources .= " ws://127.0.0.1:5173 ws://localhost:5173";
        }

        $csp = "default-src 'self'; " .
               "script-src {$scriptSources}; " .
               "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; " .
               "font-src 'self' https://fonts.gstatic.com data:; " .
               "img-src 'self' data: blob: *; " .
               "connect-src {$connectSources}; " .
               "frame-src 'none'; " .
               "object-src 'none';";
        
        $response->headers->set('Content-Security-Policy', $csp);

        if ($request->isSecure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
