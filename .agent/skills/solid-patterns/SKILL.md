---
name: solid-patterns
description: >
  Experto en principios SOLID y patrones de diseño aplicados a Laravel (PHP) y Vue.js.
  Usa este skill SIEMPRE que el usuario mencione: SOLID, SRP, OCP, LSP, ISP, DIP,
  principios de diseño, patrones de diseño, refactorizar código, arquitectura de software,
  Repository Pattern, Service Layer, Factory, Strategy, Observer, Decorator, Command,
  Pipeline, DTO, Value Object, Action, acoplamiento, cohesión, código limpio, clean code,
  deuda técnica, o cuando diga "cómo organizo esto", "esto huele mal", "cómo estructuro
  mi código", "el controlador está muy gordo", "cómo desacople esto", o cuando el usuario
  muestre código con lógica de negocio mezclada en controladores, modelos o componentes Vue.
  Aplica tanto para diseñar código nuevo como para refactorizar código existente.
---

# SOLID + Patrones de Diseño — Laravel 13 + Vue.js 3

Eres un arquitecto de software experto. Tu misión es guiar al usuario a escribir código
mantenible, extensible y desacoplado usando principios SOLID y patrones de diseño,
con ejemplos concretos del proyecto NEXO-PRO (administración de propiedad horizontal).

---

## Menú de referencia rápida

Para detalles profundos de cada tema, leer el archivo correspondiente:

| Tema | Archivo |
|---|---|
| Principios SOLID (los 5) | `references/solid.md` |
| Patrones Creacionales | `references/creational-patterns.md` |
| Patrones Estructurales | `references/structural-patterns.md` |
| Patrones de Comportamiento | `references/behavioral-patterns.md` |
| Arquitectura Laravel (Services, Actions, DTOs) | `references/laravel-architecture.md` |
| Patrones en Vue.js 3 | `references/vue-patterns.md` |

Lee **solo** el archivo relevante para la consulta. No los cargues todos.

---

## Regla de oro: diagnóstico primero

Antes de proponer un patrón, identificar el problema real:

| Síntoma en el código | Diagnóstico | Solución |
|---|---|---|
| Controlador con >50 líneas de lógica | Violación SRP | Extraer a Service/Action |
| `if` anidados según tipo de pago | Violación OCP | Strategy Pattern |
| Método que hace demasiado | Violación SRP | Decomposición en métodos/clases |
| `new ClaseConcreto()` dentro de otra clase | Violación DIP | Inyección de dependencias |
| Interfaz con métodos que la clase no usa | Violación ISP | Segregar interfaces |
| Lógica de negocio en el Modelo Eloquent | Violación SRP | Service Layer |
| Componente Vue con >200 líneas | Violación SRP | Extraer composables y subcomponentes |
| `$request->all()` en el controlador | Mass Assignment + SRP | Form Request + DTO |

---

## Arquitectura recomendada para NEXO-PRO

```
app/
├── Http/
│   ├── Controllers/        # Solo recibe request, llama Action/Service, retorna respuesta
│   ├── Requests/           # Validación y autorización
│   └── Resources/          # Transformación de respuesta (API Resources)
├── Actions/                # Una clase = Una acción de negocio (patrón Action)
├── Services/               # Orquestación de múltiples acciones
├── Repositories/           # Abstracción de acceso a datos
├── DTOs/                   # Objetos de transferencia de datos (inmutables)
├── ValueObjects/           # Lógica de dominio encapsulada (Dinero, Coeficiente)
├── Contracts/              # Interfaces (Dependency Inversion)
├── Events/                 # Eventos de dominio
├── Listeners/              # Reacción a eventos
└── Pipelines/              # Flujos encadenados (Pipeline Pattern)
```

---

## Ejemplo integrador: flujo de recaudo

Así se ve el código cuando aplica SOLID + Patrones correctamente:

```php
// ✅ Controlador delgado — solo orquesta
class LiquidacionController extends Controller
{
    public function store(GenerarLiquidacionRequest $request, GenerarLiquidacionAction $action)
    {
        $dto = GenerarLiquidacionDTO::fromRequest($request);
        $resultado = $action->execute($dto);
        return LiquidacionResource::collection($resultado);
    }
}

// ✅ Action — una responsabilidad
class GenerarLiquidacionAction
{
    public function __construct(
        private readonly LiquidacionRepositoryContract $repo,
        private readonly CalculadorValorContract $calculador,
    ) {}

    public function execute(GenerarLiquidacionDTO $dto): Collection
    {
        return $this->repo
            ->unidadesActivas($dto->copropiedadId)
            ->map(fn(Unidad $u) => $this->repo->crear(
                unidad: $u,
                valor: $this->calculador->calcular($u->coeficiente, $dto->valorBase),
                periodo: $dto->periodo,
            ));
    }
}
```

---

## Anti-patrones más comunes — detectar y corregir

### ❌ Controlador gordo (Fat Controller)

```php
// MAL: toda la lógica en el controlador
public function generarLiquidaciones(Request $request)
{
    $unidades = Unidad::where('copropiedad_id', $request->copropiedad_id)
        ->where('estado', 'activa')->get();

    foreach ($unidades as $unidad) {
        $valor = $request->valor_base * ($unidad->coeficiente / 100);
        Liquidacion::create([...]);
        Mail::to($unidad->propietario->email)->send(new LiquidacionMail($unidad));
    }
    return response()->json(['ok' => true]);
}
```

```php
// BIEN: controlador delgado
public function generarLiquidaciones(GenerarLiquidacionRequest $request, GenerarLiquidacionAction $action)
{
    $resultado = $action->execute(GenerarLiquidacionDTO::fromRequest($request));
    return LiquidacionResource::collection($resultado);
}
```

### ❌ Modelo gordo (Fat Model)

```php
// MAL: lógica de negocio en el modelo
class Liquidacion extends Model
{
    public function calcularInteresMora(): float
    {
        // 30 líneas de lógica de cálculo de mora
    }

    public function enviarNotificacion(): void
    {
        // lógica de emails
    }
}
```

```php
// BIEN: lógica en ValueObject y Service
class CalculadorInteresMora  // ValueObject o Service
{
    public function calcular(Liquidacion $liq, Carbon $fecha): Money
    {
        $diasMora = $liq->fecha_vencimiento->diffInDays($fecha);
        return new Money($liq->valor * (0.005 * $diasMora));
    }
}
```

---

## Guía de selección de patrón

Responder estas preguntas para elegir el patrón correcto:

1. **¿Necesito crear objetos complejos?** → Factory / Builder
2. **¿El algoritmo cambia según condición?** → Strategy
3. **¿Necesito reaccionar a eventos sin acoplar?** → Observer / Events
4. **¿Tengo pasos secuenciales con posibilidad de interrumpir?** → Pipeline / Chain of Responsibility
5. **¿Necesito desacoplar acceso a datos?** → Repository
6. **¿Quiero agregar comportamiento sin modificar clase?** → Decorator
7. **¿Tengo una operación compleja de una sola vez?** → Command / Action
8. **¿Necesito una sola instancia global?** → Singleton (solo para servicios stateless)
9. **¿Quiero simplificar un subsistema complejo?** → Facade
10. **¿Necesito transferir datos entre capas sin exponer el modelo?** → DTO
