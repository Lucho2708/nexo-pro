<?php

use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
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

it('an authenticated admin can create a second copropiedad', function () {
    $data = [
        'nit' => '900-2',
        'nombre' => 'Copropiedad B',
        'direccion' => 'Calle 2',
        'ciudad' => 'Bogota',
        'plan' => 'pro',
        'unidades_totales' => 50,
        'torres' => 2,
    ];

    $response = $this->actingAs($this->admin)
        ->post(route('admin.copropiedades.store'), $data);

    $response->assertRedirect();
    
    $this->assertDatabaseHas('property.copropiedades', [
        'nit' => '900-2',
        'nombre' => 'Copropiedad B',
    ]);

    // Verificar que el admin ahora tiene 2 conjuntos asociados
    $this->assertEquals(2, $this->admin->fresh()->managedCopropiedades()->count());
    
    // Verificar que el contexto cambió al nuevo conjunto automáticamente
    $newCopropiedad = Copropiedad::where('nit', '900-2')->first();
    $this->assertEquals($newCopropiedad->id, $this->admin->fresh()->current_copropiedad_id);
});
