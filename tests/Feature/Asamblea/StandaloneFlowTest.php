<?php

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('un administrador puede registrarse en modo standalone y cargar votantes con coeficientes manuales', function () {
    $this->withoutExceptionHandling();
    // 1. Registro Standalone
    $registrationData = [
        'nit' => '900123456-1',
        'nombre_copropiedad' => 'Conjunto Standalone Test',
        'direccion' => 'Calle 1 # 2-3',
        'ciudad' => 'Bogotá',
        'plan' => 'standalone',
        'unidades_totales' => 10,
        'torres' => 1,
        'name' => 'Admin Standalone',
        'email' => 'admin@standalone.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
        'terms' => true,
        'is_standalone' => true
    ];

    $response = $this->post('/register', $registrationData);

    $response->assertRedirect('/dashboard');
    
    $user = User::where('email', 'admin@standalone.com')->first();
    $copropiedad = $user->currentCopropiedad;

    expect($user->is_standalone)->toBeTrue();
    expect($copropiedad->settings['is_standalone'])->toBeTrue();

    // 2. Importación de Votantes
    $voters = [
        ['torre' => 'A', 'unidad' => '101', 'nombre' => 'Juan Perez', 'documento' => '12345', 'email' => 'juan@test.com', 'coeficiente' => 12.5000],
        ['torre' => 'A', 'unidad' => '102', 'nombre' => 'Maria Lopez', 'documento' => '67890', 'email' => 'maria@test.com', 'coeficiente' => 87.5000],
    ];

    $response = $this->actingAs($user)->post(route('admin.asambleas.voters.import'), [
        'voters' => $voters
    ]);

    $response->assertStatus(302); // Redirect back

    // 3. Verificaciones
    $u1 = Unidad::where('nombre', '101')->first();
    $u2 = Unidad::where('nombre', '102')->first();

    expect((float) $u1->coeficiente)->toBe(12.5000);
    expect((float) $u2->coeficiente)->toBe(87.5000);
    
    // La suma debe ser 100%
    $total = Unidad::where('copropiedad_id', $copropiedad->id)->sum('coeficiente');
    expect((float) $total)->toBe(100.0000);

    // 4. Verificar que el Owner creado puede entrar con su documento
    $owner = User::where('email', 'juan@test.com')->first();
    expect($owner)->not->toBeNull();
    
    $loginResponse = $this->post('/login', [
        'email' => 'juan@test.com',
        'password' => '12345' // Su documento
    ]);
    
    $loginResponse->assertRedirect();
});
