<?php

namespace Tests\Feature\Security;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrafficControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_route_is_rate_limited()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'wrong@example.com',
                'password' => 'password'
            ]);
        }

        // The 6th attempt should be rate limited
        $response = $this->post('/login', [
            'email' => 'wrong@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(429);
    }

    public function test_payment_webhook_is_rate_limited()
    {
        for ($i = 0; $i < 6; $i++) {
            $this->post(route('api.payments.webhook', 'wompi'));
        }

        $response = $this->post(route('api.payments.webhook', 'wompi'));

        $response->assertStatus(429);
    }
}
