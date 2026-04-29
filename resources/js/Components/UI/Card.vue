<script setup lang="ts">
import { computed, useSlots } from 'vue';

interface Props {
    title?: string;
    subtitle?: string;
    icon?: string;
    hover?: boolean;
    class?: string;
    contentClass?: string;
    img?: string;
}

const props = withDefaults(defineProps<Props>(), {
    hover: false,
});

const slots = useSlots();

const classes = computed(() => [
    'group premium-card overflow-hidden relative flex flex-col transition-all duration-300',
    props.hover ? 'hover:-translate-y-1 hover:premium-elevated' : '',
    props.class
]);

const hasHeader = computed(() => !!props.title || !!props.icon || !!props.img || !!slots.header);
</script>

<template>
    <div :class="classes">
        <!-- Image Header -->
        <div v-if="img" class="w-full h-48 overflow-hidden relative">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent z-10 pointer-events-none"></div>
            <img :src="img" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" :alt="title || 'Card image'">
        </div>

        <!-- Brand accent line -->
        <div v-if="icon && !img" class="absolute top-0 left-0 w-1.5 h-full bg-brand-gradient z-10"></div>

        <!-- Header section -->
        <header v-if="hasHeader" class="px-6 pt-6 pb-4 flex justify-between items-start relative z-20">
            <div class="flex items-center gap-4">
                <div v-if="icon" class="w-12 h-12 bg-primary/5 dark:bg-white/5 text-primary dark:text-secondary flex items-center justify-center rounded-xl border border-outline-variant/20 shadow-inner transition-transform duration-300 group-hover:scale-110 group-hover:rotate-3">
                    <span class="material-symbols-outlined text-[24px]" aria-hidden="true">{{ icon }}</span>
                </div>
                <div>
                    <h3 v-if="title" class="text-lg font-bold text-on-surface leading-tight tracking-tight">{{ title }}</h3>
                    <p v-if="subtitle" class="text-xs text-on-surface-variant font-bold uppercase tracking-widest mt-1">{{ subtitle }}</p>
                </div>
            </div>
            
            <div v-if="$slots.header" class="flex items-center gap-2">
                <slot name="header" />
            </div>
        </header>

        <!-- Main Content -->
        <div :class="['px-6 pb-6 flex-grow', !hasHeader ? 'pt-6' : '', props.contentClass]">
            <div class="text-sm text-on-surface/80 dark:text-on-surface opacity-90 leading-relaxed">
                <slot />
            </div>
        </div>

        <!-- Footer -->
        <footer v-if="$slots.footer" class="px-6 py-4 bg-surface-container-low border-t border-outline-variant/30 mt-auto">
            <slot name="footer" />
        </footer>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 1, 'opsz' 20;
}
</style>
