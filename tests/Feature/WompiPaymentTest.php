<?php

namespace Tests\Feature;

use App\Models\Copropiedad;
use App\Models\OnlinePayment;
use App\Models\Unidad;
use App\Models\User;
use App\Models\ConceptoCobro;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class WompiPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configurar llaves de prueba
        Config::set('services.wompi.integrity_key', 'test_integrity_key');
        Config::set('services.wompi.webhook_secret', 'test_webhook_secret');
        Config::set('services.wompi.public_key', 'pub_test_123');
    }

    /** @test */
    public function test_it_can_initiate_a_payment_with_property_specific_keys()
    {
        $copropiedad = Copropiedad::factory()->create([
            'license_expires_at' => now()->addYear(),
            'settings' => [
                'wompi_integrity_key' => 'custom_integrity',
                'wompi_public_key' => 'pub_custom',
            ]
        ]);
        $user = User::factory()->create([
            'role' => 'owner',
            'two_factor_secret' => 'dummy_secret',
            'two_factor_confirmed_at' => now(),
            'current_copropiedad_id' => $copropiedad->id,
        ]);
        $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);

        $response = $this->actingAs($user)
            ->postJson(route('payments.initiate'), [
                'unidad_id' => $unidad->id,
                'amount' => 50000,
            ]);
        
        $response->dump();

        $response->assertStatus(200)
            ->assertJsonStructure(['public_key', 'reference', 'amount_in_cents', 'signature']);

        $this->assertEquals('pub_custom', $response->json('public_key'));
        
        // Verificar registro en DB
        $this->assertDatabaseHas('online_payments', [
            'amount' => 50000,
            'unidad_id' => $unidad->id,
            'status' => 'PENDING',
        ]);
    }

    /** @test */
    public function test_it_processes_an_approved_webhook_correctly()
    {
        $copropiedad = Copropiedad::factory()->create([
            'settings' => [
                'wompi_webhook_secret' => 'webhook_secret_123',
            ]
        ]);
        
        // Crear concepto de administración
        ConceptoCobro::factory()->create([
            'copropiedad_id' => $copropiedad->id,
            'codigo' => 'ADM'
        ]);

        $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id, 'saldo_actual' => 100000]);
        $user = User::factory()->create();
        
        $payment = OnlinePayment::create([
            'user_id' => $user->id,
            'unidad_id' => $unidad->id,
            'amount' => 50000,
            'reference' => 'TEST-12345',
            'status' => 'PENDING'
        ]);

        $timestamp = time();
        $wompiId = 'TRAN-ID-999';
        
        // Signature: concat(id, status, amount_in_cents, timestamp, secret)
        $rawString = "{$wompiId}APPROVED5000000{$timestamp}webhook_secret_123";
        $checksum = hash('sha256', $rawString);

        $payload = [
            'event' => 'transaction.updated',
            'data' => [
                'transaction' => [
                    'id' => $wompiId,
                    'status' => 'APPROVED',
                    'amount_in_cents' => 5000000,
                    'reference' => 'TEST-12345',
                    'payment_method_type' => 'CARD'
                ],
            ],
            'sent_at' => date('c'),
            'timestamp' => $timestamp
        ];

        $response = $this->postJson(route('payments.webhook'), $payload, [
            'X-Event-Checksum' => $checksum,
            'X-Event-Timestamp' => $timestamp
        ]);

        $response->assertStatus(200);

        // Verificar estados
        $this->assertEquals('APPROVED', $payment->fresh()->status);
        $this->assertEquals($wompiId, $payment->fresh()->wompi_id);

        // Verificar que se creó la transacción contable interna
        $this->assertDatabaseHas('transacciones', [
            'unidad_id' => $unidad->id,
            'monto' => 50000,
            'tipo' => 'abono'
        ]);

        // Verificar actualización de saldo (100.000 - 50.000 = 50.000)
        $this->assertEquals(50000, $unidad->fresh()->saldo_actual);
    }

    /** @test */
    public function test_it_rejects_webhooks_with_invalid_checksum()
    {
        $copropiedad = Copropiedad::factory()->create([
            'settings' => ['wompi_webhook_secret' => 'real_secret']
        ]);
        $unidad = Unidad::factory()->create(['copropiedad_id' => $copropiedad->id]);
        
        OnlinePayment::create([
            'user_id' => User::factory()->create()->id,
            'unidad_id' => $unidad->id,
            'amount' => 1000,
            'reference' => 'TEST-REF'
        ]);

        $response = $this->postJson(route('payments.webhook'), [
            'data' => ['transaction' => ['id' => '1', 'status' => 'APPROVED', 'amount_in_cents' => 100000, 'reference' => 'TEST-REF']]
        ], [
            'X-Event-Checksum' => 'wrong_checksum',
            'X-Event-Timestamp' => time()
        ]);

        $response->assertStatus(403);
    }
}
