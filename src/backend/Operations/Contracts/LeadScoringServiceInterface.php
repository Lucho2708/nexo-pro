<?php

namespace NexoPro\Operations\Contracts;

use NexoPro\Operations\DTOs\LeadCalculationDTO;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Define el contrato inmutable para la calificación algorítmica y proyección de ROI de copropiedades.
 * Calcula el impacto financiero (cartera recuperada y horas ahorradas) para presentar una propuesta irresistible.
 *
 * [ASPECTO TÉCNICO / UI]
 * Interfaz segregada (ISP) bajo principios SOLID. Facilita la inyección de dependencias (DIP)
 * y desacopla la lógica financiera y de scoring del framework HTTP o de la persistencia ORM.
 */
interface LeadScoringServiceInterface
{
    /**
     * Procesa la simulación de unidades y cuotas para producir el DTO integral de calificación.
     */
    public function calculateAndScore(int $unidades, float $cuotaPromedioMensual, string $rol = 'administrador'): LeadCalculationDTO;

    /**
     * Determina el segmento comercial según el volumen de unidades inmobiliarias.
     * Retorna: 'enterprise' (>= 300), 'standard' (100 - 299) o 'boutique' (< 100).
     */
    public function determineSegment(int $unidades): string;

    /**
     * Calcula el score comercial en escala de 0 a 100 basado en unidades y nivel de decisión del rol.
     */
    public function computeScore(int $unidades, string $rol): int;
}
