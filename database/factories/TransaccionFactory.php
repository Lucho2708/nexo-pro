<?php

namespace Database\Factories;

use App\Modules\Finance\Models\Transaccion;
use App\Modules\Property\Models\Unidad;
use App\Modules\Finance\Models\ConceptoCobro;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransaccionFactory extends Factory
{
    protected $model = Transaccion::class;

    public function definition(): array
    {
        return [
            'unidad_id' => Unidad::factory(),
            'concepto_id' => ConceptoCobro::factory(),
            'tipo' => $this->faker->randomElement(['cargo', 'abono']),
            'monto' => $this->faker->randomFloat(2, 50000, 500000),
            'fecha' => $this->faker->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'referencia' => $this->faker->bothify('REF-####-????'),
        ];
    }
}
