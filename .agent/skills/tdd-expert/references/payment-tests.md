# Tests de Integración — Pagos (Wompi / PSE)

## Simular webhook de Wompi (pago exitoso)

```php
it('procesa webhook de pago exitoso de wompi', function () {
    $liquidacion = Liquidacion::factory()->pendiente()->create(['valor' => 200000]);

    $payload = [
        'event' => 'transaction.updated',
        'data' => [
            'transaction' => [
                'id'        => 'wompi-txn-123',
                'reference' => $liquidacion->referencia_pago,
                'status'    => 'APPROVED',
                'amount_in_cents' => 20000000, // 200.000 COP en centavos
            ]
        ],
        'timestamp' => now()->timestamp,
    ];

    // Firma HMAC válida
    $firma = hash('sha256', $payload['timestamp'] . config('services.wompi.events_secret'));

    postJson('/api/webhooks/wompi', $payload, [
        'X-Event-Checksum' => $firma,
    ])->assertOk();

    expect($liquidacion->fresh()->estado)->toBe('pagada');
    expect($liquidacion->fresh()->fecha_pago)->not->toBeNull();
});
```

## Idempotencia — webhook duplicado no debe crear doble pago

```php
it('webhook duplicado no genera doble registro de pago', function () {
    $liquidacion = Liquidacion::factory()->pagada()->create();

    // Enviar el mismo webhook dos veces
    $payload = webhookWompiAprobado($liquidacion);
    postJson('/api/webhooks/wompi', $payload)->assertOk();
    postJson('/api/webhooks/wompi', $payload)->assertOk();

    // Sigue siendo un solo pago
    $this->assertDatabaseCount('pagos', 1);
});
```

## Firma inválida rechazada

```php
it('rechaza webhook con firma inválida', function () {
    postJson('/api/webhooks/wompi', ['event' => 'transaction.updated'], [
        'X-Event-Checksum' => 'firma-invalida',
    ])->assertUnauthorized();
});
```
