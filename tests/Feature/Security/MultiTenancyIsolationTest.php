<?php

namespace Tests\Feature\Security;

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\Operations\Models\Pqrs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Escenario: Dos copropiedades totalmente separadas
    $this->copropiedadA = Copropiedad::factory()->create(['nombre' => 'Conjunto Residencial A']);
    $this->copropiedadB = Copropiedad::factory()->create(['nombre' => 'Conjunto Residencial B']);

    // Usuarios para cada copropiedad
    $this->adminA = User::factory()->create([
        'role' => 'admin',
        'current_copropiedad_id' => $this->copropiedadA->id
    ]);
    $this->adminA->managedCopropiedades()->attach($this->copropiedadA);

    $this->adminB = User::factory()->create([
        'role' => 'admin',
        'current_copropiedad_id' => $this->copropiedadB->id
    ]);
    $this->adminB->managedCopropiedades()->attach($this->copropiedadB);

    // Datos privados de la Copropiedad B (la víctima del intento de acceso)
    $this->unidadPrivadaB = Unidad::factory()->create([
        'copropiedad_id' => $this->copropiedadB->id,
        'nombre' => 'Apartamento Secreto B'
    ]);

    $this->pqrsPrivadaB = Pqrs::factory()->create([
        'copropiedad_id' => $this->copropiedadB->id,
        'asunto' => 'Queja Confidencial B'
    ]);
});

test('un administrador NO puede ver unidades de otra copropiedad en los listados', function () {
    $this->actingAs($this->adminA);

    // Intentar listar unidades (debería estar filtrado por TenantScope)
    $unidadesVisibles = Unidad::all();

    expect($unidadesVisibles)->toHaveCount(0)
        ->and($unidadesVisibles->pluck('id'))->not->toContain($this->unidadPrivadaB->id);
});

test('un administrador NO puede acceder directamente a un recurso de otra copropiedad (Prevención IDOR)', function () {
    $this->actingAs($this->adminA);

    $response = $this->get(route('cartera.statement.download', $this->unidadPrivadaB->id));

    // El TenantScope hace que el modelo no se encuentre (404), o la Policy lo bloquea (403)
    // Ambas son respuestas seguras.
    expect($response->status())->toBeIn([403, 404]);
});

test('un administrador NO puede modificar recursos de otra copropiedad', function () {
    $this->actingAs($this->adminA);

    $response = $this->patch(route('pqrs.update', $this->pqrsPrivadaB->id), [
        'estado' => 'cerrado'
    ]);

    expect($response->status())->toBeIn([403, 404]);
    
    // Verificar que el dato en DB no cambió
    $this->pqrsPrivadaB->refresh();
    expect($this->pqrsPrivadaB->estado)->not->toBe('cerrado');
});

test('un Super Admin SI puede acceder a recursos de cualquier copropiedad', function () {
    $superAdmin = User::factory()->create([
        'role' => 'super_admin',
        'current_copropiedad_id' => $this->copropiedadA->id
    ]);
    $this->actingAs($superAdmin);

    // El Super Admin no está sujeto al TenantScope en las políticas globales
    $response = $this->get(route('pqrs.index'));
    $response->assertStatus(200);
});

test('el cambio de tenant (switch) valida rigurosamente la pertenencia', function () {
    $this->actingAs($this->adminA);

    // El adminA intenta cambiarse a la copropiedad B (donde no tiene permiso)
    $response = $this->post(route('tenant.switch', $this->copropiedadB->id));

    $response->assertStatus(403);
    
    $this->adminA->refresh();
    expect($this->adminA->current_copropiedad_id)->toBe($this->copropiedadA->id);
});

test('un usuario NO autenticado es redirigido al login al intentar acceder a datos protegidos', function () {
    Auth::logout();

    $response = $this->get(route('pqrs.index'));
    
    $response->assertRedirect(route('login'));
});
