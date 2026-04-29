# Principios SOLID — Laravel 13 / NEXO-PRO

## S — Single Responsibility Principle (SRP)

> Una clase debe tener **una sola razón para cambiar**.

### Violación común en Laravel

```php
// ❌ MAL: el modelo hace demasiado
class Propietario extends Model
{
    public function calcularDeuda(): float { /* ... */ }
    public function enviarEstadoCuenta(): void { /* ... */ }
    public function generarPazYSalvo(): string { /* ... */ }
    public function validarMora(): bool { /* ... */ }
}
```

### Aplicación correcta

```php
// ✅ Cada clase tiene UNA responsabilidad
class Propietario extends Model
{
    // Solo relaciones y scopes de consulta
    public function unidades(): HasMany { return $this->hasMany(Unidad::class); }
    public function scopeEnMora(Builder $q): Builder { return $q->whereHas('liquidaciones', ...); }
}

class CalculadorDeudaService     { public function calcular(Propietario $p): Money { ... } }
class EstadoCuentaMailer         { public function enviar(Propietario $p): void { ... } }
class GeneradorPazYSalvoService  { public function generar(Propietario $p): string { ... } }
```

### Señales de que se viola SRP
- La clase importa namespaces de áreas muy distintas (Mail, PDF, Storage, DB...)
- El nombre de la clase tiene "And" o "Manager" (ej: `PagoAndNotificacionManager`)
- Al cambiar la lógica de email, también debes tocar la clase de cálculo

---

## O — Open/Closed Principle (OCP)

> El código debe estar **abierto para extensión, cerrado para modificación**.

### Violación común

```php
// ❌ MAL: agregar nueva pasarela obliga a modificar esta clase
class ProcesadorPago
{
    public function procesar(Pago $pago): Resultado
    {
        if ($pago->metodo === 'wompi') {
            // lógica Wompi
        } elseif ($pago->metodo === 'pse') {
            // lógica PSE
        } elseif ($pago->metodo === 'nequi') {
            // lógica Nequi — nueva adición que requirió modificar esta clase
        }
    }
}
```

### Aplicación correcta — Strategy Pattern

```php
// ✅ BIEN: agregar Daviplata = crear nueva clase, sin tocar las existentes
interface PasarelaPagoContract
{
    public function procesar(Pago $pago): ResultadoPago;
    public function verificarWebhook(array $payload): bool;
}

class WompiPasarela implements PasarelaPagoContract { ... }
class PsePasarela    implements PasarelaPagoContract { ... }
class NequiPasarela  implements PasarelaPagoContract { ... }

class ProcesadorPago
{
    public function __construct(private PasarelaPagoContract $pasarela) {}

    public function procesar(Pago $pago): ResultadoPago
    {
        return $this->pasarela->procesar($pago); // nunca cambia
    }
}

// En AppServiceProvider — binding dinámico
$this->app->bind(PasarelaPagoContract::class, function () {
    return match(config('pagos.pasarela_activa')) {
        'wompi' => new WompiPasarela(),
        'pse'   => new PsePasarela(),
        'nequi' => new NequiPasarela(),
    };
});
```

---

## L — Liskov Substitution Principle (LSP)

> Las clases hijas deben poder **sustituir a sus padres** sin romper el sistema.

### Violación común

```php
// ❌ MAL: ArrendatarioResidente sobreescribe y lanza excepción
class Residente
{
    public function generarPazYSalvo(): string
    {
        return "PAZ Y SALVO - {$this->nombre}";
    }
}

class ArrendatarioResidente extends Residente
{
    public function generarPazYSalvo(): string
    {
        // Viola LSP: el padre prometía retornar string, el hijo lanza excepción
        throw new \Exception("Arrendatarios no tienen paz y salvo");
    }
}
```

### Aplicación correcta

```php
// ✅ BIEN: usar interfaces para contratos explícitos
interface PuedeTenerPazYSalvoContract
{
    public function generarPazYSalvo(): string;
}

class PropietarioResidente extends Residente implements PuedeTenerPazYSalvoContract
{
    public function generarPazYSalvo(): string { return "PAZ Y SALVO - {$this->nombre}"; }
}

class ArrendatarioResidente extends Residente
{
    // No implementa la interfaz — no promete lo que no puede cumplir
}
```

---

## I — Interface Segregation Principle (ISP)

> Ninguna clase debe depender de métodos que **no usa**.

### Violación común

```php
// ❌ MAL: interfaz "gorda" que obliga a implementar cosas innecesarias
interface UsuarioSistemaContract
{
    public function verEstadoCuenta(): array;
    public function aprobarPresupuesto(): bool;   // ← solo Consejo
    public function generarInformeContable(): PDF; // ← solo Contador
    public function reservarZonaComun(): void;
    public function registrarVisitante(): void;    // ← solo Portero
}
```

### Aplicación correcta

```php
// ✅ BIEN: interfaces pequeñas y específicas por rol
interface PuedeVerFinanzasContract    { public function verEstadoCuenta(): array; }
interface PuedeAprobarContract        { public function aprobarPresupuesto(): bool; }
interface PuedeGenerarInformesContract{ public function generarInformeContable(): PDF; }
interface PuedeReservarContract       { public function reservarZonaComun(): void; }
interface PuedeRegistrarAccesoContract{ public function registrarVisitante(): void; }

class Propietario    implements PuedeVerFinanzasContract, PuedeReservarContract { ... }
class ConsejoMiembro implements PuedeVerFinanzasContract, PuedeAprobarContract { ... }
class Contador       implements PuedeVerFinanzasContract, PuedeGenerarInformesContract { ... }
class Portero        implements PuedeRegistrarAccesoContract { ... }
```

---

## D — Dependency Inversion Principle (DIP)

> Los módulos de alto nivel **no deben depender de módulos de bajo nivel**.
> Ambos deben depender de **abstracciones**.

### Violación común

```php
// ❌ MAL: la acción está acoplada a la implementación concreta de Wompi
class ProcesarPagoAction
{
    public function execute(Pago $pago): void
    {
        $wompi = new WompiClient(); // acoplamiento duro
        $wompi->cobrar($pago->monto, $pago->referencia);
    }
}
```

### Aplicación correcta

```php
// ✅ BIEN: depender de la abstracción, no de la implementación
class ProcesarPagoAction
{
    public function __construct(
        private readonly PasarelaPagoContract $pasarela,    // abstracción
        private readonly PagoRepositoryContract $pagos,     // abstracción
        private readonly NotificadorContract $notificador,  // abstracción
    ) {}

    public function execute(ProcesarPagoDTO $dto): ResultadoPago
    {
        $resultado = $this->pasarela->procesar($dto);
        $this->pagos->registrar($resultado);
        $this->notificador->notificarPago($resultado);
        return $resultado;
    }
}

// En tests: inyectar mocks fácilmente
$action = new ProcesarPagoAction(
    pasarela: Mockery::mock(PasarelaPagoContract::class),
    pagos:    Mockery::mock(PagoRepositoryContract::class),
    notificador: Mockery::mock(NotificadorContract::class),
);
```

### Binding en Laravel Service Container

```php
// app/Providers/AppServiceProvider.php
public function register(): void
{
    $this->app->bind(PasarelaPagoContract::class, WompiPasarela::class);
    $this->app->bind(PagoRepositoryContract::class, EloquentPagoRepository::class);
    $this->app->bind(NotificadorContract::class, MultiCanalNotificador::class);
}
```
