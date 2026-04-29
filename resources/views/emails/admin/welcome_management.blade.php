<x-mail::message>
# ¡Bienvenido a NEXO-PRO!

Hola,

Se te ha asignado formalmente la administración del conjunto **{{ $copropiedad->nombre }}** en nuestra plataforma.

Desde ahora tienes acceso total a:
- Gestión de unidades y residentes.
- Control de PQRS y comunicaciones.
- Reservas de zonas comunes.
- Reportes financieros y de cartera.

<x-mail::button :url="route('dashboard')">
Acceder al Dashboard
</x-mail::button>

Si tienes alguna duda sobre el estado actual del conjunto, recuerda que toda la información histórica se encuentra disponible en tu panel.

Gracias,<br>
El equipo de {{ config('app.name') }}
</x-mail::message>
