<script setup lang="ts">
interface Props {
    size?: 'sm' | 'md' | 'lg';
}

withDefaults(defineProps<Props>(), {
    size: 'md',
});

const emit = defineEmits(['click']);
</script>

<template>
    <button 
        type="button"
        @click="emit('click')"
        class="inline-flex items-center justify-center rounded-xl text-on-surface/50 hover:text-on-surface hover:bg-surface-container-high dark:text-white/50 dark:hover:text-white dark:hover:bg-outline-variant/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-secondary/50 active:scale-90"
        :class="{
            'w-8 h-8': size === 'sm',
            'w-10 h-10': size === 'md',
            'w-12 h-12': size === 'lg',
        }"
        aria-label="Cerrar"
    >
        <svg 
            class="transition-transform duration-300 group-hover:rotate-90" 
            :class="{
                'w-4 h-4': size === 'sm',
                'w-5 h-5': size === 'md',
                'w-6 h-6': size === 'lg',
            }"
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</template>
