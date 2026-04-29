# Patrones Creacionales — Laravel 13 / NEXO-PRO

## Factory Pattern

**Cuándo usar:** Crear objetos complejos cuya construcción tiene variantes.

```php
// Contrato
interface NotificacionFactoryContract
{
    public function crear(string $tipo, array $datos): Notificacion;
}

// Implementación
class NotificacionFactory implements NotificacionFactoryContract
{
    public function crear(string $tipo, array $datos): Notificacion
    {
        return match($tipo) {
            'mora'         => new NotificacionMora($datos),
            'paz_y_salvo'  => new NotificacionPazYSalvo($datos),
            'asamblea'     => new NotificacionAsamblea($datos),
            'mantenimiento'=> new NotificacionMantenimiento($datos),
            default        => throw new TipoNotificacionInvalidoException($tipo),
        };
    }
}

// Uso en un Service
class EnviarNotificacionService
{
    public function __construct(private NotificacionFactoryContract $factory) {}

    public function enviar(string $tipo, Propietario $propietario): void
    {
        $notificacion = $this->factory->crear($tipo, ['propietario' => $propietario]);
        $notificacion->enviar();
    }
}
```

---

## Builder Pattern

**Cuándo usar:** Construir objetos complejos paso a paso con muchas opciones opcionales.

```php
// Builder para generar reportes de cartera configurables
class ReporteCarteraBuilder
{
    private Carbon $fechaDesde;
    private Carbon $fechaHasta;
    private bool $incluirPagados = false;
    private bool $incluirEnMora  = true;
    private ?int $copropiedadId  = null;
    private string $formato      = 'pdf';

    public function desdeFecha(Carbon $fecha): static
    {
        $this->fechaDesde = $fecha;
        return $this;
    }

    public function hastaFecha(Carbon $fecha): static
    {
        $this->fechaHasta = $fecha;
        return $this;
    }

    public function incluyendoPagados(): static
    {
        $this->incluirPagados = true;
        return $this;
    }

    public function soloCopropiedad(int $id): static
    {
        $this->copropiedadId = $id;
        return $this;
    }

    public function enFormato(string $formato): static
    {
        $this->formato = $formato;
        return $this;
    }

    public function build(): ReporteCartera
    {
        return new ReporteCartera(
            fechaDesde: $this->fechaDesde,
            fechaHasta: $this->fechaHasta,
            incluirPagados: $this->incluirPagados,
            incluirEnMora:  $this->incluirEnMora,
            copropiedadId:  $this->copropiedadId,
            formato:        $this->formato,
        );
    }
}

// Uso legible y fluido
$reporte = (new ReporteCarteraBuilder())
    ->desdeFecha(now()->startOfMonth())
    ->hastaFecha(now()->endOfMonth())
    ->soloCopropiedad($copropiedad->id)
    ->enFormato('excel')
    ->build();
```

---

## DTO (Data Transfer Object)

**Cuándo usar:** Transferir datos entre capas sin exponer el modelo Eloquent.
Siempre inmutable (readonly).

```php
final readonly class GenerarLiquidacionDTO
{
    public function __construct(
        public int    $copropiedadId,
        public int    $mes,
        public int    $año,
        public Money  $valorBase,
        public string $concepto,
    ) {}

    // Factory method desde Request HTTP
    public static function fromRequest(GenerarLiquidacionRequest $request): self
    {
        return new self(
            copropiedadId: $request->integer('copropiedad_id'),
            mes:           $request->integer('mes'),
            año:           $request->integer('año'),
            valorBase:     new Money($request->integer('valor_base')),
            concepto:      $request->string('concepto'),
        );
    }

    // Factory method desde array (útil en tests)
    public static function fake(array $overrides = []): self
    {
        return new self(...array_merge([
            'copropiedadId' => 1,
            'mes'           => now()->month,
            'año'           => now()->year,
            'valorBase'     => new Money(300000),
            'concepto'      => 'Administración',
        ], $overrides));
    }
}
```

---

## Value Object

**Cuándo usar:** Representar conceptos del dominio con lógica de validación y operaciones propias.

```php
final readonly class Money
{
    public function __construct(private int $centavos)
    {
        if ($centavos < 0) {
            throw new \InvalidArgumentException("El dinero no puede ser negativo");
        }
    }

    public static function fromPesos(float $pesos): self
    {
        return new self((int) round($pesos * 100));
    }

    public function sumar(Money $otro): self
    {
        return new self($this->centavos + $otro->centavos);
    }

    public function porcentaje(float $pct): self
    {
        return new self((int) round($this->centavos * ($pct / 100)));
    }

    public function toPesos(): float
    {
        return $this->centavos / 100;
    }

    public function formato(): string
    {
        return '$ ' . number_format($this->toPesos(), 0, ',', '.');
    }

    public function equals(Money $otro): bool
    {
        return $this->centavos === $otro->centavos;
    }
}

// Uso en la lógica de negocio
$valorBase  = Money::fromPesos(300000);
$cuota      = $valorBase->porcentaje($unidad->coeficiente);
echo $cuota->formato(); // $ 99.990
```
