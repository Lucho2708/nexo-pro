<script setup lang="ts">
/**
 * NexoButton.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Botón atómico fundamental para la interacción y conversión del usuario en NEXO PRO.
 * - Diseñado bajo filosofía Mobile-First: garantiza áreas táctiles mínimas de 44x44px a 48x48px (Apple HIG y WCAG 2.5.5).
 * - Comunica claramente la intención de la acción (Primario para conversión/pago/voto, Secundario para navegación, Peligro para cancelaciones).
 * - Previene errores y envíos duplicados con estado de carga (Loading) que inhabilita de inmediato el componente visual y funcionalmente.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Implementación con Vue 3 Composition API, script setup y TypeScript estricto.
 * - Soporte nativo para Modo Claro y Modo Oscuro utilizando las variables semánticas de NEXO PRO.
 * - Estados interactivos completos: Default, Hover, Focus-Visible con ring-offset, Active (microescala de 150ms), Disabled y Loading.
 * - Accesibilidad WCAG 2.1 AA: Contraste > 4.5:1 en todos los modos, atributos aria-busy y aria-disabled gestionados automáticamente.
 */

import { computed } from 'vue';

type ButtonVariant = 'primary' | 'secondary' | 'outline' | 'ghost' | 'danger';
type ButtonSize = 'sm' | 'md' | 'lg';

const props = withDefaults(
    defineProps<{
        variant?: ButtonVariant;
        size?: ButtonSize;
        isLoading?: boolean;
        disabled?: boolean;
        type?: 'button' | 'submit' | 'reset';
        ariaLabel?: string;
    }>(),
    {
        variant: 'primary',
        size: 'md',
        isLoading: false,
        disabled: false,
        type: 'button',
        ariaLabel: undefined,
    }
);

const emit = defineEmits<{
    (e: 'click', event: MouseEvent): void;
}>();

const isDisabledOrLoading = computed(() => props.disabled || props.isLoading);

// Estilos de variantes respetando la paleta estricta de NEXO PRO
const variantClasses = computed(() => {
    switch (props.variant) {
        case 'primary':
            return 'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white dark:bg-blue-500 dark:hover:bg-blue-600 dark:text-slate-950 shadow-sm shadow-blue-500/20';
        case 'secondary':
            return 'bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white dark:bg-indigo-500 dark:hover:bg-indigo-600 dark:text-slate-950 shadow-sm';
        case 'outline':
            return 'border-2 border-slate-200 dark:border-slate-800 bg-transparent text-slate-800 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800/60 active:bg-slate-200 dark:active:bg-slate-700';
        case 'ghost':
            return 'bg-transparent text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 active:bg-slate-200 dark:active:bg-slate-700';
        case 'danger':
            return 'bg-red-600 hover:bg-red-700 active:bg-red-800 text-white dark:bg-red-500 dark:hover:bg-red-600 dark:text-white shadow-sm shadow-red-500/20';
        default:
            return 'bg-blue-600 text-white';
    }
});

// Dimensiones de botones garantizando touch target >= 44x44px
const sizeClasses = computed(() => {
    switch (props.size) {
        case 'sm':
            return 'min-h-[44px] px-3.5 py-2 text-xs font-semibold rounded-lg gap-1.5';
        case 'lg':
            return 'min-h-[56px] px-6 py-3.5 text-base font-bold rounded-xl gap-2.5';
        case 'md':
        default:
            return 'min-h-[48px] px-5 py-2.5 text-sm font-bold rounded-xl gap-2';
    }
});

const handleClick = (event: MouseEvent) => {
    if (isDisabledOrLoading.value) {
        event.preventDefault();
        event.stopPropagation();
        return;
    }
    emit('click', event);
};
</script>

<template>
    <button
        :type="type"
        :disabled="isDisabledOrLoading"
        :aria-busy="isLoading"
        :aria-disabled="isDisabledOrLoading"
        :aria-label="ariaLabel"
        @click="handleClick"
        class="inline-flex items-center justify-center font-sans tracking-wide transition-all duration-150 ease-out focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2 dark:focus-visible:ring-offset-slate-900 cursor-pointer select-none active:scale-[0.98] disabled:cursor-not-allowed disabled:opacity-50 disabled:active:scale-100"
        :class="[variantClasses, sizeClasses]"
    >
        <!-- Spinner animado para estado Loading -->
        <svg
            v-if="isLoading"
            class="animate-spin -ml-1 mr-2 h-4 w-4 text-current"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            aria-hidden="true"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        <!-- Slot para iconos o contenido -->
        <span class="inline-flex items-center gap-2">
            <slot />
        </span>
    </button>
</template>
