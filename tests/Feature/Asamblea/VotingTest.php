<?php

use App\Models\Copropiedad;
use App\Models\User;
use App\Models\Unidad;
use App\Models\Asamblea;
use App\Models\Pregunta;
use App\Models\Opcion;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a resident can vote in an open question', function () {
    // 1. Setup
    $copropiedad = Copropiedad::factory()->create(['settings' => ['asamblea_virtual_enabled' => true]]);
    $user = User::factory()->create(['current_copropiedad_id' => $copropiedad->id]);
    $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id, 'coeficiente' => 0.05]);
    $user->unidades()->attach($unidad, ['role' => 'owner']);

    $asamblea = Asamblea::factory()->create(['copropiedad_id' => $copropiedad->id, 'status' => 'in_progress']);
    $pregunta = Pregunta::create([
        'asamblea_id' => $asamblea->id,
        'titulo' => '¿Aprueba el presupuesto?',
        'status' => 'open'
    ]);
    $opcion = Opcion::create(['pregunta_id' => $pregunta->id, 'titulo' => 'SÍ']);

    $this->actingAs($user);

    // 2. Act
    $response = $this->post(route('asambleas.votar', $pregunta), [
        'opcion_id' => $opcion->id
    ]);

    // 3. Assert
    $response->assertStatus(200);
    $this->assertDatabaseHas('votos', [
        'pregunta_id' => $pregunta->id,
        'user_id' => $user->id,
        'unidad_id' => $unidad->id,
        'opcion_id' => $opcion->id,
        'peso' => 0.05
    ]);
});

test('a resident cannot vote twice in the same question', function () {
    $copropiedad = Copropiedad::factory()->create(['settings' => ['asamblea_virtual_enabled' => true]]);
    $user = User::factory()->create(['current_copropiedad_id' => $copropiedad->id]);
    $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);
    $user->unidades()->attach($unidad, ['role' => 'owner']);

    $asamblea = Asamblea::factory()->create(['copropiedad_id' => $copropiedad->id]);
    $pregunta = Pregunta::create(['asamblea_id' => $asamblea->id, 'titulo' => 'Test', 'status' => 'open']);
    $opcion = Opcion::create(['pregunta_id' => $pregunta->id, 'titulo' => 'A']);

    $this->actingAs($user);

    // First vote
    $this->post(route('asambleas.votar', $pregunta), ['opcion_id' => $opcion->id]);
    
    // Second vote attempt
    $response = $this->post(route('asambleas.votar', $pregunta), ['opcion_id' => $opcion->id]);

    $response->assertStatus(403);
});
