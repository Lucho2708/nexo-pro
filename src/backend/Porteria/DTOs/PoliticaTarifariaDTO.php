<?php

namespace NexoPro\Porteria\DTOs;

use InvalidArgumentException;

/**
 * [DTO / SOLID - SRP]
 * Encapsula la política tarifaria y reglas de negocio para liquidación de parqueaderos.
 */
readonly class PoliticaTarifariaDTO
{
    public function __construct(
        public int $horasCortesia = 2,
        public float $tarifaPorHora = 2500.0,
        public float $tarifaDiaMax = 35000.0
    ) {
        if ($this->horasCortesia < 0) {
            throw new InvalidArgumentException('Las horas de cortesía no pueden ser negativas.');
        }

        if ($this->tarifaPorHora < 0) {
            throw new InvalidArgumentException('La tarifa por hora no puede ser negativa.');
        }

        if ($this->tarifaDiaMax < 0) {
            throw new InvalidArgumentException('El tope máximo diario no puede ser negativo.');
        }
    }

    public static function fromSettings(?array $settings): self
    {
        $tarifa = $settings['tarifa_parqueadero'] ?? [];

        return new self(
            horasCortesia: (int) ($tarifa['horas_cortesia'] ?? 2),
            tarifaPorHora: (float) ($tarifa['tarifa_hora'] ?? 2500.0),
            tarifaDiaMax: (float) ($tarifa['tarifa_dia_max'] ?? 35000.0)
        );
    }
}
