<script setup lang="ts">
/**
 * NexoBadge.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Indicador semántico compacto para estados de cuenta, estado de quórum de asamblea, roles de usuario y alertas.
 * - Reduce la incertidumbre del residente proporcionando feedback visual instantáneo (ej: "Al Día", "Mora", "Quórum Válido").
 * - Soporta un indicador pulsante (Dot) para eventos en vivo de alta relevancia jurídica en tiempo real.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Implementación con Vue 3 Composition API y TypeScript.
 * - Garantiza cumplimiento estricto de la norma WCAG 2.1 AA en Modo Claro y Modo Oscuro (ratio de contraste > 4.5:1).
 * - Utiliza las variables y clases semánticas de la paleta NEXO PRO.
 */

import { computed } from 'vue';

type BadgeVariant = 'success' | 'warning' | 'error' | 'info' | 'neutral';
type BadgeSize = 'sm' | 'md';

const props = withDefaults(
    defineProps<{
        variant?: BadgeVariant;
        size?: BadgeSize;
        dot?: boolean;
        pulse?: boolean;
    }>(),
    {
        variant: 'neutral',
        size: 'md',
        dot: false,
        pulse: false,
    }
);

const variantClasses = computed(() => {
    switch (props.variant) {
        case 'success':
            return 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800/80';
        case 'warning':
            return 'bg-amber-50 text-amber-900 border-amber-200 dark:bg-amber-950/60 dark:text-amber-300 dark:border-amber-800/80';
        case 'error':
            return 'bg-red-50 text-red-800 border-red-200 dark:bg-red-950/60 dark:text-red-300 dark:border-red-800/80';
        case 'info':
            return 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-800/80';
        case 'neutral':
        default:
            return 'bg-slate-100 text-slate-800 border-slate-200 dark:bg-slate-800/80 dark:text-slate-200 dark:border-slate-700';
    }
});

const dotColorClasses = computed(() => {
    switch (props.variant) {
        case 'success':
            return 'bg-emerald-500';
        case 'warning':
            return 'bg-amber-500';
        case 'error':
            return 'bg-red-500';
        case 'info':
            return 'bg-blue-500';
        case 'neutral':
        default:
            return 'bg-slate-400';
    }
});

const sizeClasses = computed(() => {
    switch (props.size) {
        case 'sm':
            return 'px-2 py-0.5 text-[11px] font-semibold rounded-md gap-1';
        case 'md':
        default:
            return 'px-2.5 py-1 text-xs font-bold rounded-lg gap-1.5';
    }
});
</script>

<template>
    <span
        class="inline-flex items-center justify-center border font-sans tracking-wide select-none transition-colors duration-150"
        :class="[variantClasses, sizeClasses]"
    >
        <!-- Indicador circular tipo Dot -->
        <span v-if="dot" class="relative flex h-2 w-2 shrink-0">
            <span
                v-if="pulse"
                class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75"
                :class="dotColorClasses"
            ></span>
            <span
                class="relative inline-flex rounded-full h-2 w-2"
                :class="dotColorClasses"
            ></span>
        </span>

        <slot />
    </span>
</template>
