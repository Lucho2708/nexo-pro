<?php

namespace Database\Factories;

use App\Models\Asamblea;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreguntaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'asamblea_id' => Asamblea::factory(),
            'titulo' => $this->faker->sentence . '?',
            'description' => $this->faker->paragraph,
            'status' => 'opened',
        ];
    }
}
