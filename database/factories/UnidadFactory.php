<?php

namespace Database\Factories;

use App\Models\Unidad;
use App\Models\Copropiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnidadFactory extends Factory
{
    protected $model = Unidad::class;

    public function definition(): array
    {
        return [
            'copropiedad_id' => Copropiedad::factory(),
            'nombre' => $this->faker->numerify('###'),
            'torre' => $this->faker->randomElement(['Torre A', 'Torre B', 'Torre C']),
            'piso' => $this->faker->numberBetween(1, 20),
            'coeficiente' => 1.5,
            'propietario_nombre' => $this->faker->name(),
            'propietario_identificacion' => $this->faker->numerify('##########'),
            'email_contacto' => $this->faker->unique()->safeEmail(),
            'saldo_actual' => 0,
        ];
    }
}
