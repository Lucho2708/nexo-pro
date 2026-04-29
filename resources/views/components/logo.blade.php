@props([
    'width' => '100px',
    'height' => '100px',
])

@once
    @push('head')
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@800&display=swap" rel="stylesheet" />
    @endpush
@endonce

<style>
    /* === ESTILOS DEL COMPONENTE LOGO === */
    .nexo-logo-wrapper {
        display: inline-flex;
        align-items: center;
        gap: 0.5em; /* Usamos em para que el espacio escale con el tamaño de fuente */
        padding: 0.5em 1em;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        font-family: "Montserrat", sans-serif;
        text-decoration: none;
    }

    .nexo-logo-wrapper:hover {
        transform: scale(1.05);
        background-color: rgba(248, 249, 250, 0.8);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
    }

    .nexo-hexagon-container {
        /* Dimensiones pasadas por props */
        width: {{ $width }};
        height: {{ $height }};
        position: relative;
        animation: nexo-float 4s ease-in-out infinite;
    }

    .nexo-hexagon-container svg {
        width: 100%;
        height: 100%;
        overflow: visible;
        filter: drop-shadow(0px 10px 15px rgba(0, 0, 0, 0.15));
        transition: filter 0.4s ease;
    }

    .nexo-logo-wrapper:hover .nexo-hexagon-container svg {
        filter: drop-shadow(0px 15px 25px rgba(255, 0, 255, 0.3))
                drop-shadow(0px 0px 20px rgba(0, 240, 255, 0.3));
    }

    /* Animaciones */
    .nexo-n-path {
        stroke-dasharray: 450;
        stroke-dashoffset: 450;
        animation: nexo-drawPath 1.8s cubic-bezier(0.4, 0, 0.2, 1) forwards;
        animation-delay: 0.3s;
    }

    .nexo-panel {
        opacity: 0;
        transform: scale(0.8) translateY(10px);
        transform-origin: center;
        animation: nexo-popIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }
    .nexo-panel-left { animation-delay: 0.1s; }
    .nexo-panel-right { animation-delay: 0.2s; }
    .nexo-panel-bot-left { animation-delay: 0.3s; }
    .nexo-panel-bot-right { animation-delay: 0.4s; }

    .nexo-framework {
        stroke-dasharray: 800;
        stroke-dashoffset: 800;
        animation: nexo-drawPath 1.5s ease-out forwards;
    }

    .nexo-node {
        opacity: 0;
        transform: scale(0);
        transform-box: fill-box;
        transform-origin: center;
        animation: nexo-popInNode 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.5) forwards;
    }

    .nexo-inner-glow {
        opacity: 0;
        animation: nexo-fadeIn 1s ease forwards;
        animation-delay: 1s;
    }

    /* El tamaño del texto se basa en el alto del logo para mantener proporción */
    .nexo-text-container {
        font-size: calc({{ $height }} * 0.4);
        font-weight: 800;
        letter-spacing: -1px;
        display: flex;
        align-items: center;
    }

    .nexo-text-container span {
        display: inline-block;
        opacity: 0;
        transform: translateX(-30px);
        animation: nexo-slideText 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
    }

    .nexo-t-nexo { color: var(--color-primary); animation-delay: 0.6s; transition: color 0.3s ease; }
    .dark .nexo-t-nexo { color: var(--color-primary); }

    .nexo-t-dash { color: #d11275; margin: 0 0.1em; animation-delay: 0.8s; }
    .nexo-t-pro {
        background: linear-gradient(90deg, #ff00ff 0%, #8300ff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
        animation-delay: 1s;
    }

    /* Ajuste de trazo para el marco en modo oscuro */
    .nexo-framework {
        stroke: #1b2336;
        transition: stroke 0.3s ease;
    }
    .dark .nexo-framework {
        stroke: #334155;
    }

    .nexo-n-inner {
        stroke: #1b2336;
        transition: stroke 0.3s ease;
    }
    .dark .nexo-n-inner {
        stroke: #101827;
    }

    .nexo-node-bg {
        fill: #1b2336;
        transition: fill 0.3s ease;
    }
    .dark .nexo-node-bg {
        fill: #101827;
    }

    @keyframes nexo-float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-5px); } }
    @keyframes nexo-drawPath { to { stroke-dashoffset: 0; } }
    @keyframes nexo-popIn { 0% { opacity: 0; transform: scale(0.8) translateY(10px); } 100% { opacity: 1; transform: scale(1) translateY(0); } }
    @keyframes nexo-popInNode { 0% { opacity: 0; transform: scale(0); } 100% { opacity: 1; transform: scale(1); } }
    @keyframes nexo-slideText { to { opacity: 1; transform: translateX(0); } }
    @keyframes nexo-fadeIn { to { opacity: 0.7; } }

    .nexo-glowing-n { animation: nexo-pulseGlow 2.5s infinite alternate; }

    @keyframes nexo-pulseGlow {
        0% { filter: drop-shadow(0 0 3px rgba(255, 170, 0, 0.4)); }
        100% { filter: drop-shadow(0 0 10px rgba(255, 170, 0, 0.8)) drop-shadow(0 0 20px rgba(255, 0, 128, 0.4)); }
    }
</style>

