<script setup lang="ts">
/**
 * NexoInput.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Campo de entrada atómico para formularios transaccionales y de registro en NEXO PRO.
 * - Reduce errores de digitación en cédulas, valores monetarios y correos con feedback inmediato.
 * - Muestra mensajes de error accionables y claros bajo el estándar de accesibilidad.
 * - Touch target ergonómico de 48px de altura mínima para agilidad de captura en dispositivos móviles.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Vinculación bidireccional reactiva con v-model (string | number).
 * - Accesibilidad WCAG 2.1 AA: Etiquetas asociadas vía id, aria-invalid dinámico y aria-describedby para lectores de pantalla.
 * - Soporte nativo para Modo Claro (#FFFFFF) y Modo Oscuro (#0F172A / Slate-900).
 */

import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        modelValue?: string | number;
        type?: 'text' | 'email' | 'number' | 'password' | 'tel';
        label?: string;
        placeholder?: string;
        error?: string;
        hint?: string;
        disabled?: boolean;
        required?: boolean;
        id?: string;
    }>(),
    {
        modelValue: '',
        type: 'text',
        label: undefined,
        placeholder: '',
        error: undefined,
        hint: undefined,
        disabled: false,
        required: false,
        id: () => `nexo-input-${Math.random().toString(36).substring(2, 9)}`,
    }
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'blur', event: FocusEvent): void;
    (e: 'focus', event: FocusEvent): void;
}>();

const inputId = computed(() => props.id);
const errorId = computed(() => `${inputId.value}-error`);
const hintId = computed(() => `${inputId.value}-hint`);

const describedBy = computed(() => {
    if (props.error) return errorId.value;
    if (props.hint) return hintId.value;
    return undefined;
});

const handleInput = (event: Event) => {
    const target = event.target as HTMLInputElement;
    emit('update:modelValue', target.value);
};
</script>

<template>
    <div class="w-full space-y-1.5 font-sans text-left">
        <!-- Etiqueta del Campo -->
        <label
            v-if="label"
            :for="inputId"
            class="block text-xs font-semibold text-slate-700 dark:text-slate-300 tracking-wide"
        >
            {{ label }}
            <span v-if="required" class="text-red-500 font-bold" aria-hidden="true">*</span>
        </label>

        <!-- Contenedor del Input -->
        <div class="relative">
            <input
                :id="inputId"
                :type="type"
                :value="modelValue"
                :placeholder="placeholder"
                :disabled="disabled"
                :required="required"
                :aria-invalid="!!error"
                :aria-describedby="describedBy"
                @input="handleInput"
                @blur="$emit('blur', $event)"
                @focus="$emit('focus', $event)"
                class="w-full min-h-[48px] px-4 py-2.5 rounded-xl border text-sm transition-all duration-150 ease-out focus:outline-none disabled:cursor-not-allowed disabled:opacity-60"
                :class="[
                    error
                        ? 'border-red-500 bg-red-50/20 text-slate-900 dark:text-white dark:bg-red-950/20 focus:border-red-500 focus:ring-2 focus:ring-red-500/20'
                        : 'border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-900 dark:text-white hover:border-slate-300 dark:hover:border-slate-700 focus:border-blue-600 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20'
                ]"
            />
        </div>

        <!-- Mensaje de Error Accesible -->
        <p
            v-if="error"
            :id="errorId"
            class="text-xs font-medium text-red-600 dark:text-red-400 flex items-center gap-1 pt-0.5"
            role="alert"
        >
            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ error }}
        </p>

        <!-- Texto de Ayuda / Hint -->
        <p
            v-else-if="hint"
            :id="hintId"
            class="text-xs text-slate-500 dark:text-slate-400 pt-0.5"
        >
            {{ hint }}
        </p>
    </div>
</template>
