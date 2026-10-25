<?php

declare(strict_types=1);

namespace NexoPro\Assembly\Strategies;

use NexoPro\Assembly\Contracts\VotingTallyStrategyInterface;

/**
 * NominalVotingStrategy
 *
 * [LÓGICA DE NEGOCIO]
 * Estrategia de votación democrática paritaria: cada inmueble participante equivale estrictamente
 * a un (1) voto, con independencia de su área en metros cuadrados o de su coeficiente económico.
 * Se aplica típicamente en comités de convivencia, consultas no vinculantes o temas de convivencia vecinal.
 *
 * [ASPECTO TÉCNICO]
 * Implementa VotingTallyStrategyInterface.
 * Complejidad algorítmica: O(N) lineal estricta. Operaciones de incremento de enteros de bajo consumo
 * de CPU y memoria O(1) adicional.
 */
final class NominalVotingStrategy implements VotingTallyStrategyInterface
{
    public function getIdentifier(): string
    {
        return 'nominal';
    }

    /**
     * [LÓGICA DE NEGOCIO]
     * Cuenta cada sufragio con valor unitario absoluto (1.0).
     *
     * [ASPECTO TÉCNICO]
     * Agregación matemática O(N) sin ponderaciones de coeficientes.
     */
    public function tally(array $votos): array
    {
        $resultados = [];
        $totalVotos = count($votos);

        foreach ($votos as $voto) {
            $opcionId = (string) $voto['opcion_id'];

            if (!isset($resultados[$opcionId])) {
                $resultados[$opcionId] = [
                    'conteo' => 0,
                    'peso_acumulado' => 0.0,
                    'porcentaje' => 0.0,
                ];
            }

            $resultados[$opcionId]['conteo']++;
            $resultados[$opcionId]['peso_acumulado'] += 1.0;
        }

        if ($totalVotos > 0) {
            foreach ($resultados as $opcionId => &$res) {
                $res['porcentaje'] = round(($res['conteo'] / $totalVotos) * 100, 2);
            }
        }

        return $resultados;
    }
}
