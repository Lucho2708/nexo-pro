<?php

namespace App\Modules\Property\Database\Factories;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\TipoUnidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class TipoUnidadFactory extends Factory
{
    protected $model = TipoUnidad::class;

    public function definition(): array
    {
        return [
            'copropiedad_id' => Copropiedad::factory(),
            'nombre' => 'Apto Tipo ' . $this->faker->lexify('?'),
            'area_m2' => $this->faker->randomFloat(2, 40, 150),
        ];
    }
}
