<?php

namespace App\Modules\Property\Database\Factories;

use App\Modules\Property\Models\Copropiedad;
use Illuminate\Database\Eloquent\Factories\Factory;

class CopropiedadFactory extends Factory
{
    protected $model = Copropiedad::class;

    public function definition(): array
    {
        return [
            'nit' => rand(1000000, 9999999) . '-1',
            'nombre' => 'Residencial ' . rand(1, 100),
            'direccion' => 'Calle Falsa 123',
            'ciudad' => 'Bogotá',
            'plan' => 'pro',
            'license_status' => 'active',
            'license_expires_at' => now()->addYear(),
            'settings' => Copropiedad::defaultSettings(),
            'unidades_totales' => 0,
            'torres' => 0,
        ];
    }
}
