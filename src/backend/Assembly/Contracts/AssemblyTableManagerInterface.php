<?php

declare(strict_types=1);

namespace NexoPro\Assembly\Contracts;

/**
 * AssemblyTableManagerInterface
 *
 * [LÓGICA DE NEGOCIO]
 * Contrato de aislamiento dinámico para la infraestructura de persistencia de asambleas virtuales.
 * Garantiza que cada sesión de asamblea cuente con un espacio de almacenamiento segregado
 * para registrar quórum, sufragios y bitácoras probatorias sin colisionar con otras asambleas activas.
 *
 * [ASPECTO TÉCNICO]
 * Aplica el Principio de Segregación de Interfaces (ISP) e Inversión de Dependencias (DIP).
 * Desacopla la lógica de negocio de la implementación específica de tablas en PostgreSQL,
 * permitiendo sustituir el motor por particionamiento declarativo, DynamoDB o SQLite para testing.
 */
interface AssemblyTableManagerInterface
{
    /**
     * [LÓGICA DE NEGOCIO]
     * Aprovisiona en caliente las tablas aisladas de una asamblea si aún no han sido creadas.
     *
     * [ASPECTO TÉCNICO]
     * Ejecución idempotente DDL con verificación de existencia en metadata.
     */
    public function ensureAssemblyEcosystem(string $shortId, string $copropiedadId): void;

    /**
     * [LÓGICA DE NEGOCIO]
     * Persiste un sufragio en la tabla aislada de la asamblea con garantía de unicidad.
     *
     * [ASPECTO TÉCNICO]
     * Inserción protegida por restricción UNIQUE a nivel de índice B-Tree relacional.
     * @param array<string, mixed> $voteData Payload normalizado del sufragio.
     */
    public function registerVote(string $shortId, array $voteData, ?string $copropiedadId = null): void;

    /**
     * [LÓGICA DE NEGOCIO]
     * Verifica si una unidad ya emitió su sufragio para una moción específica.
     *
     * [ASPECTO TÉCNICO]
     * Búsqueda por índice B-Tree compuesto (pregunta_id, unidad_id) con complejidad O(log N).
     */
    public function hasVoted(string $shortId, string $preguntaId, string $unidadId): bool;

    /**
     * [LÓGICA DE NEGOCIO]
     * Registra un evento inmutable en la bitácora probatoria de la asamblea.
     *
     * [ASPECTO TÉCNICO]
     * Inserción append-only optimizada con columna JSONB e índice invertido GIN.
     */
    public function logEvent(string $shortId, string $copropiedadId, string $userId, ?string $unidadId, string $eventType, array $payload, string $ipAddress, string $userAgent): void;
}
