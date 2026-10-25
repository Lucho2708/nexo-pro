<script setup lang="ts">
/**
 * NexoEnvironmentBadge.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Identificador semántico de entorno para la plataforma NEXO PRO:
 * - Informa al usuario en qué contexto operativo se encuentra (Landing, Superadmin, Administrador, Propietario).
 * - Previene errores de navegación y brinda certeza de los privilegios y alcances de la sesión.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Componente atómico con soporte de tokens para Modo Claro y Modo Oscuro.
 * - Accesibilidad WCAG 2.1 AA con ratios de contraste superiores a 4.5:1.
 */

import { computed } from 'vue';
import type { NexoEnvironment } from '../../tokens/environments';

const props = withDefaults(
    defineProps<{
        environment: NexoEnvironment;
        label?: string;
    }>(),
    {
        label: undefined,
    }
);

const envConfig = computed(() => {
    switch (props.environment) {
        case 'superadmin':
            return {
                defaultLabel: 'Superadmin Console',
                badgeClasses: 'bg-sky-50 text-sky-800 border-sky-200 dark:bg-sky-950/60 dark:text-sky-300 dark:border-sky-800/80',
                dotColor: 'bg-sky-500',
            };
        case 'admin':
            return {
                defaultLabel: 'Gestión Operativa',
                badgeClasses: 'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-950/60 dark:text-blue-300 dark:border-blue-800/80',
                dotColor: 'bg-blue-500',
            };
        case 'owner':
            return {
                defaultLabel: 'Portal Propietario',
                badgeClasses: 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/60 dark:text-emerald-300 dark:border-emerald-800/80',
                dotColor: 'bg-emerald-500',
            };
        case 'landing':
        default:
            return {
                defaultLabel: 'NEXO PRO Enterprise',
                badgeClasses: 'bg-indigo-50 text-indigo-800 border-indigo-200 dark:bg-indigo-950/60 dark:text-indigo-300 dark:border-indigo-800/80',
                dotColor: 'bg-indigo-500',
            };
    }
});
</script>

<template>
    <span
        class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold border font-sans tracking-wide select-none transition-colors duration-150"
        :class="envConfig.badgeClasses"
    >
        <span class="w-1.5 h-1.5 rounded-full" :class="envConfig.dotColor"></span>
        {{ label || envConfig.defaultLabel }}
    </span>
</template>
