<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Modules\IAM\Models\LegalDocument;
use Illuminate\Support\Facades\Auth;

class EnsureLegalConsent
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Excluir rutas que no deben ser bloqueadas
        $excludedRoutes = [
            'legal.consent',
            'legal.accept',
            'logout',
        ];

        foreach ($excludedRoutes as $routeName) {
            if ($request->routeIs($routeName)) {
                return $next($request);
            }
        }

        // También excluir patrones de URL si es necesario (ej: api)
        if ($request->is('api/*') || $request->is('super-admin*')) {
            return $next($request);
        }

        // Si es Super Admin, no bloquear para que pueda arreglar los documentos si hay un error
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Obtener documentos activos que requieren aceptación (Términos y Privacidad)
        $requiredTypes = ['terms', 'privacy'];
        
        foreach ($requiredTypes as $type) {
            $document = LegalDocument::getActive($type);
            
            if ($document) {
                $accepted = $user->consents()
                    ->where('legal_document_id', $document->id)
                    ->wherePivot('version', $document->version)
                    ->exists();

                \Log::info("Checking for user {$user->id}, doc {$document->id} v{$document->version}. Accepted: " . ($accepted ? 'yes' : 'no'));
                if (!$accepted) {
                    \Log::info("User {$user->id} redirected for {$type} v{$document->version}");
                    return redirect()->route('legal.consent', ['type' => $type]);
                }
            }
        }

        return $next($request);
    }
}
