<?php

namespace Tests\Feature\SuperAdmin;

use App\Models\Copropiedad;
use App\Models\User;
use App\Mail\Admin\WelcomeManagementMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PropertyTransferTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /**
     * Test that a Super Admin can transfer management of a single property
     * from one admin to another without affecting other properties.
     */
    public function test_super_admin_can_transfer_management_of_single_property()
    {
        // 1. Create Super Admin
        $superAdmin = User::factory()->create(['role' => 'super_admin']);

        // 2. Create Admin A with 2 properties
        $adminA = User::factory()->create(['role' => 'admin']);
        $property1 = Copropiedad::factory()->create(['nombre' => 'Conjunto 1']);
        $property2 = Copropiedad::factory()->create(['nombre' => 'Conjunto 2']);
        
        $adminA->managedCopropiedades()->attach([$property1->id, $property2->id]);
        $adminA->update(['current_copropiedad_id' => $property1->id]);

        // 3. Create Admin B (New Admin)
        $adminB = User::factory()->create(['role' => 'admin', 'name' => 'Admin B']);

        // 4. Perform Transfer of Property 1 to Admin B
        $response = $this->actingAs($superAdmin)
            ->post(route('superadmin.properties.transfer', $property1->id), [
                'old_admin_id' => $adminA->id,
                'new_admin_id' => $adminB->id,
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // 5. Assertions
        // Admin A should NOT have Property 1 anymore
        $this->assertFalse($adminA->managedCopropiedades()->where('copropiedades.id', $property1->id)->exists());
        
        // Admin A SHOULD still have Property 2 (Isolation Check)
        $this->assertTrue($adminA->managedCopropiedades()->where('copropiedades.id', $property2->id)->exists());

        // Admin B SHOULD have Property 1
        $this->assertTrue($adminB->managedCopropiedades()->where('copropiedades.id', $property1->id)->exists());

        // Mail should be sent to Admin B
        Mail::assertSent(WelcomeManagementMail::class, function ($mail) use ($adminB, $property1) {
            return $mail->hasTo($adminB->email) && $mail->copropiedad->id === $property1->id;
        });
    }

    /**
     * Test that non-super admins cannot access the transfer functionality.
     */
    public function test_only_super_admin_can_transfer_management()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $property = Copropiedad::factory()->create();
        
        $response = $this->actingAs($admin)
            ->post(route('superadmin.properties.transfer', $property->id), [
                'old_admin_id' => $admin->id,
                'new_admin_id' => User::factory()->create()->id,
            ]);

        $response->assertStatus(403);
    }
}
