<script setup lang="ts">
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
}

const props = withDefaults(defineProps<Props>(), {
    type: 'text',
    disabled: false,
    required: false,
    modelValue: '',
    state: 'default'
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const inputId = `input-${Math.random().toString(36).substring(2, 9)}`;

const onInput = (event: Event) => {
    emit('update:modelValue', (event.target as HTMLInputElement).value);
};

const currentState = computed(() => {
    if (props.error) return 'error';
    return props.state;
});

const stateConfig = {
    error: 'border-error/50 focus:border-error focus:ring-error/10 text-error',
    success: 'border-success/50 focus:border-success focus:ring-success/10 text-success',
    warning: 'border-warning/50 focus:border-warning focus:ring-warning/10 text-warning',
    default: 'border-outline-variant/10 hover:border-outline-variant/30 focus:border-primary focus:ring-primary/10'
};

const labelStateConfig = {
    error: 'text-error',
    success: 'text-success',
    warning: 'text-warning',
    default: 'text-on-surface-variant/60 peer-focus:text-primary'
};
</script>

<template>
    <div :class="['relative w-full group', props.class]">
        <div class="relative flex items-center">
            <!-- Icon -->
            <span 
                v-if="icon" 
                class="material-symbols-outlined absolute left-4 text-on-surface-variant/40 group-focus-within:text-primary transition-colors z-10"
                style="font-variation-settings: 'opsz' 20, 'wght' 500;"
                aria-hidden="true"
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
                    'peer w-full rounded-2xl border-2 bg-surface-container-low px-4 pt-6 pb-2 text-sm font-medium transition-all outline-none uppercase tracking-wider',
                    'focus:ring-4 focus:ring-offset-0 disabled:opacity-50 disabled:cursor-not-allowed text-on-surface',
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
                    'absolute font-black uppercase tracking-widest transition-all duration-200 ease-in-out cursor-text pointer-events-none z-10',
                    icon ? 'left-12' : 'left-4',
                    'top-4 -translate-y-2.5 text-[9px]',
                    'peer-placeholder-shown:translate-y-0 peer-placeholder-shown:text-[11px]',
                    'peer-focus:-translate-y-2.5 peer-focus:text-[9px]',
                    labelStateConfig[currentState]
                ]"
            >
                {{ label }} <span v-if="required" class="text-error ml-0.5">*</span>
            </label>
        </div>
        
        <!-- Error Message -->
        <p v-if="error" :id="`${inputId}-error`" class="mt-1.5 text-[10px] font-black uppercase tracking-wider text-error flex items-center gap-1 animate-in fade-in slide-in-from-top-1">
            <span class="material-symbols-outlined text-[14px]" aria-hidden="true">error</span>
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
    font-size: 9px;
}
</style>
