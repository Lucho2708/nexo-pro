<?php

use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Unidad;
use App\Modules\Asamblea\Models\Asamblea;
use App\Modules\Asamblea\Services\AsambleaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('it creates a dynamic log table for a specific assembly and logs events to it', function () {
    // 1. Setup
    $copropiedad = Copropiedad::factory()->create(['nombre' => 'Nexo Tower']);
    $user = User::factory()->create(['current_copropiedad_id' => $copropiedad->id]);
    $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);
    
    $asamblea = Asamblea::create([
        'copropiedad_id' => $copropiedad->id,
        'titulo' => 'Asamblea Ordinaria 2026',
        'fecha' => now(),
        'status' => 'in_progress'
    ]);

    $service = app(AsambleaService::class);
    $tableName = $asamblea->getLogTableName();

    // 2. Act: Log an event (this should trigger table creation if it doesn't exist)
    $service->logEvent($asamblea, $user, $unidad, 'login', ['browser' => 'Firefox']);

    // 3. Assert: Table exists and has the data
    expect(Schema::hasTable($tableName))->toBeTrue();
    
    $log = DB::table($tableName)->first();
    expect($log->event_type)->toBe('login');
    expect($log->user_id)->toBe($user->id);
    expect(json_decode($log->payload)->browser)->toBe('Firefox');
});
