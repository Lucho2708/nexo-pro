<?php

declare(strict_types=1);

namespace NexoPro\Assembly\Strategies;

use NexoPro\Assembly\Contracts\VotingTallyStrategyInterface;

/**
 * CoefficientVotingStrategy
 *
 * [LÓGICA DE NEGOCIO]
 * Estrategia de votación estándar obligatoria para decisiones económicas y patrimoniales en asambleas
 * de propiedad horizontal (aprobación de presupuestos, cuotas extraordinarias, reformas estatutarias).
 * El peso de cada sufragio es directamente proporcional al coeficiente de copropiedad asignado
 * en el Reglamento de Propiedad Horizontal (RPH) y escrituras públicas registradas.
 *
 * [ASPECTO TÉCNICO]
 * Implementación de VotingTallyStrategyInterface.
 * Complejidad algorítmica: O(N) en un único pase de agregación lineal sobre el array de votos.
 * Manejo de memoria: Acumulación en variables de precisión flotante round(X, 5) sin crear arrays
 * intermedios innecesarios.
 */
final class CoefficientVotingStrategy implements VotingTallyStrategyInterface
{
    public function getIdentifier(): string
    {
        return 'coeficiente';
    }

    /**
     * [LÓGICA DE NEGOCIO]
     * Suma los coeficientes porcentuales para cada opción y calcula su peso respecto al total votante.
     *
     * [ASPECTO TÉCNICO]
     * Complejidad computacional: O(N) donde N es el número total de votos. Espacio O(K) donde K
     * es el número de opciones de respuesta de la pregunta (K normalmente <= 5).
     */
    public function tally(array $votos): array
    {
        $resultados = [];
        $pesoTotalVotante = 0.0;

        // 1. Acumulación por opción en O(N)
        foreach ($votos as $voto) {
            $opcionId = (string) $voto['opcion_id'];
            $peso = (float) ($voto['peso'] ?? 0.0);

            if (!isset($resultados[$opcionId])) {
                $resultados[$opcionId] = [
                    'conteo' => 0,
                    'peso_acumulado' => 0.0,
                    'porcentaje' => 0.0,
                ];
            }

            $resultados[$opcionId]['conteo']++;
            $resultados[$opcionId]['peso_acumulado'] = round($resultados[$opcionId]['peso_acumulado'] + $peso, 5);
            $pesoTotalVotante += $peso;
        }

        // 2. Cálculo de porcentajes relativos
        if ($pesoTotalVotante > 0) {
            foreach ($resultados as $opcionId => &$res) {
                $res['porcentaje'] = round(($res['peso_acumulado'] / $pesoTotalVotante) * 100, 4);
            }
        }

        return $resultados;
    }
}
