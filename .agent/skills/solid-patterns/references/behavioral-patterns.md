# Patrones de Comportamiento — Laravel 13 / NEXO-PRO

## Strategy Pattern

**Cuándo usar:** El algoritmo varía según condición y puede crecer con nuevas variantes.

```php
// Contexto: distintas estrategias para calcular interés de mora según tipo de unidad

interface EstrategiaInteresMoraContract
{
    public function calcular(Liquidacion $liquidacion, int $diasMora): Money;
}

class InteresMoraResidencial implements EstrategiaInteresMoraContract
{
    public function calcular(Liquidacion $liquidacion, int $diasMora): Money
    {
        // Tasa 0.5% diario para residencial
        return $liquidacion->valor->porcentaje(0.5 * $diasMora);
    }
}

class InteresMoraComercial implements EstrategiaInteresMoraContract
{
    public function calcular(Liquidacion $liquidacion, int $diasMora): Money
    {
        // Tasa 1% diario para comercial
        return $liquidacion->valor->porcentaje(1.0 * $diasMora);
    }
}

class SinInteresMora implements EstrategiaInteresMoraContract
{
    public function calcular(Liquidacion $liquidacion, int $diasMora): Money
    {
        return new Money(0); // Sin intereses por acuerdo especial
    }
}

// Selector de estrategia
class SelectorEstrategiaInteres
{
    public function seleccionar(Unidad $unidad): EstrategiaInteresMoraContract
    {
        return match($unidad->tipo) {
            'residencial' => new InteresMoraResidencial(),
            'comercial'   => new InteresMoraComercial(),
            'exento'      => new SinInteresMora(),
        };
    }
}

// Service limpio: no sabe qué estrategia usa
class CalculadorMoraService
{
    public function __construct(private SelectorEstrategiaInteres $selector) {}

    public function calcular(Liquidacion $liquidacion): Money
    {
        $dias = $liquidacion->fecha_vencimiento->diffInDays(now());
        $estrategia = $this->selector->seleccionar($liquidacion->unidad);
        return $estrategia->calcular($liquidacion, $dias);
    }
}
```

---

## Observer Pattern (Laravel Events)

**Cuándo usar:** Reaccionar a cambios en el estado de un objeto sin acoplar los actores.
Laravel implementa este patrón nativamente con Events + Listeners.

```php
// Evento de dominio
class PagoRegistrado
{
    public function __construct(
        public readonly Pago $pago,
        public readonly Liquidacion $liquidacion,
    ) {}
}

// Listeners desacoplados — cada uno con UNA responsabilidad
class ActualizarEstadoLiquidacion
{
    public function handle(PagoRegistrado $event): void
    {
        $event->liquidacion->update(['estado' => 'pagada', 'fecha_pago' => now()]);
    }
}

class EnviarConfirmacionAlPropietario
{
    public function handle(PagoRegistrado $event): void
    {
        Mail::to($event->pago->propietario->email)
            ->send(new ConfirmacionPagoMail($event->pago));
    }
}

class GenerarReciboPDF
{
    public function handle(PagoRegistrado $event): void
    {
        GenerarReciboPdfJob::dispatch($event->pago);
    }
}

class ActualizarDashboardKPIs
{
    public function handle(PagoRegistrado $event): void
    {
        Cache::tags(['kpis', "copropiedad.{$event->pago->copropiedad_id}"])->flush();
    }
}

// Registro en EventServiceProvider
protected $listen = [
    PagoRegistrado::class => [
        ActualizarEstadoLiquidacion::class,      // síncrono
        EnviarConfirmacionAlPropietario::class,  // síncrono
        GenerarReciboPDF::class,                 // dispara job asíncrono
        ActualizarDashboardKPIs::class,          // síncrono
    ],
];

// Dispatch desde el Action — no sabe quién escucha
class RegistrarPagoAction
{
    public function execute(RegistrarPagoDTO $dto): Pago
    {
        $pago = Pago::create($dto->toArray());
        PagoRegistrado::dispatch($pago, $pago->liquidacion); // ← lanza el evento
        return $pago;
    }
}
```

---

## Command Pattern (Action Pattern en Laravel)

**Cuándo usar:** Encapsular una operación completa como un objeto. Facilita tests, undo y colas.

```php
// Una Action = Un comando de negocio
final class CancelarReservaZonaComunAction
{
    public function __construct(
        private ReservaRepositoryContract $repo,
        private NotificadorContract $notificador,
        private LoggerInterface $logger,
    ) {}

    public function execute(CancelarReservaDTO $dto): void
    {
        $reserva = $this->repo->findOrFail($dto->reservaId);

        // Validar política de cancelación (mínimo 2h antes)
        if ($reserva->inicio->diffInHours(now()) < 2) {
            throw new CancelacionFueraDePlazoException();
        }

        $this->repo->cancelar($reserva, $dto->motivo);
        $this->notificador->notificarCancelacion($reserva);
        $this->logger->info('Reserva cancelada', ['reserva_id' => $reserva->id]);
    }
}

// En controlador — limpio y sin lógica
public function cancelar(CancelarReservaRequest $request, CancelarReservaZonaComunAction $action): JsonResponse
{
    $action->execute(CancelarReservaDTO::fromRequest($request));
    return response()->json(['message' => 'Reserva cancelada exitosamente']);
}
```

---

## Chain of Responsibility

**Cuándo usar:** Una solicitud pasa por varios manejadores hasta que uno la resuelve.

```php
// Caso: clasificación de PQRS — intenta clasificar con reglas antes de llamar a la IA

abstract class ClasificadorPQRS
{
    protected ?ClasificadorPQRS $siguiente = null;

    public function setSiguiente(ClasificadorPQRS $siguiente): static
    {
        $this->siguiente = $siguiente;
        return $siguiente;
    }

    abstract public function clasificar(string $texto): ?string;

    protected function pasarAlSiguiente(string $texto): ?string
    {
        return $this->siguiente?->clasificar($texto) ?? 'otro';
    }
}

// Primero: reglas de palabras clave (rápido, sin costo)
class ClasificadorPorPalabras extends ClasificadorPQRS
{
    public function clasificar(string $texto): ?string
    {
        $texto = strtolower($texto);

        if (str_contains($texto, 'ascensor') || str_contains($texto, 'plomería')) {
            return 'mantenimiento';
        }
        if (str_contains($texto, 'pago') || str_contains($texto, 'deuda')) {
            return 'financiero';
        }

        return $this->pasarAlSiguiente($texto); // No pudo clasificar, pasar
    }
}

// Segundo: modelo de IA (más lento y costoso — solo si el anterior falló)
class ClasificadorPorIA extends ClasificadorPQRS
{
    public function clasificar(string $texto): ?string
    {
        try {
            return $this->openai->clasificar($texto);
        } catch (\Exception) {
            return $this->pasarAlSiguiente($texto);
        }
    }
}

// Fallback final: clasificación manual
class ClasificadorManual extends ClasificadorPQRS
{
    public function clasificar(string $texto): ?string
    {
        return 'pendiente_revision_manual';
    }
}

// Construcción de la cadena
$cadena = new ClasificadorPorPalabras();
$cadena->setSiguiente(new ClasificadorPorIA())
       ->setSiguiente(new ClasificadorManual());

$categoria = $cadena->clasificar($pqrs->descripcion);
```
