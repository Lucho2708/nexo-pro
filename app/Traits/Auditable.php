<?php

namespace App\Traits;

use App\Models\FeatureUsageLog;
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
        // Inyectar datos técnicos base para soporte
        $baseMetadata = [
            'ip' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ];

        $fullMetadata = array_merge($baseMetadata, $metadata);

        // Sanitización estricta de privacidad
        $sanitizedMetadata = $this->sanitizeAuditMetadata($fullMetadata);

        FeatureUsageLog::create([
            'user_id'        => Auth::id(),
            'copropiedad_id' => Auth::user()?->current_copropiedad_id,
            'feature'        => strtoupper($feature),
            'action'         => strtoupper($action),
            'metadata'       => $sanitizedMetadata,
            'used_at'        => now(),
        ]);
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
