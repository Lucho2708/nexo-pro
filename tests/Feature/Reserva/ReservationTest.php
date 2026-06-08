<?php

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Operations\Models\Reserva;
use App\Modules\Property\Models\Unidad;
use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\ZonaComun;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->copropiedad = Copropiedad::factory()->create();
    $this->user = User::factory()->create([
        'current_copropiedad_id' => $this->copropiedad->id,
        'role' => 'owner',
    ]);
    $this->unidad = Unidad::factory()->create([
        'copropiedad_id' => $this->copropiedad->id,
    ]);
    $this->user->unidades()->attach($this->unidad->id, ['role' => 'owner']);
    
    $this->zona = ZonaComun::factory()->create([
        'copropiedad_id' => $this->copropiedad->id,
        'activa' => true,
    ]);
});

test('a user can create a reservation', function () {
    $data = [
        'zona_id' => $this->zona->id,
        'unidad_id' => $this->unidad->id,
        'fecha' => now()->addDay()->toDateString(),
        'hora_inicio' => '10:00:00',
        'hora_fin' => '12:00:00',
        'cantidad_personas' => 5,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('reservas.store'), $data);

    $response->assertStatus(302);
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('operations.reservas', [
        'user_id' => $this->user->id,
        'zona_id' => $this->zona->id,
        'fecha' => \Illuminate\Support\Carbon::parse($data['fecha']),
        'hora_inicio' => $data['hora_inicio'],
        'hora_fin' => $data['hora_fin'],
    ]);
});

test('it prevents overlapping reservations', function () {
    Reserva::factory()->create([
        'zona_id' => $this->zona->id,
        'user_id' => $this->user->id,
        'unidad_id' => $this->unidad->id,
        'fecha' => now()->addDay()->toDateString(),
        'hora_inicio' => '10:00:00',
        'hora_fin' => '12:00:00',
        'copropiedad_id' => $this->copropiedad->id,
        'estado' => 'aprobada',
    ]);

    $data = [
        'zona_id' => $this->zona->id,
        'unidad_id' => $this->unidad->id,
        'fecha' => now()->addDay()->toDateString(),
        'hora_inicio' => '11:00:00',
        'hora_fin' => '13:00:00',
        'cantidad_personas' => 2,
    ];

    $response = $this->actingAs($this->user)
        ->from(route('reservas.index'))
        ->post(route('reservas.store'), $data);

    $response->assertStatus(302);
    $response->assertSessionHas('error', 'El horario seleccionado ya se encuentra ocupado.');
    
    $this->assertDatabaseCount('operations.reservas', 1);
});

test('it allows back-to-back reservations if handled correctly', function () {
    Reserva::factory()->create([
        'zona_id' => $this->zona->id,
        'user_id' => $this->user->id,
        'unidad_id' => $this->unidad->id,
        'fecha' => now()->addDay()->toDateString(),
        'hora_inicio' => '10:00:00',
        'hora_fin' => '11:00:00',
        'copropiedad_id' => $this->copropiedad->id,
        'estado' => 'aprobada',
    ]);

    $data = [
        'zona_id' => $this->zona->id,
        'unidad_id' => $this->unidad->id,
        'fecha' => now()->addDay()->toDateString(),
        'hora_inicio' => '11:00:00',
        'hora_fin' => '12:00:00',
        'cantidad_personas' => 2,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('reservas.store'), $data);

    // This might fail depending on current implementation of checkOverlap
    $response->assertStatus(302);
    $response->assertSessionHas('success');
    
    $this->assertDatabaseCount('operations.reservas', 2);
});

test('it prevents reservation if unit is in debt and zone settings block it', function () {
    $this->unidad->update(['saldo_actual' => 100.00]);
    $this->zona->update(['settings' => ['bloquear_si_mora' => true]]);

    $data = [
        'zona_id' => $this->zona->id,
        'unidad_id' => $this->unidad->id,
        'fecha' => now()->addDays(5)->toDateString(),
        'hora_inicio' => '10:00:00',
        'hora_fin' => '12:00:00',
        'cantidad_personas' => 2,
    ];

    $response = $this->actingAs($this->user)
        ->from(route('reservas.index'))
        ->post(route('reservas.store'), $data);

    $response->assertStatus(302);
    $response->assertSessionHas('error', 'La unidad presenta saldos pendientes. No es posible realizar reservas.');
    
    $this->assertDatabaseCount('operations.reservas', 0);
});

test('it allows reservation if unit is in debt but zone settings allow it', function () {
    $this->unidad->update(['saldo_actual' => 100.00]);
    $this->zona->update(['settings' => ['bloquear_si_mora' => false]]);

    $data = [
        'zona_id' => $this->zona->id,
        'unidad_id' => $this->unidad->id,
        'fecha' => now()->addDays(5)->toDateString(),
        'hora_inicio' => '10:00:00',
        'hora_fin' => '12:00:00',
        'cantidad_personas' => 2,
    ];

    $response = $this->actingAs($this->user)
        ->post(route('reservas.store'), $data);

    $response->assertStatus(302);
    $response->assertSessionHas('success');
    
    $this->assertDatabaseCount('operations.reservas', 1);
});

test('it enforces monthly reservation limit', function () {
    $this->zona->update(['settings' => ['max_reservas_mes' => 1]]);
    
    // Create one reservation for this month
    Reserva::factory()->create([
        'zona_id' => $this->zona->id,
        'user_id' => $this->user->id,
        'unidad_id' => $this->unidad->id,
        'fecha' => now()->addDay()->toDateString(),
        'estado' => 'aprobada',
        'copropiedad_id' => $this->copropiedad->id,
    ]);

    $data = [
        'zona_id' => $this->zona->id,
        'unidad_id' => $this->unidad->id,
        'fecha' => now()->addDay()->toDateString(), // Mismo día/mes
        'hora_inicio' => '14:00:00',
        'hora_fin' => '16:00:00',
        'cantidad_personas' => 2,
    ];

    $response = $this->actingAs($this->user)
        ->from(route('reservas.index'))
        ->post(route('reservas.store'), $data);

    $response->assertStatus(302);
    $response->assertSessionHas('error', 'Se ha alcanzado el límite máximo de 1 reservas mensuales para esta zona.');
    
    $this->assertDatabaseCount('operations.reservas', 1);
});

test('it enforces minimum anticipation rules', function () {
    $this->zona->update(['settings' => ['min_dias_anticipacion' => 3]]);

    $data = [
        'zona_id' => $this->zona->id,
        'unidad_id' => $this->unidad->id,
        'fecha' => now()->addDays(2)->toDateString(), // Less than 3 days
        'hora_inicio' => '10:00:00',
        'hora_fin' => '12:00:00',
        'cantidad_personas' => 2,
    ];

    $response = $this->actingAs($this->user)
        ->from(route('reservas.index'))
        ->post(route('reservas.store'), $data);

    $response->assertStatus(302);
    $response->assertSessionHas('error', 'La reserva debe realizarse con al menos 3 días de anticipación.');
    
    $this->assertDatabaseCount('operations.reservas', 0);
});
