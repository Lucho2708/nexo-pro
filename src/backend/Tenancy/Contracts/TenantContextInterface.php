<?php

declare(strict_types=1);

namespace NexoPro\Tenancy\Contracts;

/**
 * TenantContextInterface
 *
 * [LÓGICA DE NEGOCIO]
 * Contrato de acceso al contexto de copropiedad (inquilino o tenant) actualmente activo en la sesión.
 * Permite a cualquier módulo consultar a qué conjunto residencial o edificio pertenece la petición en curso,
 * asegurando que las operaciones financieras, de votación y de gestión inmobiliaria se ejecuten
 * con aislamiento de fronteras riguroso.
 *
 * [ASPECTO TÉCNICO]
 * Aplica Inversión de Dependencias (DIP).
 * Desacopla la lógica de negocio de la implementación de sesión de Laravel (Auth facade o request attributes).
 */
interface TenantContextInterface
{
    /**
     * [LÓGICA DE NEGOCIO]
     * Retorna el UUID de la copropiedad activa, o null si el usuario no ha seleccionado un conjunto.
     */
    public function getActiveCopropiedadId(): ?string;

    /**
     * [LÓGICA DE NEGOCIO]
     * Indica si la sesión actual cuenta con un contexto de copropiedad válido y debidamente inicializado.
     */
    public function hasActiveContext(): bool;

    /**
     * [LÓGICA DE NEGOCIO]
     * Indica si el usuario autenticado tiene permisos de Super Administrador con acceso trans-tenant.
     */
    public function isSuperAdmin(): bool;
}
