<?php

namespace Database\Factories;

use App\Models\Pregunta;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpcionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pregunta_id' => Pregunta::factory(),
            'titulo' => $this->faker->word,
            'votos_count' => 0,
        ];
    }
}
