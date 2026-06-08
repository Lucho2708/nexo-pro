<?php

namespace App\Modules\Asamblea\Database\Factories;

use App\Modules\Asamblea\Models\Pregunta;
use App\Modules\Asamblea\Models\Opcion;
use Illuminate\Database\Eloquent\Factories\Factory;

class OpcionFactory extends Factory
{
    protected $model = Opcion::class;

    public function definition(): array
    {
        return [
            'pregunta_id' => Pregunta::factory(),
            'titulo' => $this->faker->word,
            'order' => 0,
        ];
    }
}
