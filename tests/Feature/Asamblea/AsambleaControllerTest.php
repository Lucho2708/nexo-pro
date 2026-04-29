<?php

use App\Models\Copropiedad;
use App\Models\User;
use App\Models\Unidad;
use App\Models\Asamblea;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    config([
        'services.livekit.key' => 'test_key_1234567890_test_key_123', 
        'services.livekit.secret' => 'test_secret_1234567890_test_secret_123',
        'services.livekit.url' => 'ws://localhost:7880'
    ]);
});

test('a resident can access their assembly room if everything is valid', function () {
    // 1. Setup
    $copropiedad = Copropiedad::factory()->create(['settings' => ['asamblea_virtual_enabled' => true]]);
    $user = User::factory()->create(['current_copropiedad_id' => $copropiedad->id, 'role' => 'owner']);
    $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);
    $user->unidades()->attach($unidad, ['role' => 'owner']);

    $asamblea = Asamblea::factory()->create([
        'copropiedad_id' => $copropiedad->id,
        'status' => 'in_progress'
    ]);

    $this->actingAs($user);

    // 2. Act
    $response = $this->get(route('asambleas.show', $asamblea));

    // 3. Assert
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('Asamblea/Show')
        ->has('token')
        ->has('asamblea')
    );
});

test('a resident cannot access if another device is connected for the same unit', function () {
    $copropiedad = Copropiedad::factory()->create(['settings' => ['asamblea_virtual_enabled' => true]]);
    $user = User::factory()->create(['current_copropiedad_id' => $copropiedad->id, 'role' => 'owner']);
    $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);
    $user->unidades()->attach($unidad, ['role' => 'owner']);
    
    $asamblea = Asamblea::factory()->create(['copropiedad_id' => $copropiedad->id, 'status' => 'in_progress']);

    // Simulate another device already connected
    $service = new \App\Services\AsambleaService();
    $service->registerConnection($user, $unidad, $asamblea);

    $this->actingAs($user);

    $response = $this->get(route('asambleas.show', $asamblea));

    $response->assertStatus(403);
    $response->assertSee('Ya existe un dispositivo conectado');
});
