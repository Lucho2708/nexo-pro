<?php

declare(strict_types=1);

namespace NexoPro\Property\ValueObjects;

use InvalidArgumentException;

/**
 * CoefficientPercentage
 *
 * [LÓGICA DE NEGOCIO]
 * Objeto de valor inmutable que representa el coeficiente de copropiedad de una unidad privada.
 * Define la participación en las decisiones de la asamblea y la cuota parte de contribución en expensas.
 * El valor porcentual debe oscilar en el rango legal (0, 100].
 *
 * [ASPECTO TÉCNICO]
 * Inmutable con validación de rango y redondeo estandarizado a 5 decimales (DECIMAL(10,5) en PostgreSQL).
 * Previene errores de truncamiento binario de coma flotante.
 */
final readonly class CoefficientPercentage
{
    public float $value;

    public function __construct(float $value)
    {
        if ($value <= 0.0 || $value > 100.0) {
            throw new InvalidArgumentException("El coeficiente de copropiedad debe estar en el rango (0, 100]. Provisto: {$value}");
        }

        $this->value = round($value, 5);
    }

    public static function fromFloat(float $value): self
    {
        return new self($value);
    }

    public function add(float $delta): self
    {
        return new self($this->value + $delta);
    }

    public function equals(self $other): bool
    {
        return abs($this->value - $other->value) < 0.00001;
    }

    public function __toString(): string
    {
        return number_format($this->value, 5, '.', '') . '%';
    }
}
