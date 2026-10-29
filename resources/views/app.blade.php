<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light notranslate" translate="no">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google" content="notranslate">

        <title inertia>{{ config('app.name', 'NEXO-PRO') }}</title>
        <meta name="description" content="NEXO-PRO: La plataforma líder en gestión inteligente de copropiedades. Automatiza recaudo, reservas, PQRS y asambleas en una sola solución premium.">
        <meta name="keywords" content="gestión copropiedades, propiedad horizontal, software residencial, recaudo cartera, asambleas virtuales">

        <!-- Fonts & Icons -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        {{-- Preload critical font --}}
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap">
        
        {{-- Material Symbols - High priority & display=block to prevent ligature text flash and unwanted browser translation --}}
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=block">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block">

        <noscript>
            <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=block" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block" rel="stylesheet">
        </noscript>

        <!-- Scripts -->
        @php echo app(Tighten\Ziggy\BladeRouteGenerator::class)->generate(); @endphp
        @vite(['resources/js/app.js'])
        @inertiaHead

        <style>
            /* Optimization & Defensive Icon Protection against Google Translate & Ligature Explosion */
            .material-symbols-rounded, .material-symbols-outlined, [class*="material-symbols-"] {
                font-family: 'Material Symbols Rounded', 'Material Symbols Outlined' !important;
                min-width: 24px;
                min-height: 24px;
                display: inline-block;
                vertical-align: middle;
                text-transform: none !important;
                letter-spacing: normal !important;
                word-wrap: normal;
                white-space: nowrap;
                direction: ltr;
                -webkit-font-smoothing: antialiased;
                text-rendering: optimizeLegibility;
                font-feature-settings: 'liga' 1;
                user-select: none;
                -webkit-user-select: none;
                overflow: hidden;
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
