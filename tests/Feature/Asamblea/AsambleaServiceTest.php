<?php

use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Unidad;
use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Asamblea\Services\AsambleaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

test('it prevents multiple devices for the same unit in the same assembly', function () {
    $copropiedad = Copropiedad::factory()->create(['settings' => ['asamblea_virtual_enabled' => true]]);
    $user1 = User::factory()->create(['current_copropiedad_id' => $copropiedad->id]);
    $user2 = User::factory()->create(['current_copropiedad_id' => $copropiedad->id]);
    $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);

    $asamblea = Asamblea::factory()->create(['copropiedad_id' => $copropiedad->id]);
    $service = app(AsambleaService::class);

    // 1. First user joins
    $result1 = $service->canJoin($user1, $unidad);
    expect($result1)->toBeTrue();
    
    $service->registerConnection($user1, $unidad, $asamblea);

    // 2. Second user (same unit) tries to join
    $result2 = $service->canJoin($user2, $unidad);
    expect($result2)->toBeFalse();
});

test('it allows joining again if the previous session is cleared', function () {
    $copropiedad = Copropiedad::factory()->create(['settings' => ['asamblea_virtual_enabled' => true]]);
    $user = User::factory()->create(['current_copropiedad_id' => $copropiedad->id]);
    $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);

    $asamblea = Asamblea::factory()->create(['copropiedad_id' => $copropiedad->id]);
    $service = app(AsambleaService::class);

    $service->registerConnection($user, $unidad, $asamblea);
    $service->clearConnection($unidad);

    $result = $service->canJoin($user, $unidad);
    expect($result)->toBeTrue();
});

test('it generates a valid livekit token', function () {
    config([
        'services.livekit.key' => 'test_key_1234567890_test_key_123', 
        'services.livekit.secret' => 'test_secret_1234567890_test_secret_123'
    ]);
    
    $copropiedad = Copropiedad::factory()->create();
    $user = User::factory()->create(['current_copropiedad_id' => $copropiedad->id]);
    $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id, 'nombre' => '501', 'torre' => 'A']);

    $service = app(AsambleaService::class);
    $token = $service->generateToken($user, $unidad);

    expect($token)->toBeString()
        ->not->toBeEmpty();
});
