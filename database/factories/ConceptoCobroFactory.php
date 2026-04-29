<?php

namespace Database\Factories;

use App\Models\ConceptoCobro;
use App\Models\Copropiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConceptoCobroFactory extends Factory
{
    protected $model = ConceptoCobro::class;

    public function definition(): array
    {
        return [
            'copropiedad_id' => Copropiedad::factory(),
            'nombre' => $this->faker->word(),
            'codigo' => $this->faker->unique()->lexify('CON-???'),
            'valor_fijo' => $this->faker->numberBetween(10000, 200000),
            'es_obligatorio' => true,
        ];
    }
}
