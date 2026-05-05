<?php

use App\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use App\Models\ZonaComun;
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

it('can list zonas comunes', function () {
    ZonaComun::create([
        'copropiedad_id' => $this->copropiedadA->id,
        'nombre' => 'Piscina',
        'capacidad_maxima' => 20,
        'costo' => 50000,
        'activa' => true,
    ]);

    $response = $this->actingAs($this->admin)->get(route('admin.zonas.index'));

    $response->assertOk();
    // Assuming Inertia response, we just check 200 OK.
});

it('can create a zona comun', function () {
    $data = [
        'nombre' => 'Salon Social',
        'descripcion' => 'Salon principal',
        'capacidad_maxima' => 50,
        'costo' => 100000,
    ];

    $response = $this->actingAs($this->admin)->post(route('admin.zonas.store'), $data);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('zonas_comunes', [
        'copropiedad_id' => $this->copropiedadA->id,
        'nombre' => 'Salon Social',
        'capacidad_maxima' => 50,
        'costo' => 100000,
        'activa' => 1,
    ]);
});

it('can update a zona comun', function () {
    $zona = ZonaComun::create([
        'copropiedad_id' => $this->copropiedadA->id,
        'nombre' => 'Piscina',
        'capacidad_maxima' => 20,
        'costo' => 50000,
        'activa' => true,
    ]);

    $data = [
        'nombre' => 'Piscina Climatizada',
        'descripcion' => 'Piscina renovada',
        'capacidad_maxima' => 25,
        'costo' => 60000,
    ];

    $response = $this->actingAs($this->admin)->patch(route('admin.zonas.update', $zona->id), $data);

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('zonas_comunes', [
        'id' => $zona->id,
        'nombre' => 'Piscina Climatizada',
        'capacidad_maxima' => 25,
        'costo' => 60000,
    ]);
});

it('can toggle zona comun status', function () {
    $zona = ZonaComun::create([
        'copropiedad_id' => $this->copropiedadA->id,
        'nombre' => 'Piscina',
        'capacidad_maxima' => 20,
        'costo' => 50000,
        'activa' => true,
    ]);

    $response = $this->actingAs($this->admin)->patch(route('admin.zonas.toggle', $zona->id));

    $response->assertRedirect();
    $response->assertSessionHas('success');

    $this->assertDatabaseHas('zonas_comunes', [
        'id' => $zona->id,
        'activa' => 0,
    ]);

    $response = $this->actingAs($this->admin)->patch(route('admin.zonas.toggle', $zona->id));

    $this->assertDatabaseHas('zonas_comunes', [
        'id' => $zona->id,
        'activa' => 1,
    ]);
});
