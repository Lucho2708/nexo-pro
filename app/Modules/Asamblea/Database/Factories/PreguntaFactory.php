<?php

namespace App\Modules\Asamblea\Database\Factories;

use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Asamblea\Models\Pregunta;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreguntaFactory extends Factory
{
    protected $model = Pregunta::class;

    public function definition(): array
    {
        return [
            'asamblea_id' => Asamblea::factory(),
            'titulo' => $this->faker->sentence . '?',
            'descripcion' => $this->faker->paragraph,
            'tipo' => 'simple',
            'status' => 'draft',
        ];
    }
}
