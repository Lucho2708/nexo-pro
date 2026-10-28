<script setup lang="ts">
import { computed, useSlots } from 'vue';

interface Props {
    modelValue?: boolean;
    checked?: boolean;
    id?: string;
    label?: string;
    description?: string;
    error?: string;
    state?: 'success' | 'warning' | 'error' | 'default';
    disabled?: boolean;
    required?: boolean;
    class?: string;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: false,
    checked: undefined,
    id: undefined,
    disabled: false,
    required: false,
    state: 'default'
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
    (e: 'update:checked', value: boolean): void;
    (e: 'change', value: boolean): void;
}>();

const slots = useSlots();
const generatedId = `checkbox-${Math.random().toString(36).substring(2, 9)}`;
const elementId = computed(() => props.id || generatedId);

const hasContent = computed(() => {
    return Boolean(
        props.label || 
        props.description || 
        slots.label || 
        slots.description || 
        slots.default
    );
});

const isChecked = computed(() => {
    if (props.checked !== undefined) return Boolean(props.checked);
    return Boolean(props.modelValue);
});

const onInput = (event: Event) => {
    const val = (event.target as HTMLInputElement).checked;
    emit('update:modelValue', val);
    emit('update:checked', val);
    emit('change', val);
};
</script>

<template>
    <div class="flex flex-col gap-1.5 w-full" :class="props.class">
        <!-- MODE 1: Rich Card with Label & Description -->
        <label 
            v-if="hasContent"
            :for="elementId"
            class="flex items-start gap-3.5 p-4 rounded-2xl border-2 transition-all cursor-pointer group select-none"
            :class="[
                error 
                    ? 'border-red-500/50 bg-red-50/5 dark:bg-red-950/10' 
                    : isChecked
                        ? 'border-primary/50 bg-primary/5 dark:bg-primary/[0.04]'
                        : 'border-slate-300 dark:border-slate-700/80 bg-slate-50/70 dark:bg-slate-900/40 hover:border-primary/50 dark:hover:border-primary/50',
                disabled ? 'opacity-50 cursor-not-allowed' : ''
            ]"
        >
            <input 
                :id="elementId"
                type="checkbox" 
                :checked="isChecked"
                @change="onInput"
                :disabled="disabled"
                :required="required"
                class="sr-only peer"
            />
            
            <!-- Checkbox Box (High Contrast) -->
            <div 
                class="mt-0.5 w-5 h-5 min-w-[20px] min-h-[20px] rounded-lg border-2 flex items-center justify-center transition-all duration-150 shadow-xs"
                :class="[
                    isChecked 
                        ? 'bg-primary border-primary text-white shadow-sm shadow-primary/30' 
                        : error
                            ? 'bg-white dark:bg-slate-900 border-red-500'
                            : 'bg-white dark:bg-slate-950 border-slate-400 dark:border-slate-500 group-hover:border-primary dark:group-hover:border-primary',
                    'peer-focus-visible:ring-4 peer-focus-visible:ring-primary/20'
                ]"
            >
                <svg 
                    v-if="isChecked" 
                    class="w-3.5 h-3.5 text-white stroke-[3.5] fill-none drop-shadow-xs" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            
            <div class="flex flex-col gap-0.5 flex-1 pt-0.5">
                <div v-if="label || $slots.label" class="text-sm font-bold text-slate-900 dark:text-white leading-snug">
                    <slot name="label">{{ label }}</slot>
                    <span v-if="required" class="text-red-500 ml-0.5">*</span>
                </div>
                <div v-if="description || $slots.description" class="text-xs text-slate-600 dark:text-slate-300 font-medium leading-relaxed">
                    <slot name="description">{{ description }}</slot>
                </div>
                <slot />
            </div>
        </label>

        <!-- MODE 2: Standalone Compact Checkbox (No Label/Description) -->
        <label 
            v-else 
            :for="elementId"
            class="relative inline-flex items-center justify-center cursor-pointer select-none group"
            :class="disabled ? 'opacity-50 cursor-not-allowed' : ''"
        >
            <input 
                :id="elementId"
                type="checkbox" 
                :checked="isChecked"
                @change="onInput"
                :disabled="disabled"
                :required="required"
                class="sr-only peer"
            />
            <div 
                class="w-5 h-5 min-w-[20px] min-h-[20px] rounded-lg border-2 flex items-center justify-center transition-all duration-150 shadow-xs"
                :class="[
                    isChecked 
                        ? 'bg-primary border-primary text-white shadow-sm shadow-primary/30' 
                        : error
                            ? 'bg-white dark:bg-slate-900 border-red-500'
                            : 'bg-white dark:bg-slate-950 border-slate-400 dark:border-slate-500 group-hover:border-primary dark:group-hover:border-primary',
                    'peer-focus-visible:ring-4 peer-focus-visible:ring-primary/20'
                ]"
            >
                <svg 
                    v-if="isChecked" 
                    class="w-3.5 h-3.5 text-white stroke-[3.5] fill-none drop-shadow-xs" 
                    viewBox="0 0 24 24" 
                    stroke="currentColor"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
        </label>
        
        <p v-if="error" class="text-red-500 text-xs font-bold px-2 mt-1 flex items-center gap-1 animate-in fade-in slide-in-from-top-1">
            <span class="material-symbols-outlined text-[14px]" aria-hidden="true">error</span>
            {{ error }}
        </p>
    </div>
</template>
