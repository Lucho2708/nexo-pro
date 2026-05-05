<?php

namespace App\Modules\IAM\Interfaces;

use App\Modules\IAM\Models\User;

interface IAMServiceInterface
{
    /**
     * Valida las credenciales y autentica al usuario.
     */
    public function authenticate(array $credentials): bool;

    /**
     * Obtiene el usuario actualmente autenticado.
     */
    public function getCurrentUser(): ?User;

    /**
     * Cierra la sesión del usuario actual.
     */
    public function logout(): void;
}
