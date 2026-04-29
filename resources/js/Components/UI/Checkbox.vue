<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    modelValue: boolean;
    label?: string;
    description?: string;
    error?: string;
    state?: 'success' | 'warning' | 'error' | 'default';
    disabled?: boolean;
    required?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: false,
    disabled: false,
    required: false,
    state: 'default'
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const inputId = `checkbox-${Math.random().toString(36).substring(2, 9)}`;

const currentState = computed(() => {
    if (props.error) return 'error';
    return props.state;
});

const stateConfig = {
    error: 'border-error/50 hover:border-error focus:ring-error/20 bg-error/5',
    success: 'border-success/50 hover:border-success focus:ring-success/20 bg-success/5',
    warning: 'border-warning/50 hover:border-warning focus:ring-warning/20 bg-warning/5',
    default: 'border-outline-variant/30 hover:border-primary/50 focus:ring-primary/20 bg-surface-container-low/30'
};

const checkboxStateConfig = {
    error: 'border-error text-error focus:ring-error/20 accent-error',
    success: 'border-success text-success focus:ring-success/20 accent-success',
    warning: 'border-warning text-warning focus:ring-warning/20 accent-warning',
    default: 'border-outline-variant/30 text-primary focus:ring-primary/20 accent-primary',
};

const onInput = (event: Event) => {
    emit('update:modelValue', (event.target as HTMLInputElement).checked);
};
</script>

<template>
    <div class="flex flex-col gap-2">
        <label 
            :for="inputId"
            class="flex items-start gap-4 p-4 rounded-xl border cursor-pointer transition-colors"
            :class="[
                stateConfig[currentState],
                disabled ? 'opacity-50 cursor-not-allowed' : ''
            ]"
        >
            <div class="pt-0.5 relative flex items-center justify-center">
                <input 
                    :id="inputId"
                    type="checkbox" 
                    :checked="modelValue"
                    @change="onInput"
                    :disabled="disabled"
                    :required="required"
                    class="peer w-5 h-5 rounded outline-none transition-all cursor-pointer appearance-none border-2 checked:bg-current"
                    :class="checkboxStateConfig[currentState]"
                />
                <span class="material-symbols-outlined text-white text-[14px] absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
            </div>
            
            <div class="flex flex-col gap-1">
                <div v-if="label || $slots.label" class="text-sm font-bold text-on-surface leading-snug">
                    <slot name="label">{{ label }}</slot>
                    <span v-if="required" class="text-error ml-0.5">*</span>
                </div>
                <div v-if="description || $slots.description" class="text-xs text-on-surface-variant font-medium leading-relaxed">
                    <slot name="description">{{ description }}</slot>
                </div>
            </div>
        </label>
        
        <p v-if="error" class="text-error text-xs font-bold px-2 mt-1 flex items-center gap-1 animate-in fade-in slide-in-from-top-1">
            <span class="material-symbols-outlined text-[14px]" aria-hidden="true">error</span>
            {{ error }}
        </p>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 20, 'wght' 600;
}
</style>
