<?php

declare(strict_types=1);

namespace NexoPro\Infrastructure\Adapters;

use NexoPro\Tenancy\Contracts\TenantContextInterface;
use Illuminate\Support\Facades\Auth;

/**
 * LaravelTenantContextAdapter
 *
 * [LÓGICA DE NEGOCIO]
 * Adaptador de contexto de copropiedad que extrae el inquilino activo desde la sesión del usuario en Laravel.
 * Permite que los servicios del nuevo backend en src/backend/ validen las fronteras de seguridad multi-inquilino.
 *
 * [ASPECTO TÉCNICO]
 * Implementa TenantContextInterface desacoplando el dominio del Auth facade de Laravel.
 */
final class LaravelTenantContextAdapter implements TenantContextInterface
{
    public function getActiveCopropiedadId(): ?string
    {
        $user = Auth::user();
        return $user?->current_copropiedad_id ? (string) $user->current_copropiedad_id : null;
    }

    public function hasActiveContext(): bool
    {
        return $this->getActiveCopropiedadId() !== null;
    }

    public function isSuperAdmin(): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        return method_exists($user, 'isSuperAdmin') ? $user->isSuperAdmin() : false;
    }
}
