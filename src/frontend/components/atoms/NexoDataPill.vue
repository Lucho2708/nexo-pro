<script setup lang="ts">
/**
 * NexoDataPill.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Píldora de datos ultra-compacta diseñada para vistas de alta densidad de información (Superadmin y Administrador).
 * - Permite examinar rápidamente métricas técnicas (versión de software, latencia en ms, estado de licencia, saldo en mora).
 *
 * [ASPECTO TÉCNICO / UI]
 * - Componente atómico de alta densidad con tipografía tabular opcional (font-mono).
 * - Diseñado para no alterar el ritmo vertical ni causar desbordamiento en filas de tablas responsivas.
 */

import { computed } from 'vue';

type PillVariant = 'neutral' | 'success' | 'warning' | 'danger' | 'info';

const props = withDefaults(
    defineProps<{
        label?: string;
        value: string | number;
        variant?: PillVariant;
        mono?: boolean;
    }>(),
    {
        label: undefined,
        variant: 'neutral',
        mono: false,
    }
);

const variantClasses = computed(() => {
    switch (props.variant) {
        case 'success':
            return 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800/60';
        case 'warning':
            return 'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300 dark:border-amber-800/60';
        case 'danger':
            return 'bg-red-50 text-red-800 border-red-200 dark:bg-red-950/40 dark:text-red-300 dark:border-red-800/60';
        case 'info':
            return 'bg-sky-50 text-sky-800 border-sky-200 dark:bg-sky-950/40 dark:text-sky-300 dark:border-sky-800/60';
        case 'neutral':
        default:
            return 'bg-slate-100 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700';
    }
});
</script>

<template>
    <span
        class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] border font-medium select-none truncate"
        :class="variantClasses"
    >
        <span v-if="label" class="opacity-70 font-normal uppercase tracking-wider text-[10px]">
            {{ label }}:
        </span>
        <span :class="mono ? 'font-mono tracking-tight font-semibold' : 'font-semibold'">
            {{ value }}
        </span>
    </span>
</template>
