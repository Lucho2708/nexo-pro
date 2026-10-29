<script setup lang="ts">
/**
 * Input.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Campo de entrada accesible y responsivo para formularios transaccionales y de captura.
 * Soporta etiquetas flotantes sin colisión con placeholders y modo de etiqueta superior estática.
 *
 * [ASPECTO TÉCNICO / UI]
 * Prevención de colisión tipográfica mediante 'placeholder-transparent focus:placeholder-...'
 * y soporte de 'staticLabel' para formularios de alta densidad visual. WCAG 2.1 AA compliant.
 */

import { computed } from 'vue';

interface Props {
    modelValue: string | number | null;
    label?: string;
    type?: string;
    placeholder?: string;
    error?: string;
    state?: 'success' | 'warning' | 'error' | 'default';
    icon?: string;
    disabled?: boolean;
    required?: boolean;
    class?: string;
    inputClass?: string;
    staticLabel?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    disabled: false,
    required: false,
    modelValue: '',
    state: 'default',
    staticLabel: true
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const inputId = `input-${Math.random().toString(36).substring(2, 9)}`;

const displayLabel = computed(() => {
    if (!props.label) return '';
    return props.label.trim().replace(/\s*\*+$/, '');
});

const onInput = (event: Event) => {
    emit('update:modelValue', (event.target as HTMLInputElement).value);
};

const currentState = computed(() => {
    if (props.error) return 'error';
    return props.state;
});

const stateConfig = {
    error: 'border-red-500/60 focus:border-red-500 focus:ring-red-500/10 text-red-600 dark:text-red-400',
    success: 'border-emerald-500/60 focus:border-emerald-500 focus:ring-emerald-500/10 text-emerald-600 dark:text-emerald-400',
    warning: 'border-amber-500/60 focus:border-amber-500 focus:ring-amber-500/10 text-amber-600 dark:text-amber-400',
    default: 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600 focus:border-primary focus:ring-primary/10'
};

const labelStateConfig = {
    error: 'text-red-500',
    success: 'text-emerald-500',
    warning: 'text-amber-500',
    default: 'text-slate-500 dark:text-slate-400 peer-focus:text-primary'
};
</script>

<template>
    <div :class="['relative w-full group', props.class]">
        <!-- Modo Etiqueta Superior Estática (Alta legibilidad empresarial) -->
        <template v-if="staticLabel">
            <label 
                v-if="label" 
                :for="inputId" 
                class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2"
            >
                {{ displayLabel }} <span v-if="required" class="text-red-500 ml-0.5">*</span>
            </label>
            <div class="relative flex items-center">
                <!-- Icon -->
                <span 
                    v-if="icon" 
                    class="material-symbols-outlined notranslate absolute left-4 text-slate-400 group-focus-within:text-primary transition-colors z-10 pointer-events-none select-none"
                    style="font-variation-settings: 'opsz' 20, 'wght' 500;"
                    aria-hidden="true"
                    translate="no"
                >
                    {{ icon }}
                </span>
                
                <!-- Input -->
                <input
                    :id="inputId"
                    :type="type"
                    :value="modelValue"
                    :placeholder="placeholder"
                    :disabled="disabled"
                    :required="required"
                    :class="[
                        'w-full rounded-xl border bg-slate-50/70 dark:bg-slate-900/70 px-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 outline-none transition-all duration-200',
                        'focus:bg-white dark:focus:bg-slate-900 focus:ring-4 disabled:opacity-50 disabled:cursor-not-allowed',
                        icon ? 'pl-11' : '',
                        stateConfig[currentState],
                        inputClass
                    ]"
                    :aria-invalid="!!error"
                    :aria-describedby="error ? `${inputId}-error` : undefined"
                    @input="onInput"
                />
            </div>
        </template>

        <!-- Modo Etiqueta Flotante (Con prevención de colisión de placeholder) -->
        <template v-else>
            <div class="relative flex items-center">
                <!-- Icon -->
                <span 
                    v-if="icon" 
                    class="material-symbols-outlined notranslate absolute left-4 text-slate-400 group-focus-within:text-primary transition-colors z-10 pointer-events-none select-none"
                    style="font-variation-settings: 'opsz' 20, 'wght' 500;"
                    aria-hidden="true"
                    translate="no"
                >
                    {{ icon }}
                </span>
                
                <!-- Input -->
                <input
                    :id="inputId"
                    :type="type"
                    :value="modelValue"
                    :placeholder="placeholder || ' '"
                    :disabled="disabled"
                    :required="required"
                    :class="[
                        'peer w-full rounded-2xl border-2 bg-slate-50 dark:bg-slate-900/60 px-4 pt-6 pb-2 text-sm font-medium transition-all outline-none tracking-normal placeholder-transparent focus:placeholder-slate-400 dark:focus:placeholder-slate-500',
                        'focus:ring-4 focus:ring-offset-0 disabled:opacity-50 disabled:cursor-not-allowed text-slate-900 dark:text-white',
                        icon ? 'pl-12' : '',
                        stateConfig[currentState],
                        inputClass
                    ]"
                    :aria-invalid="!!error"
                    :aria-describedby="error ? `${inputId}-error` : undefined"
                    @input="onInput"
                />

                <!-- Floating Label -->
                <label 
                    v-if="label" 
                    :for="inputId" 
                    :class="[
                        'absolute font-bold uppercase tracking-wider transition-all duration-200 ease-in-out cursor-text pointer-events-none z-10',
                        icon ? 'left-12' : 'left-4',
                        'top-4 -translate-y-2.5 text-[10px]',
                        'peer-placeholder-shown:translate-y-0 peer-placeholder-shown:text-xs',
                        'peer-focus:-translate-y-2.5 peer-focus:text-[10px]',
                        labelStateConfig[currentState]
                    ]"
                >
                    {{ displayLabel }} <span v-if="required" class="text-red-500 ml-0.5">*</span>
                </label>
            </div>
        </template>
        
        <!-- Error Message -->
        <p v-if="error" :id="`${inputId}-error`" class="mt-1.5 text-xs font-semibold text-red-500 flex items-center gap-1 animate-in fade-in slide-in-from-top-1">
            <span class="material-symbols-outlined text-sm" aria-hidden="true">error</span>
            {{ error }}
        </p>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    line-height: 1;
}

/* Fallback for placeholder-shown behaviour when placeholder is not explicitly set */
input:not(:placeholder-shown) + label {
    transform: translateY(-0.625rem);
    font-size: 10px;
}
</style>
