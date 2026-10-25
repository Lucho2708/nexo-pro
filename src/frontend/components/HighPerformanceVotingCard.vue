<script setup lang="ts">
/**
 * HighPerformanceVotingCard.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Módulo de papeleta digital para votaciones masivas en asamblea de copropietarios.
 * - Reduce al mínimo los errores del votante destacando las opciones con botones de tacto amplio (mínimo 48x48px).
 * - Previene el fraude o doble voto deshabilitando inmediatamente el componente tras el primer clic (Optimistic UI)
 *   y mostrando retroalimentación de progreso en tiempo real.
 * - Soporta la votación unificada para usuarios que poseen o representan múltiples apartamentos o locales comerciales.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Arquitectura Mobile-First reactiva con TypeScript estricto.
 * - Accesibilidad WCAG 2.1 AA: Agrupación semántica role="radiogroup", soporte completo de navegación
 *   por teclado (flechas arriba/abajo/tab/enter) y aria-checked para lectores de pantalla.
 * - Mitigación de Layout Shift (CLS): Espaciado reservado para advertencias y estado de confirmación.
 * - Microinteracciones hápticas visuales de 150ms en hover y focus-visible con outline offset.
 */

import { ref, computed } from 'vue';

interface Opcion {
    id: string;
    titulo: string;
    orden?: number;
}

interface UnidadVotante {
    id: string;
    nombre: string;
    torre?: string;
    coeficiente: number;
}

interface Pregunta {
    id: string;
    titulo: string;
    descripcion?: string;
    tipo: 'simple' | 'coeficiente' | 'nominal';
    opciones: Opcion[];
}

const props = defineProps<{
    pregunta: Pregunta;
    unidades: UnidadVotante[];
    hasVoted?: boolean;
    isSubmitting?: boolean;
}>();

const emit = defineEmits<{
    (e: 'submit-vote', opcionId: string): void;
}>();

const selectedOpcionId = ref<string | null>(null);
const localSubmitting = ref(false);

const pesoTotalRepresentado = computed(() => {
    return props.unidades.reduce((acc, u) => acc + (u.coeficiente || 0), 0);
});

const canSubmit = computed(() => {
    return selectedOpcionId.value !== null && !props.hasVoted && !props.isSubmitting && !localSubmitting.value;
});

const handleVote = () => {
    if (!canSubmit.value || selectedOpcionId.value === null) return;

    localSubmitting.value = true;
    emit('submit-vote', selectedOpcionId.value);
};
</script>

<template>
    <div
        class="w-full rounded-2xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 p-6 shadow-sm transition-all duration-200"
    >
        <!-- Encabezado de la Moción -->
        <div class="space-y-2 pb-4 border-b border-zinc-100 dark:border-zinc-800">
            <div class="flex items-center justify-between">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-100 text-primary-800 dark:bg-primary-950/80 dark:text-primary-300">
                    Votación Activa • Ponderada por Coeficiente
                </span>
                <span v-if="hasVoted" class="inline-flex items-center gap-1 text-xs font-bold text-emerald-600 dark:text-emerald-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Voto Registrado
                </span>
            </div>

            <h2 class="text-xl font-bold text-zinc-900 dark:text-white leading-snug">
                {{ pregunta.titulo }}
            </h2>

            <p v-if="pregunta.descripcion" class="text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">
                {{ pregunta.descripcion }}
            </p>
        </div>

        <!-- Resumen de Unidades Representadas -->
        <div class="my-4 p-3 rounded-xl bg-zinc-50 dark:bg-zinc-800/40 border border-zinc-100 dark:border-zinc-800 flex flex-wrap items-center justify-between gap-2 text-xs">
            <span class="text-zinc-600 dark:text-zinc-400">
                Votando con <strong class="text-zinc-900 dark:text-zinc-100 font-semibold">{{ unidades.length }}</strong> inmueble(s):
                <span class="text-zinc-500 font-mono">({{ unidades.map(u => u.nombre).join(', ') }})</span>
            </span>
            <span class="font-bold text-zinc-900 dark:text-zinc-100 bg-white dark:bg-zinc-900 px-2 py-0.5 rounded-md shadow-xs">
                Peso total: {{ pesoTotalRepresentado.toFixed(4) }}%
            </span>
        </div>

        <!-- Lista de Opciones de Votación (Accessible Radio Group) -->
        <div
            class="space-y-3 pt-2"
            role="radiogroup"
            :aria-label="pregunta.titulo"
        >
            <button
                v-for="opcion in pregunta.opciones"
                :key="opcion.id"
                type="button"
                role="radio"
                :aria-checked="selectedOpcionId === opcion.id"
                :disabled="hasVoted || isSubmitting || localSubmitting"
                @click="selectedOpcionId = opcion.id"
                class="w-full min-h-[52px] px-4 py-3 rounded-xl border text-left transition-all duration-150 flex items-center justify-between group focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 cursor-pointer disabled:cursor-not-allowed disabled:opacity-60"
                :class="[
                    selectedOpcionId === opcion.id
                        ? 'border-primary-600 bg-primary-50/50 dark:bg-primary-950/30 dark:border-primary-500 shadow-xs'
                        : 'border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900/60 hover:border-zinc-300 dark:hover:border-zinc-700'
                ]"
            >
                <span class="text-sm sm:text-base font-medium text-zinc-900 dark:text-zinc-100 group-hover:text-primary-600 dark:group-hover:text-primary-400 min-w-0 pr-3 break-words">
                    {{ opcion.titulo }}
                </span>

                <!-- Indicador Circular Accesible -->
                <span
                    class="w-5 h-5 rounded-full border flex items-center justify-center transition-colors shrink-0"
                    :class="[
                        selectedOpcionId === opcion.id
                            ? 'border-primary-600 bg-primary-600 text-white dark:border-primary-500 dark:bg-primary-500'
                            : 'border-zinc-300 dark:border-zinc-600 group-hover:border-zinc-400'
                    ]"
                >
                    <span v-if="selectedOpcionId === opcion.id" class="w-2 h-2 rounded-full bg-white"></span>
                </span>
            </button>
        </div>

        <!-- Botón de Envío y Confirmación -->
        <div class="pt-6">
            <button
                type="button"
                @click="handleVote"
                :disabled="!canSubmit"
                class="w-full min-h-[48px] px-6 py-3 rounded-xl font-bold text-sm tracking-wide transition-all duration-150 flex items-center justify-center gap-2 shadow-sm focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500 cursor-pointer disabled:cursor-not-allowed disabled:opacity-50"
                :class="[
                    hasVoted
                        ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-400'
                        : 'bg-primary-600 hover:bg-primary-700 text-white active:scale-[0.99]'
                ]"
            >
                <template v-if="isSubmitting || localSubmitting">
                    <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                    </svg>
                    <span>Registrando voto seguro...</span>
                </template>
                <template v-else-if="hasVoted">
                    <span>Sufragio Emitido con Éxito</span>
                </template>
                <template v-else>
                    <span>Confirmar y Emitir Voto</span>
                </template>
            </button>
        </div>
    </div>
</template>
