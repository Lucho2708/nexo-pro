<?php

declare(strict_types=1);

namespace NexoPro\Property\ValueObjects;

use InvalidArgumentException;

/**
 * AreaSquareMeters
 *
 * [LÓGICA DE NEGOCIO]
 * Objeto de valor que encapsula la medida del área construida o privada de un inmueble en metros cuadrados (m²).
 * Base fundamental para el cálculo del coeficiente de copropiedad según el Artículo 26 de la Ley 675.
 * Impide áreas negativas o nulas que viciarían de nulidad el reparto porcentual de expensas comunes.
 *
 * [ASPECTO TÉCNICO]
 * Value Object inmutable. Autovalida sus invariantes en el constructor.
 * Utiliza precisión de 4 decimales para compatibilidad con planos arquitectónicos y licencias de construcción.
 */
final readonly class AreaSquareMeters
{
    public float $value;

    public function __construct(float $value)
    {
        if ($value <= 0.0) {
            throw new InvalidArgumentException("El área construida debe ser estrictamente mayor a cero. Valor provisto: {$value}");
        }

        $this->value = round($value, 4);
    }

    public static function fromFloat(float $value): self
    {
        return new self($value);
    }

    public function add(self $other): self
    {
        return new self($this->value + $other->value);
    }

    public function equals(self $other): bool
    {
        return abs($this->value - $other->value) < 0.0001;
    }

    public function __toString(): string
    {
        return number_format($this->value, 4, '.', '') . ' m²';
    }
}
