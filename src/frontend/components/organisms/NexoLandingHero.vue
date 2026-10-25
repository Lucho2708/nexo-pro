<script setup lang="ts">
/**
 * NexoLandingHero.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Organismo de conversión principal para la Landing Page de NEXO PRO.
 * - Maximiza la tasa de captura de leads cualificados (administradores y miembros de consejo de administración).
 * - Comunica de inmediato la propuesta de valor: legalidad garantizada (Ley 675), asambleas sin caídas y recaudo automatizado.
 * - Incluye prueba social cuantificable para reducir la aversión al cambio de software tradicional.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Jerarquía visual 60-30-10 con gradientes sutiles de acento (#2563EB a #4F46E5).
 * - Maquetación Mobile-First con tipografía fluida clamp() y layout adaptativo (columna en móvil, 2 columnas en desktop).
 * - Cero saltos de diseño (CLS = 0) y contraste WCAG 2.1 AA en Modo Claro y Oscuro.
 */

import NexoButton from '../atoms/NexoButton.vue';
import NexoEnvironmentBadge from '../atoms/NexoEnvironmentBadge.vue';

withDefaults(
    defineProps<{
        title?: string;
        highlightText?: string;
        subtitle?: string;
        activePropertiesCount?: number;
    }>(),
    {
        title: 'El Sistema Operativo Definitivo para',
        highlightText: 'Propiedad Horizontal',
        subtitle: 'Asambleas virtuales inimpugnables con quórum en tiempo real, recaudo automatizado Wompi y contabilidad inteligente para copropiedades modernas.',
        activePropertiesCount: 450,
    }
);

const emit = defineEmits<{
    (e: 'request-demo'): void;
    (e: 'view-pricing'): void;
}>();
</script>

<template>
    <section class="relative w-full overflow-hidden py-12 sm:py-20 lg:py-24 text-left">
        <!-- Resplandor decorativo de fondo -->
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] sm:w-[900px] h-[300px] sm:h-[450px] bg-gradient-to-tr from-blue-600/10 via-indigo-600/10 to-transparent blur-[100px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-center">
                <!-- Columna de Texto y Conversión (7 cols en Desktop) -->
                <div class="lg:col-span-7 space-y-6 sm:space-y-8">
                    <!-- Badge Semántico Superior -->
                    <div class="flex items-center gap-3">
                        <NexoEnvironmentBadge environment="landing" label="NEXO PRO Enterprise 2026" />
                        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            {{ activePropertiesCount }}+ Edificios Activos
                        </span>
                    </div>

                    <!-- Titular de Alta Conversión con Tipografía Fluida -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white tracking-tight leading-[1.1]">
                        {{ title }}
                        <span class="block bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-500 bg-clip-text text-transparent">
                            {{ highlightText }}
                        </span>
                    </h1>

                    <!-- Bajada Descriptiva de Valor -->
                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl">
                        {{ subtitle }}
                    </p>

                    <!-- Botones de Acción (CTAs Mobile-First) -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3.5 pt-2">
                        <NexoButton
                            variant="primary"
                            size="lg"
                            class="shadow-lg shadow-blue-500/25 !min-h-[56px]"
                            @click="emit('request-demo')"
                        >
                            <svg class="w-5 h-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                            </svg>
                            Agendar Demostración VIP
                        </NexoButton>

                        <NexoButton
                            variant="outline"
                            size="lg"
                            class="!min-h-[56px]"
                            @click="emit('view-pricing')"
                        >
                            Explorar Planes y Precios
                        </NexoButton>
                    </div>

                    <!-- Micro-Prueba Social y Cumplimiento Legal -->
                    <div class="pt-4 flex flex-wrap items-center gap-6 text-xs text-slate-500 dark:text-slate-400 font-medium">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Conforme a Ley 675 de 2001
                        </span>
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            Certificación SSL y Cifrado SHA-256
                        </span>
                    </div>
                </div>

                <!-- Columna Gráfica / Teaser de Dashboard (5 cols en Desktop) -->
                <div class="lg:col-span-5 relative">
                    <div class="rounded-3xl border border-slate-200/80 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md p-6 shadow-2xl space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                                <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                            </div>
                            <span class="text-[11px] font-mono text-slate-400">nexo-pro.app/demo</span>
                        </div>

                        <!-- Mini Card de Quórum en Vivo -->
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 space-y-2">
                            <div class="flex justify-between items-center text-xs">
                                <span class="font-bold text-slate-700 dark:text-slate-200">Asamblea Ordinaria Anual</span>
                                <span class="text-blue-600 dark:text-blue-400 font-extrabold">76.45% Quórum</span>
                            </div>
                            <div class="w-full h-2 rounded-full bg-slate-200 dark:bg-slate-700 overflow-hidden">
                                <div class="h-full bg-blue-600 rounded-full w-[76.45%]"></div>
                            </div>
                            <span class="text-[10px] text-slate-500 font-medium">Quórum Calificado Acreditado (Ley 675)</span>
                        </div>

                        <!-- Mini Card de Recaudo Wompi -->
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-100 dark:border-slate-800 flex justify-between items-center">
                            <div>
                                <span class="block text-[11px] text-slate-500 font-medium">Recaudo Automatizado</span>
                                <span class="text-base font-extrabold text-slate-900 dark:text-white">$ 48.250.000 COP</span>
                            </div>
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300">
                                98.2% Al Día
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
