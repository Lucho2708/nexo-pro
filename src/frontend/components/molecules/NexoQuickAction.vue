<script setup lang="ts">
/**
 * NexoQuickAction.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Botón / tarjeta de acceso rápido mobile-first para flujos de alta frecuencia (pagar cuota, entrar a asamblea, reservar zona).
 * - Diseñado con área táctil mínima de 56px para eliminar la fricción al usar el pulgar en smartphones.
 * - Proporciona feedback háptico visual inmediato en hover y active.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Molécula interactiva accesible WCAG 2.1 AA con soporte de teclado y foco visible.
 * - Soporte nativo para Modo Claro y Modo Oscuro.
 */

defineProps<{
    title: string;
    description?: string;
    badge?: string;
    disabled?: boolean;
}>();

const emit = defineEmits<{
    (e: 'click'): void;
}>();
</script>

<template>
    <button
        type="button"
        :disabled="disabled"
        @click="emit('click')"
        class="w-full min-h-[56px] p-4 rounded-xl border border-slate-200/90 dark:border-slate-800 bg-white dark:bg-slate-900 text-left transition-all duration-150 flex items-center justify-between gap-3 group hover:border-blue-500/50 hover:shadow-sm active:scale-[0.99] focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 cursor-pointer disabled:cursor-not-allowed disabled:opacity-50"
    >
        <div class="flex items-center gap-3.5 min-w-0">
            <div class="w-10 h-10 rounded-lg bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0 transition-transform group-hover:scale-105">
                <slot name="icon">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </slot>
            </div>

            <div class="space-y-0.5 min-w-0">
                <div class="flex items-center gap-2">
                    <h4 class="text-sm font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 truncate">
                        {{ title }}
                    </h4>
                    <span v-if="badge" class="px-2 py-0.2 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                        {{ badge }}
                    </span>
                </div>
                <p v-if="description" class="text-xs text-slate-500 dark:text-slate-400 truncate">
                    {{ description }}
                </p>
            </div>
        </div>

        <svg class="w-5 h-5 text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 group-hover:translate-x-0.5 transition-all shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
    </button>
</template>
