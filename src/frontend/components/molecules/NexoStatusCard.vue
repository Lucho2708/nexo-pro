<script setup lang="ts">
/**
 * NexoStatusCard.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Tarjeta de estado y métricas adaptativa para los 4 entornos de NEXO PRO.
 * - Ajusta automáticamente su densidad visual y tamaño de padding según el contexto (compacto en Superadmin, amplio en Propietario).
 * - Muestra indicadores de salud de infraestructura, cartera financiera o estado legal de copropiedad.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Molécula compuesta con soporte de tokens para Modo Claro y Modo Oscuro.
 * - Cumplimiento WCAG 2.1 AA con tipografía responsiva y reserva de espacio anti-CLS.
 */

import { computed } from 'vue';
import NexoSkeleton from './NexoSkeleton.vue';
import type { NexoEnvironment } from '../../tokens/environments';

type HealthStatus = 'healthy' | 'warning' | 'critical' | 'neutral';

const props = withDefaults(
    defineProps<{
        environment?: NexoEnvironment;
        title: string;
        value: string | number;
        subtitle?: string;
        status?: HealthStatus;
        badge?: string;
        isLoading?: boolean;
    }>(),
    {
        environment: 'admin',
        subtitle: undefined,
        status: 'neutral',
        badge: undefined,
        isLoading: false,
    }
);

// Densidad adaptativa según el entorno
const densityClasses = computed(() => {
    switch (props.environment) {
        case 'superadmin':
            return 'p-4 rounded-xl'; // Compacta
        case 'owner':
        case 'landing':
            return 'p-6 sm:p-7 rounded-2xl'; // Relajada / espaciosa
        case 'admin':
        default:
            return 'p-5 sm:p-6 rounded-2xl'; // Normal
    }
});

const statusBorderClasses = computed(() => {
    switch (props.status) {
        case 'healthy':
            return 'border-l-4 border-l-emerald-500 border-slate-200/80 dark:border-slate-800';
        case 'warning':
            return 'border-l-4 border-l-amber-500 border-slate-200/80 dark:border-slate-800';
        case 'critical':
            return 'border-l-4 border-l-red-500 border-slate-200/80 dark:border-slate-800';
        case 'neutral':
        default:
            return 'border border-slate-200/80 dark:border-slate-800';
    }
});
</script>

<template>
    <div
        class="w-full bg-white dark:bg-slate-900 shadow-xs transition-all duration-200 hover:shadow-md"
        :class="[densityClasses, statusBorderClasses]"
    >
        <!-- Skeleton Loading -->
        <div v-if="isLoading" class="space-y-2.5">
            <NexoSkeleton width="50%" height="12px" />
            <NexoSkeleton width="70%" height="28px" />
            <NexoSkeleton width="40%" height="10px" />
        </div>

        <!-- Contenido Real -->
        <div v-else class="space-y-2">
            <div class="flex items-center justify-between gap-2">
                <span class="text-xs font-semibold tracking-wider uppercase text-slate-500 dark:text-slate-400 truncate">
                    {{ title }}
                </span>
                <span
                    v-if="badge"
                    class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300"
                >
                    {{ badge }}
                </span>
            </div>

            <div class="flex items-baseline gap-2">
                <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight tabular-nums truncate">
                    {{ value }}
                </span>
            </div>

            <p v-if="subtitle" class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                {{ subtitle }}
            </p>
        </div>
    </div>
</template>
