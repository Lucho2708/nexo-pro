<x-mail::message>
# Nueva PQRS Radicada

Se ha recibido una nueva solicitud en la plataforma **Nexo-Pro**.

**Detalles de la Radicación:**
- **Conjunto:** {{ $pqrs->unidad->copropiedad->nombre }}
- **Prioridad:** {{ strtoupper($pqrs->prioridad) }}
- **Tipo:** {{ ucfirst(str_replace('_', ' ', $pqrs->tipo)) }}
- **Radicado por:** {{ $pqrs->user->name }}
- **Unidad:** {{ $pqrs->unidad->torre }} {{ $pqrs->unidad->nombre }}

**Asunto:** {{ $pqrs->asunto }}

**Mensaje:**
{{ $pqrs->mensaje }}

<x-mail::button :url="route('dashboard')">
Ver en Dashboard
</x-mail::button>

Gracias,<br>
{{ config('app.name') }}
</x-mail::message>
