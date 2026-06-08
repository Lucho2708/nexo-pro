<?php

use App\Modules\IAM\Models\User;
use App\Modules\IAM\Models\LegalDocument;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;

uses(RefreshDatabase::class);

test('Cualquier usuario que dispare un 403 ve nuestra pagina Premium de error', function () {
    
    $user = User::factory()->create();

    // Aceptar los documentos legales
    $terms = LegalDocument::factory()->create(['type' => 'terms', 'is_active' => true, 'version' => 1]);
    $privacy = LegalDocument::factory()->create(['type' => 'privacy', 'is_active' => true, 'version' => 1]);
    $user->consents()->attach($terms->id, ['version' => 1, 'accepted_at' => now()]);
    $user->consents()->attach($privacy->id, ['version' => 1, 'accepted_at' => now()]);

    $this->assertDatabaseHas('iam.legal_consents', [
        'user_id' => $user->id,
        'legal_document_id' => $terms->id,
        'version' => 1
    ]);

    // Actuar
    $response = $this->actingAs($user)
        ->get(route('security.test-403'));

    // Verificar
    if ($response->status() !== 403) {
        dd($response->status(), $response->content());
    }
    dump($response->content());
    $response->assertStatus(403);
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Errors/Forbidden')
    );
    
    echo "\n🏆  Blindaje Premium Confirmado: El componente Errors/Forbidden se renderiza correctamente.\n";
});
