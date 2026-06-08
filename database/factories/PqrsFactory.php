<?php

namespace Database\Factories;

use App\Modules\Operations\Models\Pqrs;
use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Unidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class PqrsFactory extends Factory
{
    protected $model = Pqrs::class;

    public function definition(): array
    {
        return [
            'unidad_id' => Unidad::factory(),
            'user_id' => User::factory(),
            'tipo' => $this->faker->randomElement(['peticion', 'queja', 'reclamo', 'sugerencia']),
            'asunto' => $this->faker->sentence(),
            'mensaje' => $this->faker->paragraph(),
            'prioridad' => 'media',
            'estado' => 'abierto',
        ];
    }
}
