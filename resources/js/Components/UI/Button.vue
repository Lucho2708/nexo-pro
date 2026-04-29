<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

interface Props {
    href?: string;
    type?: 'button' | 'submit' | 'reset';
    variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'error' | 'gradient';
    size?: 'sm' | 'md' | 'lg';
    disabled?: boolean;
    loading?: boolean;
    icon?: string;
}

const props = withDefaults(defineProps<Props>(), {
    type: 'button',
    variant: 'primary',
    size: 'md',
    disabled: false,
    loading: false,
});

defineEmits(['click']);

const baseClasses = 'inline-flex items-center justify-center font-bold uppercase tracking-widest transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary/20 active:scale-[0.98] disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none rounded-xl border';

const variantClasses = {
    primary: 'bg-primary text-on-primary border-primary shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5',
    secondary: 'bg-surface-container text-on-surface border-outline-variant/30 hover:bg-surface-container-high',
    outline: 'bg-transparent border-2 border-primary text-primary hover:bg-primary hover:text-on-primary',
    ghost: 'bg-transparent border-transparent text-on-surface-variant hover:text-primary hover:bg-primary/5',
    error: 'bg-error text-white border-error shadow-lg shadow-error/20 hover:shadow-error/40 hover:-translate-y-0.5',
    gradient: 'bg-brand-gradient text-white border-transparent shadow-lg shadow-primary/20 hover:-translate-y-[2px]',
};

const sizeClasses = {
    sm: 'px-4 py-2 text-[10px] gap-2',
    md: 'px-6 py-2.5 text-[11px] gap-2.5',
    lg: 'px-8 py-3.5 text-xs gap-3',
};

const classes = computed(() => [
    baseClasses,
    variantClasses[props.variant],
    sizeClasses[props.size],
]);
</script>

<template>
    <Link
        v-if="href"
        :href="href"
        :class="classes"
        class="no-underline"
    >
        <span v-if="loading" class="animate-spin material-symbols-outlined text-[1.2em]">sync</span>
        <span v-else-if="icon" class="material-symbols-outlined text-[1.2em]">{{ icon }}</span>
        <slot />
    </Link>
    
    <button
        v-else
        :type="type"
        :disabled="disabled || loading"
        :class="classes"
        @click="$emit('click', $event)"
    >
        <span v-if="loading" class="animate-spin material-symbols-outlined text-[1.2em]">sync</span>
        <span v-else-if="icon" class="material-symbols-outlined text-[1.2em]">{{ icon }}</span>
        <slot />
    </button>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 20, 'wght' 600;
    line-height: 1;
}
</style>