<div {{ $attributes->merge(['class' => 'nexo-logo-wrapper']) }}>
    <div class="nexo-hexagon-container">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <linearGradient id="gradLeft" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#00ffff" />
                    <stop offset="100%" stop-color="#4a00e0" />
                </linearGradient>
                <linearGradient id="gradRight" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#ff00ff" />
                    <stop offset="100%" stop-color="#4a00e0" />
                </linearGradient>
                <linearGradient id="gradBot" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" stop-color="#2a1b54" />
                    <stop offset="100%" stop-color="#140a2e" />
                </linearGradient>
            </defs>

            <polygon class="nexo-panel nexo-panel-left" points="100,10 20,55 20,145 100,150" fill="url(#gradLeft)" />
            <polygon class="nexo-panel nexo-panel-right" points="100,10 180,55 180,145 100,150" fill="url(#gradRight)" />
            <polygon class="nexo-panel nexo-panel-bot-left" points="20,145 100,190 100,150" fill="url(#gradBot)" />
            <polygon class="nexo-panel nexo-panel-bot-right" points="180,145 100,190 100,150" fill="url(#gradBot)" />

            <polygon class="nexo-inner-glow" points="95,22 28,60 28,139 95,143" fill="none" stroke="#00ffff" stroke-width="2.5" />
            <polygon class="nexo-inner-glow" points="105,22 172,60 172,139 105,143" fill="none" stroke="#ff00ff" stroke-width="2.5" />

            <g class="nexo-framework" stroke-width="9" fill="none" stroke-linejoin="round" stroke-linecap="round">
                <path d="M 100,10 L 180,55 L 180,145 L 100,190 L 20,145 L 20,55 Z" />
                <path d="M 100,10 L 100,150" />
                <path d="M 100,150 L 20,145" />
                <path d="M 100,150 L 180,145" />
                <path d="M 100,150 L 100,190" />
            </g>

            <g class="nexo-glowing-n">
                <path class="nexo-n-path" d="M 55,135 L 55,65 L 100,100 L 145,135 L 145,65" fill="none" stroke="#ffaa00" stroke-width="14" stroke-linecap="round" stroke-linejoin="round" />
                <path class="nexo-n-path nexo-n-inner" d="M 55,135 L 55,65 L 100,100 L 145,135 L 145,65" fill="none" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />

                <g class="nexo-node nexo-n-in-1" style="animation-delay: 1.2s;">
                    <circle cx="55" cy="135" r="13" fill="#ffaa00" />
                    <circle cx="55" cy="135" r="9" class="nexo-node-bg" />
                    <circle cx="55" cy="135" r="5" fill="#00ff66" />
                </g>
                <g class="nexo-node nexo-n-in-2" style="animation-delay: 1.3s;">
                    <circle cx="55" cy="65" r="13" fill="#ffaa00" />
                    <circle cx="55" cy="65" r="9" class="nexo-node-bg" />
                    <circle cx="55" cy="65" r="5" fill="#ff00ff" />
                </g>
                <g class="nexo-node nexo-n-in-3" style="animation-delay: 1.4s;">
                    <circle cx="100" cy="100" r="13" fill="#ffaa00" />
                    <circle cx="100" cy="100" r="9" class="nexo-node-bg" />
                    <circle cx="100" cy="100" r="5" fill="#b000ff" />
                </g>
                <g class="nexo-node nexo-n-in-4" style="animation-delay: 1.5s;">
                    <circle cx="145" cy="135" r="13" fill="#ffaa00" />
                    <circle cx="145" cy="135" r="9" class="nexo-node-bg" />
                    <circle cx="145" cy="135" r="5" fill="#ff00ff" />
                </g>
                <g class="nexo-node nexo-n-in-5" style="animation-delay: 1.6s;">
                    <circle cx="145" cy="65" r="13" fill="#ffaa00" />
                    <circle cx="145" cy="65" r="9" class="nexo-node-bg" />
                    <circle cx="145" cy="65" r="5" fill="#00ffff" />
                </g>
            </g>

            <!-- Nodos exteriores con delays individuales -->
            <g class="nexo-node" style="animation-delay: 0.5s;">
                <circle cx="100" cy="10" r="10" class="nexo-node-bg" />
                <circle cx="100" cy="10" r="5.5" fill="#00ffff" />
            </g>
            <g class="nexo-node" style="animation-delay: 0.6s;">
                <circle cx="180" cy="55" r="10" class="nexo-node-bg" />
                <circle cx="180" cy="55" r="5.5" fill="#00ff66" />
            </g>
            <g class="nexo-node" style="animation-delay: 0.7s;">
                <circle cx="180" cy="145" r="10" class="nexo-node-bg" />
                <circle cx="180" cy="145" r="5.5" fill="#00aaff" />
            </g>
            <g class="nexo-node" style="animation-delay: 0.8s;">
                <circle cx="100" cy="190" r="10" class="nexo-node-bg" />
                <circle cx="100" cy="190" r="5.5" fill="#00ff66" />
            </g>
            <g class="nexo-node" style="animation-delay: 0.9s;">
                <circle cx="20" cy="145" r="10" class="nexo-node-bg" />
                <circle cx="20" cy="145" r="5.5" fill="#ff00ff" />
            </g>
            <g class="nexo-node" style="animation-delay: 1.0s;">
                <circle cx="20" cy="55" r="10" class="nexo-node-bg" />
                <circle cx="20" cy="55" r="5.5" fill="#ff00ff" />
            </g>
        </svg>
    </div>

    <div class="nexo-text-container">
        <span class="nexo-t-nexo">NEXO</span>
        <span class="nexo-t-dash">-</span>
        <span class="nexo-t-pro">PRO</span>
    </div>
</div>
