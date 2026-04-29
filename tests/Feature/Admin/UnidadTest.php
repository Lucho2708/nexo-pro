<?php

use App\Models\Copropiedad;
use App\Models\User;
use App\Models\Unidad;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::create([
        'name' => 'Admin Test',
        'email' => 'admin@test.com',
        'password' => bcrypt('password'),
        'role' => 'admin',
    ]);

    $this->copropiedadA = Copropiedad::create([
        'nit' => '900-1',
        'nombre' => 'Copropiedad A',
        'direccion' => 'Calle 1',
        'ciudad' => 'Medellin',
    ]);

    $this->admin->managedCopropiedades()->attach($this->copropiedadA->id);
    $this->admin->update(['current_copropiedad_id' => $this->copropiedadA->id]);
});

it('can bulk generate unidades for a torre', function () {
    $data = [
        'torre' => 'Torre A',
        'pisos' => 5,
        'aptos_por_piso' => 4,
        'default_coeficiente' => 1.5,
    ];

    $response = $this->actingAs($this->admin)->post(route('unidades.bulk-generate'), $data);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    // It should create 5 * 4 = 20 unidades
    $this->assertDatabaseCount('unidades', 20);

    // Verify format Piso + Apto (e.g. 101, 102, 504)
    $this->assertDatabaseHas('unidades', [
        'copropiedad_id' => $this->copropiedadA->id,
        'torre' => 'Torre A',
        'nombre' => '101',
        'piso' => 1,
        'coeficiente' => 1.5,
    ]);

    $this->assertDatabaseHas('unidades', [
        'copropiedad_id' => $this->copropiedadA->id,
        'torre' => 'Torre A',
        'nombre' => '504',
        'piso' => 5,
        'coeficiente' => 1.5,
    ]);
});

it('validates custom_settings is a valid json if provided', function () {
    $data = [
        'torre' => 'Torre B',
        'pisos' => 1,
        'aptos_por_piso' => 1,
        'default_coeficiente' => 1.0,
        'custom_settings' => 'invalid-json',
    ];

    $response = $this->actingAs($this->admin)->post(route('unidades.bulk-generate'), $data);

    $response->assertSessionHasErrors('custom_settings');
    $this->assertDatabaseCount('unidades', 0);
});

it('fails to bulk generate if the user has no current_copropiedad_id', function () {
    $this->admin->update(['current_copropiedad_id' => null]);

    $data = [
        'torre' => 'Torre A',
        'pisos' => 5,
        'aptos_por_piso' => 4,
        'default_coeficiente' => 1.5,
    ];

    $response = $this->actingAs($this->admin)->post(route('unidades.bulk-generate'), $data);

    $response->assertStatus(403);
});
