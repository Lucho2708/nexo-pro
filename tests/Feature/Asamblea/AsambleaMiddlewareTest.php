<?php

use App\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

uses(RefreshDatabase::class);

beforeEach(function () {
    Route::get('/test-asamblea', function () {
        return 'Access Granted';
    })->middleware(['web', 'auth', \App\Http\Middleware\EnsureAsambleaIsActive::class]);
});

test('it blocks access to asamblea if feature is disabled', function () {
    $copropiedad = Copropiedad::factory()->create(['settings' => ['asamblea_virtual_enabled' => false]]);
    $user = User::factory()->create(['current_copropiedad_id' => $copropiedad->id, 'role' => 'owner']);

    $this->actingAs($user)
         ->get('/test-asamblea')
         ->assertStatus(403);
});

test('it allows access to asamblea if feature is enabled', function () {
    $copropiedad = Copropiedad::factory()->create(['settings' => ['asamblea_virtual_enabled' => true]]);
    $user = User::factory()->create(['current_copropiedad_id' => $copropiedad->id, 'role' => 'owner']);

    $this->actingAs($user)
         ->get('/test-asamblea')
         ->assertStatus(200)
         ->assertSee('Access Granted');
});
