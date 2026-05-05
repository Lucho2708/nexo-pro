<?php

namespace Database\Factories;

use App\Models\Reserva;
use App\Models\ZonaComun;
use App\Modules\IAM\Models\User;
use App\Models\Unidad;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservaFactory extends Factory
{
    protected $model = Reserva::class;

    public function definition(): array
    {
        return [
            'zona_id' => ZonaComun::factory(),
            'user_id' => User::factory(),
            'unidad_id' => Unidad::factory(),
            'fecha' => $this->faker->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'hora_inicio' => '10:00:00',
            'hora_fin' => '14:00:00',
            'cantidad_personas' => $this->faker->numberBetween(1, 10),
            'monto_pagado' => 0,
            'estado' => $this->faker->randomElement(['pendiente', 'aprobada', 'rechazada', 'cancelada', 'pagada']),
        ];
    }
}
