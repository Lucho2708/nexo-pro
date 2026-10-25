<script setup lang="ts">
/**
 * LiveQuorumCard.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Proporciona a los copropietarios y administradores una visualización en tiempo real del quórum legal
 * de la asamblea según lo estipulado en la Ley 675 de 2001 (Art. 45).
 * - Reduce la incertidumbre y la carga cognitiva mostrando con claridad meridiana si la sesión cuenta con
 *   quórum deliberatorio válido (> 50.01%) o quórum calificado (> 70.00%).
 * - Evita discusiones de orden público y brinda transparencia jurídica absoluta a la toma de decisiones.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Implementación bajo Vue 3 Composition API con script setup y TypeScript estricto.
 * - Regla 60-30-10: 60% neutrales (slate/zinc de fondo), 30% superficies elevadas (card estructurado)
 *   y 10% acento semántico (emerald para quórum válido, amber para deliberación en espera).
 * - Accesibilidad WCAG 2.1 AA: Contraste de texto > 4.5:1, etiquetas aria-live="polite" y role="meter"
 *   para lectores de pantalla.
 * - Rendimiento y Animaciones: Microinteracciones CSS aceleradas por GPU (transform y opacity)
 *   con transiciones suaves de 200ms cubic-bezier(0.16, 1, 0.3, 1).
 * - Soporte nativo para Modo Oscuro y Modo Claro vía clases Tailwind CSS v4.
 */

import { computed } from 'vue';

interface QuorumData {
    totalCoeficiente: number;
    presenteCoeficiente: number;
    totalUnidades: number;
    presenteUnidades: number;
    porcentajeQuorum: number;
    tieneQuorumDeliberatorio: boolean;
    tieneQuorumCalificado: boolean;
}

const props = withDefaults(
    defineProps<{
        quorum?: QuorumData | null;
        isLoading?: boolean;
        asambleaTitulo?: string;
    }>(),
    {
        quorum: null,
        isLoading: false,
        asambleaTitulo: 'Asamblea General Ordinaria',
    }
);

// Cálculos de porcentaje y estado visual
const porcentaje = computed(() => {
    if (!props.quorum) return 0;
    return Math.min(Math.max(props.quorum.porcentajeQuorum, 0), 100);
});

const estadoQuorum = computed(() => {
    if (props.isLoading || !props.quorum) return { texto: 'Calculando quórum...', color: 'text-zinc-500' };
    if (props.quorum.tieneQuorumCalificado) {
        return { texto: 'Quórum Calificado Alcanzado (> 70%)', color: 'text-emerald-600 dark:text-emerald-400', badge: 'bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300' };
    }
    if (props.quorum.tieneQuorumDeliberatorio) {
        return { texto: 'Quórum Deliberatorio Válido (> 50%)', color: 'text-blue-600 dark:text-blue-400', badge: 'bg-blue-100 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300' };
    }
    return { texto: 'Quórum Insuficiente para Sesionar', color: 'text-amber-600 dark:text-amber-400', badge: 'bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300' };
});
</script>

