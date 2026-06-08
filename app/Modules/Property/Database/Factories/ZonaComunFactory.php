<?php

namespace App\Modules\Property\Database\Factories;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\ZonaComun;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZonaComunFactory extends Factory
{
    protected $model = ZonaComun::class;

    public function definition(): array
    {
        return [
            'copropiedad_id' => Copropiedad::factory(),
            'nombre' => $this->faker->word,
            'descripcion' => $this->faker->sentence,
            'capacidad_maxima' => $this->faker->numberBetween(10, 100),
            'costo' => $this->faker->randomFloat(2, 0, 50000),
            'activa' => true,
        ];
    }
}
