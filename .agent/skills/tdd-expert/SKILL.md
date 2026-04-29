---
name: tdd-expert
description: >
  Experto en Test-Driven Development (TDD) para proyectos Laravel (PHP) y Vue.js.
  Usa este skill SIEMPRE que el usuario mencione: escribir tests, TDD, pruebas unitarias,
  feature tests, PHPUnit, Pest, Vitest, testing de APIs, coverage, mocks, factories,
  seeders de prueba, o cuando pida implementar una funcionalidad nueva desde cero usando
  el ciclo Red-Green-Refactor. También activar cuando el usuario diga "cómo pruebo esto",
  "hazme el test para...", "quiero hacer TDD", "necesito tests para mi controlador/modelo/componente".
  Aplica tanto a proyectos nuevos como a código existente sin cobertura.
---

# TDD Expert — Laravel 13 + Vue.js 3

Eres un experto en Test-Driven Development. Tu misión es guiar al usuario a través del
ciclo **Red → Green → Refactor** con ejemplos concretos, listos para copiar y ejecutar.

---

## Filosofía TDD que debes aplicar

1. **Nunca escribir código de producción sin un test fallando primero**
2. **Escribir el test más simple posible que falle**
3. **Escribir el código mínimo para que el test pase**
4. **Refactorizar con los tests en verde**

---

## Stack de Testing del Proyecto

| Capa | Herramienta | Comando |
|---|---|---|
| Backend unitario | **Pest PHP** (sobre PHPUnit) | `php artisan test` |
| Backend feature | **Pest + Laravel HTTP Tests** | `php artisan test --filter=NombreTest` |
| Frontend unitario | **Vitest** | `npm run test` |
| Frontend componentes | **Vue Test Utils + Vitest** | `npm run test:unit` |
| E2E (opcional) | **Playwright** | `npx playwright test` |
| Coverage backend | **Xdebug / PCOV** | `php artisan test --coverage` |
| Coverage frontend | **V8 / Istanbul** | `npm run test:coverage` |

---

## Ciclo TDD — Paso a Paso

### FASE 1: RED (test que falla)

Escribe PRIMERO el test. Debe fallar porque la funcionalidad no existe aún.

```php
// tests/Feature/Recaudo/GenerarLiquidacionTest.php
it('genera liquidacion mensual para todas las unidades activas', function () {
    // Arrange
    $copropiedad = Copropiedad::factory()->create();
    $unidades = Unidad::factory()->count(3)->for($copropiedad)->create([
        'coeficiente' => 33.33,
        'estado' => 'activa',
    ]);
    $config = ConfiguracionRecaudo::factory()->for($copropiedad)->create([
        'valor_administracion' => 300000,
    ]);

    // Act
    $resultado = app(GenerarLiquidacionService::class)
        ->ejecutar($copropiedad, now()->month, now()->year);

    // Assert
    expect($resultado)->toHaveCount(3);
    expect($resultado->first()->valor)->toBe(100000); // 300000 * 33.33%
    $this->assertDatabaseCount('liquidaciones', 3);
});
```

**Ejecutar:** `php artisan test --filter=genera_liquidacion` → ❌ ROJO esperado.

---

### FASE 2: GREEN (mínimo código para pasar)

```php
// app/Services/Recaudo/GenerarLiquidacionService.php
class GenerarLiquidacionService
{
    public function ejecutar(Copropiedad $copropiedad, int $mes, int $año): Collection
    {
        $config = $copropiedad->configuracionRecaudo;

        return $copropiedad->unidades()->activas()->get()->map(function (Unidad $unidad) use ($config, $mes, $año) {
            return Liquidacion::create([
                'unidad_id'    => $unidad->id,
                'mes'          => $mes,
                'año'          => $año,
                'valor'        => round($config->valor_administracion * ($unidad->coeficiente / 100)),
                'estado'       => 'pendiente',
            ]);
        });
    }
}
```

**Ejecutar:** `php artisan test --filter=genera_liquidacion` → ✅ VERDE.

---

### FASE 3: REFACTOR (mejorar sin romper)

Mejorar el código manteniendo los tests en verde:
- Extraer cálculo de valor a un `ValueObject`
- Agregar evento `LiquidacionGenerada`
- Validar que no exista liquidación duplicada del mismo mes/año

---

## Tipos de Tests en Laravel — Cuándo usar cada uno

### Unit Test (prueba una clase aislada)

```php
// tests/Unit/Services/ConciliacionBancariaTest.php
it('hace match exacto por monto y referencia', function () {
    $servicio = new AlgoritmoConciliacion();

    $pagoSistema = ['referencia' => 'REF-001', 'monto' => 150000];
    $movimientoBanco = ['descripcion' => 'PAGO REF-001', 'valor' => 150000];

    $resultado = $servicio->intentarMatch($pagoSistema, $movimientoBanco);

    expect($resultado->confianza)->toBeGreaterThan(0.9)
        ->and($resultado->tipo)->toBe('exacto');
});
```

### Feature Test (prueba un flujo HTTP completo)

```php
// tests/Feature/Api/PagosTest.php
it('propietario puede ver solo sus propios pagos', function () {
    $propietario = User::factory()->propietario()->create();
    $otroPropietario = User::factory()->propietario()->create();

    $miPago = Pago::factory()->for($propietario)->create();
    $pagoAjeno = Pago::factory()->for($otroPropietario)->create();

    actingAs($propietario)
        ->getJson('/api/v1/pagos')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['id' => $miPago->id])
        ->assertJsonMissing(['id' => $pagoAjeno->id]);
});
```

### Test de Autorización (seguridad — IDOR)

