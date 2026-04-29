<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'NEXO-PRO') }}</title>
        <meta name="description" content="NEXO-PRO: La plataforma líder en gestión inteligente de copropiedades. Automatiza recaudo, reservas, PQRS y asambleas en una sola solución premium.">
        <meta name="keywords" content="gestión copropiedades, propiedad horizontal, software residencial, recaudo cartera, asambleas virtuales">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        {{-- Preload critical font --}}
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" media="print" onload="this.media='all'">
        
        {{-- Material Symbols - Non-blocking --}}
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" media="print" onload="this.media='all'">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" media="print" onload="this.media='all'">

        <noscript>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=swap" rel="stylesheet">
        </noscript>

        <!-- Scripts -->
        @php echo app(Tighten\Ziggy\BladeRouteGenerator::class)->generate(); @endphp
        @vite(['resources/js/app.js'])
        @inertiaHead

        <style>
            /* Optimization: Pre-loading CSS and GPU Transitions */
            .material-symbols-rounded, .material-symbols-outlined {
                min-width: 24px;
                min-height: 24px;
                display: inline-block;
            }
            * {
                box-sizing: border-box;
                transition-property: transform, opacity !important; /* Fix Non-composited animations */
            }
        </style>
    </head>
    <body class="bg-surface text-on-surface antialiased font-sans">
        @inertia
    </body>
</html>
