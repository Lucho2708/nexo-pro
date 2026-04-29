<?php

namespace Database\Factories;

use App\Models\ZonaComun;
use App\Models\Copropiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZonaComunFactory extends Factory
{
    protected $model = ZonaComun::class;

    public function definition(): array
    {
        return [
            'copropiedad_id' => Copropiedad::factory(),
            'nombre' => $this->faker->randomElement(['Salón Social', 'Piscina', 'Gimnasio', 'Cancha de Squash', 'BBQ']),
            'descripcion' => $this->faker->sentence(),
            'capacidad_maxima' => $this->faker->numberBetween(5, 50),
            'costo' => $this->faker->randomElement([0, 20000, 50000, 100000]),
            'activa' => true,
            'settings' => json_encode([
                'horario_inicio' => '08:00',
                'horario_fin' => '22:00',
                'duracion_maxima' => 4
            ]),
        ];
    }
}