```php
it('propietario no puede ver pago de otra unidad', function () {
    $propietario = User::factory()->propietario()->create();
    $pagoAjeno = Pago::factory()->create(); // de otro usuario

    actingAs($propietario)
        ->getJson("/api/v1/pagos/{$pagoAjeno->id}")
        ->assertForbidden();
});
```

### Test de Job/Queue

```php
it('envía notificación de mora al ejecutar el job', function () {
    Mail::fake();
    Queue::fake();

    $unidad = Unidad::factory()->enMora()->create();

    EnviarRecordatorioMoraJob::dispatch($unidad);

    Queue::assertPushed(EnviarRecordatorioMoraJob::class);
    Mail::assertQueued(RecordatorioMoraMail::class, fn($mail) =>
        $mail->hasTo($unidad->propietario->email)
    );
});
```

### Test de WebSocket / Broadcasting

```php
it('transmite resultado de votación en tiempo real', function () {
    Event::fake();

    $asamblea = Asamblea::factory()->enCurso()->create();
    $admin = User::factory()->administrador()->create();

    actingAs($admin)
        ->postJson("/api/v1/asambleas/{$asamblea->id}/votos", [
            'punto_orden_id' => 1,
            'opcion' => 'a_favor',
        ])
        ->assertCreated();

    Event::assertDispatched(VotoRegistrado::class);
});
```

---

## Factories — Buenas Prácticas

```php
// database/factories/UnidadFactory.php
class UnidadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'numero'      => $this->faker->numerify('##-##'),
            'coeficiente' => $this->faker->randomFloat(4, 0.5, 5.0),
            'estado'      => 'activa',
            'area_m2'     => $this->faker->numberBetween(40, 200),
        ];
    }

    // Estados semánticos — usar en tests
    public function enMora(): static
    {
        return $this->state(['estado_pago' => 'mora']);
    }

    public function inactiva(): static
    {
        return $this->state(['estado' => 'inactiva']);
    }
}
```

**Regla:** Nunca crear datos de prueba directamente con `Model::create()` en los tests. Siempre usar factories.

---

## Tests Vue.js con Vitest + Vue Test Utils

### Test de componente

```javascript
// tests/unit/components/EstadoCuenta.test.js
import { mount } from '@vue/test-utils'
import { describe, it, expect } from 'vitest'
import EstadoCuenta from '@/Components/EstadoCuenta.vue'

describe('EstadoCuenta', () => {
  it('muestra saldo en rojo cuando hay mora', () => {
    const wrapper = mount(EstadoCuenta, {
      props: {
        saldo: -150000,
        estado: 'mora'
      }
    })

    expect(wrapper.find('[data-testid="saldo"]').classes()).toContain('text-red-600')
    expect(wrapper.find('[data-testid="saldo"]').text()).toContain('$150.000')
  })

  it('muestra botón de pago solo cuando hay saldo pendiente', () => {
    const wrapper = mount(EstadoCuenta, {
      props: { saldo: 200000, estado: 'pendiente' }
    })

    expect(wrapper.find('[data-testid="btn-pagar"]').exists()).toBe(true)
  })

  it('no muestra botón de pago cuando está al día', () => {
    const wrapper = mount(EstadoCuenta, {
      props: { saldo: 0, estado: 'al_dia' }
    })

    expect(wrapper.find('[data-testid="btn-pagar"]').exists()).toBe(false)
  })
})
```

### Test de Composable (Vue 3)

```javascript
// tests/unit/composables/useReserva.test.js
import { describe, it, expect, vi } from 'vitest'
import { useReserva } from '@/Composables/useReserva'

describe('useReserva', () => {
  it('bloquea reserva si unidad está en mora', async () => {
    const { puedeReservar, validarMora } = useReserva()

    vi.mocked(validarMora).mockResolvedValue({ en_mora: true })

    const resultado = await puedeReservar({ unidad_id: 5 })

    expect(resultado).toBe(false)
  })
})
```

---

## Configuración recomendada

### Pest (backend)

```php
// tests/Pest.php
uses(Tests\TestCase::class)->in('Feature');
uses(Tests\TestCase::class)->in('Unit');

// Helpers globales
function actingAsAdmin(): TestCase {
    return test()->actingAs(User::factory()->administrador()->create());
}
```

```php
// phpunit.xml — variables de entorno para tests
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
<env name="QUEUE_CONNECTION" value="sync"/>
<env name="MAIL_MAILER" value="array"/>
<env name="CACHE_DRIVER" value="array"/>
```

### Vitest (frontend)

```javascript
// vitest.config.js
import { defineConfig } from 'vitest/config'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
  plugins: [vue()],
  test: {
    environment: 'jsdom',
    globals: true,
    coverage: {
      reporter: ['text', 'html'],
      threshold: { lines: 70 }
    }
  }
})
```

---

## Checklist TDD por cada Historia de Usuario

Antes de marcar una HU como "Done", verificar:

- [ ] Test unitario del Service/Action principal
- [ ] Feature test del endpoint HTTP (happy path)
- [ ] Feature test de autorización (caso de acceso denegado)
- [ ] Feature test de validación (datos inválidos → 422)
- [ ] Test del caso borde más probable (valor cero, lista vacía, duplicado)
- [ ] Test de componente Vue si hay lógica condicional en el template
- [ ] Coverage de la nueva clase ≥ 80%

---

## Referencias adicionales

- Para tests de seguridad avanzados → ver `references/security-tests.md`
- Para tests de integración con Wompi/PSE → ver `references/payment-tests.md`
- Para tests de WebSockets con Reverb → ver `references/websocket-tests.md`
