<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\LegalDocument;
use App\Models\Copropiedad;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalConsentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_redirected_to_consent_page_if_not_accepted()
    {
        $copropiedad = Copropiedad::factory()->create();
        $user = User::factory()->create([
            'role' => 'owner',
            'current_copropiedad_id' => $copropiedad->id
        ]);
        
        LegalDocument::create([
            'type' => 'terms',
            'title' => 'Términos de Prueba',
            'body' => 'Contenido legal...',
            'version' => '1.0.0',
            'is_active' => true
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertRedirect(route('legal.consent', ['type' => 'terms']));
    }

    public function test_user_can_access_after_accepting()
    {
        $copropiedad = Copropiedad::factory()->create();
        $user = User::factory()->create([
            'role' => 'owner',
            'current_copropiedad_id' => $copropiedad->id
        ]);
        
        $document = LegalDocument::create([
            'type' => 'terms',
            'title' => 'Términos de Prueba',
            'body' => 'Contenido legal...',
            'version' => '1.0.0',
            'is_active' => true
        ]);

        // Aceptar el documento
        $this->actingAs($user)->post(route('legal.accept', $document->id));

        $response = $this->actingAs($user)->get(route('dashboard'));

        // Nota: dashboard redirige a owner.dashboard para propietarios
        $response->assertStatus(302);
        $response->assertRedirect(route('owner.dashboard'));
    }
}
