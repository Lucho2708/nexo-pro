<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    variant?: 'primary' | 'secondary' | 'success' | 'warning' | 'error' | 'neutral' | 'gradient' | 'outline';
    class?: string;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'neutral',
});

const variants = {
    primary: 'bg-primary text-on-primary',
    secondary: 'bg-secondary text-on-secondary shadow-sm',
    success: 'bg-success/10 text-success border border-success/20 dark:text-success',
    warning: 'bg-warning/10 text-warning border border-warning/20 dark:text-warning',
    error: 'bg-error/10 text-error border border-error/20 dark:text-error',
    neutral: 'bg-surface-container-high text-on-surface border border-outline-variant/30',
    gradient: 'bg-brand-gradient text-white shadow-md',
    outline: 'bg-transparent border border-surface-container-highest text-on-surface dark:border-outline-variant dark:text-white',
};

const classes = computed(() => [
    'inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider transition-all',
    variants[props.variant],
    props.class
]);
</script>

<template>
    <span :class="classes">
        <slot />
    </span>
</template>
