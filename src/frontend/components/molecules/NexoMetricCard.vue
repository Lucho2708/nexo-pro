<script setup lang="ts">
/**
 * NexoMetricCard.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Tarjeta de visualización de métricas de alto impacto para dashboards de administración y asambleas.
 * - Muestra con total transparencia cifras clave (recaudo del mes, cartera vencida, quórum actual, reservas activas).
 * - Incorpora indicadores de tendencia porcentual con código de color semántico inmediato.
 * - Incluye estado de carga integrado con Skeleton para evitar flashes visuales y layout shifts.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Jerarquía visual 60-30-10: Superficie Slate-900 (Dark) / White (Light) con elevación suave.
 * - Microinteracciones en hover de 200ms cubic-bezier con elevación en el eje Y (-2px).
 * - Integración con los átomos NexoBadge y NexoSkeleton.
 */

import NexoBadge from '../atoms/NexoBadge.vue';
import NexoSkeleton from './NexoSkeleton.vue';

interface MetricTrend {
    value: string | number;
    isPositive: boolean;
    label?: string;
}

interface BadgeConfig {
    text: string;
    variant: 'success' | 'warning' | 'error' | 'info' | 'neutral';
}

withDefaults(
    defineProps<{
        title: string;
        value: string | number;
        subtitle?: string;
        trend?: MetricTrend;
        badge?: BadgeConfig;
        isLoading?: boolean;
    }>(),
    {
        subtitle: undefined,
        trend: undefined,
        badge: undefined,
        isLoading: false,
    }
);
</script>

<template>
    <div
        class="w-full rounded-2xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 p-5 shadow-xs transition-all duration-200 hover:shadow-md hover:-translate-y-0.5"
    >
        <!-- Estado Loading con Skeleton -->
        <div v-if="isLoading" class="space-y-3" aria-busy="true">
            <div class="flex items-center justify-between">
                <NexoSkeleton width="45%" height="14px" />
                <NexoSkeleton width="20%" height="20px" rounded="rounded-md" />
            </div>
            <NexoSkeleton width="60%" height="32px" />
            <NexoSkeleton width="35%" height="12px" />
        </div>

        <!-- Vista Normal Poblada -->
        <div v-else class="space-y-3">
            <!-- Header de la Métrica -->
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-semibold tracking-wider uppercase text-slate-500 dark:text-slate-400">
                    {{ title }}
                </span>
                <NexoBadge
                    v-if="badge"
                    :variant="badge.variant"
                    size="sm"
                >
                    {{ badge.text }}
                </NexoBadge>
            </div>

            <!-- Valor Principal -->
            <div class="flex items-baseline gap-3">
                <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-slate-900 dark:text-white leading-none">
                    {{ value }}
                </h3>

                <!-- Indicador de Tendencia -->
                <span
                    v-if="trend"
                    class="inline-flex items-center text-xs font-bold"
                    :class="trend.isPositive ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'"
                >
                    <svg
                        class="w-3.5 h-3.5 mr-0.5"
                        :class="trend.isPositive ? '' : 'rotate-180'"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    {{ trend.value }}
                </span>
            </div>

            <!-- Subtítulo o Texto Descriptivo -->
            <p v-if="subtitle" class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                {{ subtitle }}
            </p>
        </div>
    </div>
</template>
