<?php

declare(strict_types=1);

namespace NexoPro\Assembly\Contracts;

/**
 * VotingTallyStrategyInterface
 *
 * [LÓGICA DE NEGOCIO]
 * Contrato de estrategia para el cómputo y escrutinio de votos en una asamblea de propiedad horizontal.
 * La legislación colombiana (Ley 675) estipula que la mayoría de decisiones se adoptan por mayoría
 * de coeficientes de copropiedad (votación económica/ponderada), pero ciertas elecciones (como comités
 * de convivencia o temas comunitarios informales) pueden pactarse por voto nominal (1 unidad = 1 voto).
 *
 * [ASPECTO TÉCNICO]
 * Aplica el patrón de diseño Strategy (Principio Abierto/Cerrado - OCP e Inversión de Dependencias - DIP).
 * Permite agregar nuevos algoritmos de escrutinio (ej: cociente electoral, mayoría especial de 70%,
 * voto preferencial) sin modificar los orquestadores ni la capa de persistencia.
 */
interface VotingTallyStrategyInterface
{
    /**
     * [LÓGICA DE NEGOCIO]
     * Identificador único de la estrategia de conteo (ej: 'coeficiente', 'nominal').
     *
     * [ASPECTO TÉCNICO]
     * Clave semántica para resolución dinámica mediante Service Container o Factory.
     */
    public function getIdentifier(): string;

    /**
     * [LÓGICA DE NEGOCIO]
     * Realiza el escrutinio de un conjunto de sufragios emitidos y genera el resumen oficial de resultados.
     *
     * [ASPECTO TÉCNICO]
     * @param array<int, array{opcion_id: string, peso: float, unidad_id: string}> $votos Registros de sufragios.
     * @return array<string, array{conteo: int, peso_acumulado: float, porcentaje: float}> Resultados agregados por opción.
     */
    public function tally(array $votos): array;
}
