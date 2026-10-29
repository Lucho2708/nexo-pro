<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light notranslate" translate="no">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google" content="notranslate">

        <title inertia>{{ config('app.name', 'NEXO-PRO') }}</title>
        <meta name="description" content="NEXO-PRO: La plataforma líder en gestión inteligente de copropiedades. Automatiza recaudo, reservas, PQRS y asambleas en una sola solución premium.">
        <meta name="keywords" content="gestión copropiedades, propiedad horizontal, software residencial, recaudo cartera, asambleas virtuales">

        <!-- Fonts & Icons: Unified Enterprise Typography (Manrope) & Material Symbols -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        
        {{-- Preload primary brand typography (Manrope) with display=swap --}}
        <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap">
        
        {{-- Material Symbols - High priority & display=block to prevent ligature text flash and unwanted browser translation --}}
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&display=block">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=block">

        <noscript>
            <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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

            /* High Performance 60/120 FPS Mobile Optimization (No universal transitions) */
            *, ::before, ::after {
                box-sizing: border-box;
            }
            a, button, input, select, textarea {
                transition-property: color, background-color, border-color, opacity, box-shadow, transform;
                transition-duration: 150ms;
                transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            }

            /* NEXO-PRO Native Logo Preloader Animations (GPU Accelerated) */
            @keyframes nexo-splash-orbit {
                0% { transform: scale(0.95); opacity: 0.85; filter: drop-shadow(0 0 12px rgba(37,99,235,0.35)); }
                50% { transform: scale(1.05); opacity: 1; filter: drop-shadow(0 0 28px rgba(37,99,235,0.75)); }
                100% { transform: scale(0.95); opacity: 0.85; filter: drop-shadow(0 0 12px rgba(37,99,235,0.35)); }
            }
            @keyframes nexo-splash-dash {
                0% { stroke-dashoffset: 450; opacity: 0.4; }
                50% { stroke-dashoffset: 0; opacity: 1; }
                100% { stroke-dashoffset: -450; opacity: 0.4; }
            }
            .nexo-splash-hexagon {
                animation: nexo-splash-orbit 2.6s ease-in-out infinite;
                will-change: transform, opacity;
            }
            .nexo-splash-n-pulse {
                stroke-dasharray: 450;
                animation: nexo-splash-dash 2.8s cubic-bezier(0.4, 0, 0.2, 1) infinite;
                will-change: stroke-dashoffset;
            }
            #nexo-app-preloader.fade-out {
                opacity: 0 !important;
                visibility: hidden !important;
                pointer-events: none !important;
            }
        </style>
    </head>
    <body class="bg-surface text-on-surface antialiased font-sans">
        <!-- NEXO-PRO Enterprise Logo Preloader (Zero-JS Native Splash) -->
        <div id="nexo-app-preloader" aria-hidden="true" style="
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background-color: #06080e;
            transition: opacity 0.35s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.35s ease;
            will-change: opacity, transform;
            pointer-events: auto;
        ">
            <div style="display: flex; flex-direction: column; align-items: center; gap: 18px;">
                <div class="nexo-splash-hexagon" style="width: 82px; height: 82px; position: relative;">
                    <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" style="width: 100%; height: 100%; overflow: visible;">
                        <defs>
                            <linearGradient id="splashGradLeft" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#00ffff" />
                                <stop offset="100%" stop-color="#2563eb" />
                            </linearGradient>
                            <linearGradient id="splashGradRight" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#818cf8" />
                                <stop offset="100%" stop-color="#4f46e5" />
                            </linearGradient>
                            <linearGradient id="splashGradBot" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="#1e1b4b" />
                                <stop offset="100%" stop-color="#0f172a" />
                            </linearGradient>
                        </defs>

                        <!-- Hexagon Facets -->
                        <polygon points="100,10 20,55 20,145 100,150" fill="url(#splashGradLeft)" opacity="0.85" />
                        <polygon points="100,10 180,55 180,145 100,150" fill="url(#splashGradRight)" opacity="0.85" />
                        <polygon points="20,145 100,190 100,150" fill="url(#splashGradBot)" />
                        <polygon points="180,145 100,190 100,150" fill="url(#splashGradBot)" />

                        <!-- Isometric Grid Wireframe -->
                        <g stroke="#ffffff" stroke-opacity="0.25" stroke-width="4" fill="none" stroke-linejoin="round" stroke-linecap="round">
                            <path d="M 100,10 L 180,55 L 180,145 L 100,190 L 20,145 L 20,55 Z" />
                            <path d="M 100,10 L 100,150" />
                            <path d="M 100,150 L 20,145" />
                            <path d="M 100,150 L 180,145" />
                            <path d="M 100,150 L 100,190" />
                        </g>

                        <!-- Glowing N Path -->
                        <path class="nexo-splash-n-pulse" d="M 55,135 L 55,65 L 100,100 L 145,135 L 145,65" fill="none" stroke="#f59e0b" stroke-width="12" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M 55,135 L 55,65 L 100,100 L 145,135 L 145,65" fill="none" stroke="#ffffff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />

                        <!-- Glowing Circuit Nodes -->
                        <circle cx="55" cy="135" r="7" fill="#10b981" stroke="#ffffff" stroke-width="2" />
                        <circle cx="55" cy="65" r="7" fill="#3b82f6" stroke="#ffffff" stroke-width="2" />
                        <circle cx="100" cy="100" r="8" fill="#f59e0b" stroke="#ffffff" stroke-width="2" />
                        <circle cx="145" cy="135" r="7" fill="#3b82f6" stroke="#ffffff" stroke-width="2" />
                        <circle cx="145" cy="65" r="7" fill="#10b981" stroke="#ffffff" stroke-width="2" />
                    </svg>
                </div>
                <div style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
                    <span style="font-family: 'Manrope', system-ui, sans-serif; font-size: 13px; font-weight: 800; letter-spacing: 0.18em; color: #f8fafc; text-transform: uppercase;">
                        NEXO<span style="color: #3b82f6;"> PRO</span>
                    </span>
                    <span style="font-family: 'Manrope', system-ui, sans-serif; font-size: 10px; font-weight: 600; letter-spacing: 0.1em; color: #94a3b8; text-transform: uppercase;">
                        Cargando entorno seguro...
                    </span>
                </div>
            </div>
        </div>
        @inertia
    </body>
</html>