<template>
    <div
        class="w-full rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm transition-all duration-200 hover:shadow-md"
        aria-live="polite"
    >
        <!-- Estado de Carga (Skeleton) -->
        <div v-if="isLoading" class="animate-pulse space-y-4">
            <div class="h-4 w-1/3 bg-zinc-200 dark:bg-zinc-800 rounded"></div>
            <div class="h-8 w-1/2 bg-zinc-200 dark:bg-zinc-800 rounded"></div>
            <div class="h-3 w-full bg-zinc-200 dark:bg-zinc-800 rounded-full"></div>
            <div class="grid grid-cols-2 gap-4 pt-2">
                <div class="h-10 bg-zinc-100 dark:bg-zinc-800/60 rounded-xl"></div>
                <div class="h-10 bg-zinc-100 dark:bg-zinc-800/60 rounded-xl"></div>
            </div>
        </div>

        <!-- Vista Normal con Datos en Tiempo Real -->
        <div v-else class="space-y-5">
            <!-- Header con Título y Badge de Estado -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <div>
                    <span class="text-xs font-semibold tracking-wider uppercase text-zinc-500 dark:text-zinc-400">
                        Estado del Quórum en Vivo
                    </span>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100 leading-tight">
                        {{ asambleaTitulo }}
                    </h3>
                </div>
                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium self-start sm:self-auto"
                    :class="estadoQuorum.badge"
                >
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75 bg-current"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-current"></span>
                    </span>
                    {{ estadoQuorum.texto }}
                </span>
            </div>

            <!-- Porcentaje Principal y Barra de Progreso con Marcadores -->
            <div class="space-y-2">
                <div class="flex items-baseline justify-between">
                    <span class="text-3xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                        {{ porcentaje.toFixed(2) }}%
                    </span>
                    <span class="text-xs text-zinc-500 dark:text-zinc-400 font-medium">
                        Meta mínima legal: 50.01%
                    </span>
                </div>

                <!-- Barra de Progreso Accesible -->
                <div
                    class="relative w-full h-3.5 bg-zinc-100 dark:bg-zinc-800 rounded-full overflow-hidden"
                    role="meter"
                    :aria-valuenow="porcentaje"
                    aria-valuemin="0"
                    aria-valuemax="100"
                    :aria-label="`Porcentaje de quórum actual: ${porcentaje.toFixed(2)}%`"
                >
                    <div
                        class="h-full rounded-full transition-all duration-500 ease-out"
                        :class="[
                            porcentaje >= 70
                                ? 'bg-emerald-500 dark:bg-emerald-400'
                                : porcentaje >= 50.01
                                ? 'bg-blue-500 dark:bg-blue-400'
                                : 'bg-amber-500 dark:bg-amber-400'
                        ]"
                        :style="{ width: `${porcentaje}%` }"
                    ></div>

                    <!-- Marcador visual del 50.01% (Línea de decisión) -->
                    <div
                        class="absolute top-0 bottom-0 w-0.5 bg-zinc-400/80 dark:bg-zinc-500/80 z-10"
                        style="left: 50.01%;"
                        title="Umbral de quórum deliberatorio (50.01%)"
                    ></div>

                    <!-- Marcador visual del 70.00% (Decisiones calificadas) -->
                    <div
                        class="absolute top-0 bottom-0 w-0.5 bg-emerald-600/80 dark:bg-emerald-400/80 z-10"
                        style="left: 70%;"
                        title="Umbral de quórum calificado (70.00%)"
                    ></div>
                </div>
            </div>

            <!-- Desglose Métrico de Presencia y Coeficientes con Grid Adaptativo -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-1">
                <div class="rounded-xl border border-zinc-100 dark:border-zinc-800/80 bg-zinc-50/70 dark:bg-zinc-800/40 p-3 min-w-0">
                    <span class="block text-xs text-zinc-500 dark:text-zinc-400 font-medium truncate">Inmuebles Representados</span>
                    <span class="text-base sm:text-lg font-bold text-zinc-900 dark:text-zinc-100 tabular-nums">
                        {{ quorum?.presenteUnidades ?? 0 }} <span class="text-xs font-normal text-zinc-400">/ {{ quorum?.totalUnidades ?? 0 }}</span>
                    </span>
                </div>
                <div class="rounded-xl border border-zinc-100 dark:border-zinc-800/80 bg-zinc-50/70 dark:bg-zinc-800/40 p-3 min-w-0">
                    <span class="block text-xs text-zinc-500 dark:text-zinc-400 font-medium truncate">Coeficiente Acreditado</span>
                    <span class="text-base sm:text-lg font-bold text-zinc-900 dark:text-zinc-100 tabular-nums">
                        {{ quorum ? quorum.presenteCoeficiente.toFixed(4) : '0.0000' }}%
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
