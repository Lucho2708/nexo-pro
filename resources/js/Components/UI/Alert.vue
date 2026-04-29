<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    variant?: 'success' | 'info' | 'warning' | 'error' | 'primary';
    title?: string;
    message?: string;
    closeable?: boolean;
    show?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'info',
    closeable: false,
    show: true,
});

const emit = defineEmits(['close']);

const config = {
    success: {
        icon: 'check_circle',
        classes: 'bg-success/10 border-success/50 text-success dark:text-success',
    },
    info: {
        icon: 'info',
        classes: 'bg-info/10 border-info/50 text-info dark:text-info',
    },
    primary: {
        icon: 'notifications',
        classes: 'bg-primary/10 border-primary/50 text-primary dark:text-secondary',
    },
    warning: {
        icon: 'warning',
        classes: 'bg-warning/10 border-warning/50 text-warning dark:text-warning',
    },
    error: {
        icon: 'error',
        classes: 'bg-error/10 border-error/50 text-error dark:text-error',
    },
};

const currentConfig = computed(() => config[props.variant]);
</script>

<template>
    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 translate-y-2"
    >
        <div 
            v-if="show"
            :class="['p-4 rounded-xl border flex gap-3 items-start relative overflow-hidden backdrop-blur-sm', currentConfig.classes]"
        >
            <div class="shrink-0 pt-0.5">
                <span class="material-symbols-outlined text-[20px]">{{ currentConfig.icon }}</span>
            </div>
            
            <div class="flex-1">
                <h4 v-if="title" class="text-sm font-bold tracking-tight leading-none mb-1">{{ title }}</h4>
                <p v-if="message || $slots.default" class="text-sm font-medium opacity-90 leading-relaxed">
                    <slot>{{ message }}</slot>
                </p>
            </div>

            <button 
                v-if="closeable"
                @click="emit('close')"
                class="shrink-0 p-1 hover:bg-black/10 dark:hover:bg-white/10 rounded-lg transition-colors"
            >
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
    </Transition>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 1;
}
</style>
