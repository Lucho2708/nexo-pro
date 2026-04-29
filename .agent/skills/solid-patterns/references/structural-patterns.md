# Patrones Estructurales — Laravel 13 / NEXO-PRO

## Repository Pattern

**Cuándo usar:** Desacoplar la lógica de negocio del acceso a datos.
Permite cambiar Eloquent por otra fuente sin tocar los Services.

```php
// 1. Contrato (Interfaz)
interface LiquidacionRepositoryContract
{
    public function porPropietario(int $propietarioId, FiltroPeriodo $filtro): Collection;
    public function pendientesDeCopropiedad(int $copropiedadId): Collection;
    public function crear(CrearLiquidacionDTO $dto): Liquidacion;
    public function marcarPagada(Liquidacion $liq, PagoDTO $pago): Liquidacion;
}

// 2. Implementación Eloquent
class EloquentLiquidacionRepository implements LiquidacionRepositoryContract
{
    public function porPropietario(int $propietarioId, FiltroPeriodo $filtro): Collection
    {
        return Liquidacion::query()
            ->whereHas('unidad.propietario', fn($q) => $q->where('id', $propietarioId))
            ->whereBetween('periodo', [$filtro->desde, $filtro->hasta])
            ->with(['unidad', 'pagos'])
            ->get();
    }

    public function pendientesDeCopropiedad(int $copropiedadId): Collection
    {
        return Liquidacion::query()
            ->whereHas('unidad', fn($q) => $q->where('copropiedad_id', $copropiedadId))
            ->where('estado', 'pendiente')
            ->get();
    }

    public function crear(CrearLiquidacionDTO $dto): Liquidacion
    {
        return Liquidacion::create($dto->toArray());
    }

    public function marcarPagada(Liquidacion $liq, PagoDTO $pago): Liquidacion
    {
        return tap($liq)->update([
            'estado'     => 'pagada',
            'fecha_pago' => $pago->fecha,
            'referencia' => $pago->referencia,
        ]);
    }
}

// 3. Binding en AppServiceProvider
$this->app->bind(LiquidacionRepositoryContract::class, EloquentLiquidacionRepository::class);
```

---

## Facade Pattern (Laravel nativo)

**Cuándo usar:** Simplificar el acceso a un subsistema complejo con una interfaz unificada.

```php
// Facade para el módulo de notificaciones multicanal
class NotificadorPH extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return NotificadorService::class;
    }
}

// Uso simple desde cualquier lado
NotificadorPH::alPropietario($propietario)
    ->porEmail()
    ->porPush()
    ->enviar('mora', ['dias' => 15, 'valor' => $deuda]);
```

---

## Decorator Pattern

**Cuándo usar:** Agregar comportamiento a un objeto sin modificar su clase.

```php
// Contexto: agregar logging y caché al repositorio de liquidaciones

// Decorator con logging
class LoggingLiquidacionRepository implements LiquidacionRepositoryContract
{
    public function __construct(
        private LiquidacionRepositoryContract $inner,
        private LoggerInterface $logger,
    ) {}

    public function crear(CrearLiquidacionDTO $dto): Liquidacion
    {
        $this->logger->info('Creando liquidacion', $dto->toArray());
        $resultado = $this->inner->crear($dto);
        $this->logger->info('Liquidacion creada', ['id' => $resultado->id]);
        return $resultado;
    }

    // Delegar el resto al inner...
    public function porPropietario(int $id, FiltroPeriodo $f): Collection
    {
        return $this->inner->porPropietario($id, $f);
    }
}

// Decorator con caché
class CacheLiquidacionRepository implements LiquidacionRepositoryContract
{
    public function __construct(
        private LiquidacionRepositoryContract $inner,
        private CacheInterface $cache,
    ) {}

    public function porPropietario(int $propietarioId, FiltroPeriodo $filtro): Collection
    {
        $key = "liquidaciones.propietario.{$propietarioId}.{$filtro->cacheKey()}";
        return $this->cache->remember($key, now()->addMinutes(10), fn() =>
            $this->inner->porPropietario($propietarioId, $filtro)
        );
    }
}

// Composición en AppServiceProvider — capas como cebolla
$this->app->bind(LiquidacionRepositoryContract::class, function ($app) {
    return new LoggingLiquidacionRepository(
        inner: new CacheLiquidacionRepository(
            inner: $app->make(EloquentLiquidacionRepository::class),
            cache: $app->make(CacheInterface::class),
        ),
        logger: $app->make(LoggerInterface::class),
    );
});
```

---

## Pipeline Pattern (Laravel nativo)

**Cuándo usar:** Flujos de pasos secuenciales donde cada uno puede modificar o detener el proceso.
Laravel trae `Illuminate\Pipeline\Pipeline` incorporado.

```php
// Caso: procesar un pago pasa por múltiples etapas

// Pipes (cada uno es una clase con método handle)
class ValidarSaldoSuficiente
{
    public function handle(ContextoPago $contexto, Closure $next): ContextoPago
    {
        if ($contexto->monto->toPesos() <= 0) {
            throw new MontoInvalidoException();
        }
        return $next($contexto);
    }
}

class VerificarUnidadNoEnListaNegra
{
    public function handle(ContextoPago $contexto, Closure $next): ContextoPago
    {
        if ($contexto->unidad->enListaNegra()) {
            throw new UnidadBloqueadaException();
        }
        return $next($contexto);
    }
}

class EnviarAPasarela
{
    public function handle(ContextoPago $contexto, Closure $next): ContextoPago
    {
        $contexto->resultado = $this->pasarela->cobrar($contexto->monto, $contexto->referencia);
        return $next($contexto);
    }
}

class RegistrarEnCartera
{
    public function handle(ContextoPago $contexto, Closure $next): ContextoPago
    {
        $this->repo->registrarPago($contexto);
        return $next($contexto);
    }
}

// Orquestación limpia en el Action
class ProcesarPagoAction
{
    public function execute(ContextoPago $contexto): ContextoPago
    {
        return app(Pipeline::class)
            ->send($contexto)
            ->through([
                ValidarSaldoSuficiente::class,
                VerificarUnidadNoEnListaNegra::class,
                EnviarAPasarela::class,
                RegistrarEnCartera::class,
            ])
            ->thenReturn();
    }
}
```
