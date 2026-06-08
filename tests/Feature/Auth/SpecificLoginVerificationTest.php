<?php

use App\Modules\IAM\Models\User;
use Illuminate\Support\Facades\Auth;

test('verificar login especifico para propietario302_0_2@nexo.pro', function () {
    $email = 'propietario302_0_2@nexo.pro';
    $password = 'password';

    // 1. Crear al usuario manualmente en el entorno del test
    $user = User::factory()->create([
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'owner',
    ]);

    // 2. Intentar el login vía POST
    $response = $this->post('/login', [
        'email' => $email,
        'password' => $password,
    ]);

    // 3. Verificar estado y redirección
    // Los propietarios van a 'owner.dashboard'
    $response->assertStatus(302);
    $this->assertAuthenticatedAs($user);
    
    echo "\n✅ Login verificado para el nuevo módulo IAM.\n";
});
