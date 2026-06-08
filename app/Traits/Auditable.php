<?php

namespace App\Traits;

use App\Modules\Operations\Models\FeatureUsageLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait Auditable
{
    /**
     * Registra una acción en la auditoría del sistema.
     * 
     * @param string $feature El módulo afectado (ej: 'LICENCIAS', 'USUARIOS', 'PQRS')
     * @param string $action La acción realizada (ej: 'UPDATE_STATUS', 'CREATE', 'DELETE')
     * @param array $metadata Detalles técnicos adicionales (IP, Navegador, IDs de referencia)
     */
    public function audit(string $feature, string $action, array $metadata = []): void
    {
        $user = Auth::user();
        if (!$user) {
            return;
        }

        // Inyectar datos técnicos base para soporte
        $baseMetadata = [
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ];

        $fullMetadata = array_merge($baseMetadata, $metadata);

        // Sanitización estricta de privacidad
        $sanitizedMetadata = $this->sanitizeAuditMetadata($fullMetadata);

        try {
            // Solo registramos si el usuario está activo y opcionalmente verificamos copropiedad
            FeatureUsageLog::create([
                'user_id'        => $user->id,
                'copropiedad_id' => $user->current_copropiedad_id,
                'feature'        => strtoupper($feature),
                'action'         => strtoupper($action),
                'metadata'       => $sanitizedMetadata,
                'used_at'        => now(),
            ]);
        } catch (\Exception $e) {
            // Silencio administrativo en auditoría para no romper flujos críticos
            \Log::warning("Error en auditoría ($feature/$action): " . $e->getMessage());
        }
    }

    /**
     * Limpia datos sensibles para cumplir con estándares de privacidad (GPDR/Habeas Data).
     */
    protected function sanitizeAuditMetadata(array $metadata): array
    {
        $sensitiveKeys = [
            'password', 'password_confirmation', 'token', 'cvv', 
            'card_number', 'secret', 'current_password', 'new_password',
            'api_key', 'auth_token'
        ];

        $sanitized = [];
        foreach ($metadata as $key => $value) {
            if (in_array(strtolower($key), $sensitiveKeys)) {
                $sanitized[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitizeAuditMetadata($value);
            } else {
                $sanitized[$key] = $value;
            }
        }

        return $sanitized;
    }
}
