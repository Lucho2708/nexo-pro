<?php

namespace Database\Factories;

use App\Models\Asamblea;
use App\Models\Copropiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsambleaFactory extends Factory
{
    protected $model = Asamblea::class;

    public function definition(): array
    {
        return [
            'copropiedad_id' => Copropiedad::factory(),
            'titulo' => 'Asamblea Ordinaria ' . now()->year,
            'fecha' => now()->addDays(7),
            'status' => 'scheduled',
            'settings' => [],
        ];
    }
}
