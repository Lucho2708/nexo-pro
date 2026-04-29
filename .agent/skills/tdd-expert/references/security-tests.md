# Tests de Seguridad — OWASP Top 10 en Laravel

## IDOR (Insecure Direct Object Reference)

```php
it('no permite acceder a recurso de otro usuario', function () {
    $usuario = User::factory()->propietario()->create();
    $recursoAjeno = Liquidacion::factory()->create(); // de otro usuario

    actingAs($usuario)
        ->getJson("/api/v1/liquidaciones/{$recursoAjeno->id}")
        ->assertForbidden();
});
```

## Rate Limiting

```php
it('bloquea login tras 5 intentos fallidos', function () {
    $user = User::factory()->create();

    foreach (range(1, 5) as $intento) {
        postJson('/api/login', [
            'email' => $user->email,
            'password' => 'incorrecta',
        ]);
    }

    postJson('/api/login', [
        'email' => $user->email,
        'password' => 'incorrecta',
    ])->assertStatus(429); // Too Many Requests
});
```

## Mass Assignment

```php
it('ignora campos no permitidos en el request', function () {
    $admin = User::factory()->administrador()->create();

    actingAs($admin)
        ->putJson('/api/v1/unidades/1', [
            'numero' => '101',
            'coeficiente' => 5.0,  // campo permitido
            'id' => 9999,          // campo NO permitido — debe ignorarse
            'copropiedad_id' => 1, // campo NO permitido — debe ignorarse
        ])
        ->assertOk()
        ->assertJsonFragment(['numero' => '101'])
        ->assertJsonMissing(['id' => 9999]);
});
```

## SQL Injection (verificar que Eloquent protege)

```php
it('no es vulnerable a sql injection en búsqueda', function () {
    $admin = User::factory()->administrador()->create();

    actingAs($admin)
        ->getJson("/api/v1/unidades?search=' OR 1=1 --")
        ->assertOk() // No debe crashear ni devolver todo
        ->assertJsonCount(0, 'data'); // Sin resultados para ese string
});
```

## Autenticación requerida

```php
it('endpoints protegidos requieren autenticación', function (string $method, string $url) {
    $this->$method($url)->assertUnauthorized();
})->with([
    ['getJson',  '/api/v1/liquidaciones'],
    ['postJson', '/api/v1/pagos'],
    ['getJson',  '/api/v1/reportes/cartera'],
]);
```
