<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    size?: 'sm' | 'md' | 'lg';
    class?: string;
}

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
});

const classes = computed(() => [
    'inline-flex rounded-lg shadow-sm overflow-hidden',
    props.class
]);
</script>

<template>
    <div :class="classes" role="group">
        <slot />
    </div>
</template>

<style>
/* CSS to handle border merging between buttons in the group */
[role="group"] > button,
[role="group"] > a {
    border-radius: 0 !important;
    border-right-width: 0 !important;
}

[role="group"] > button:first-child,
[role="group"] > a:first-child {
    border-top-left-radius: 0.5rem !important;
    border-bottom-left-radius: 0.5rem !important;
}

[role="group"] > button:last-child,
[role="group"] > a:last-child {
    border-top-right-radius: 0.5rem !important;
    border-bottom-right-radius: 0.5rem !important;
    border-right-width: 2px !important; /* Restore border for outline variant */
}

/* For primary/secondary variants with single border */
[role="group"] > .bg-primary,
[role="group"] > .bg-surface-container {
    border-right-width: 1px !important;
    border-right-color: rgba(255, 255, 255, 0.1) !important;
}

[role="group"] > .bg-primary:last-child,
[role="group"] > .bg-surface-container:last-child {
    border-right-width: 0 !important;
}
</style>
