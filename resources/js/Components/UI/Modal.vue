<script setup lang="ts">
import { onUnmounted, watch } from 'vue';
import CloseButton from './CloseButton.vue';

interface Props {
    show?: boolean;
    title?: string;
    showClose?: boolean;
    maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '4xl' | 'full';
    closeable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    show: false,
    showClose: true,
    maxWidth: 'md',
    closeable: true,
});

const emit = defineEmits(['close']);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const maxWidthClasses = {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
    '4xl': 'sm:max-w-4xl',
    full: 'sm:max-w-full sm:m-4',
};

// Evitar scroll del body cuando el modal está abierto
watch(() => props.show, (value) => {
    if (typeof document !== 'undefined') {
        if (value) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    }
}, { immediate: true });

onUnmounted(() => {
    if (typeof document !== 'undefined') {
        document.body.style.overflow = '';
    }
});

const handleKeydown = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

if (typeof window !== 'undefined') {
    window.addEventListener('keydown', handleKeydown);
    onUnmounted(() => window.removeEventListener('keydown', handleKeydown));
}
</script>

<template>
    <Teleport to="body">
        <div v-show="show" class="fixed inset-0 z-[100] flex flex-col justify-end sm:justify-center overflow-hidden">
            <!-- Backdrop -->
            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
                appear
            >
                <div v-if="show" class="fixed inset-0 bg-slate-950/60 backdrop-blur-md transition-opacity" @click="close" />
            </Transition>

            <!-- Modal Panel -->
            <Transition
                enter-active-class="ease-out duration-500 sm:duration-300"
                enter-from-class="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
                enter-to-class="translate-y-0 sm:scale-100 sm:opacity-100"
                leave-active-class="ease-in duration-300 sm:duration-200"
                leave-from-class="translate-y-0 sm:scale-100 sm:opacity-100"
                leave-to-class="translate-y-full sm:translate-y-0 sm:scale-95 sm:opacity-0"
                appear
            >
                <div 
                    v-if="show"
                    class="relative w-full transform overflow-hidden bg-white dark:bg-[#0B132B] border border-slate-200/90 dark:border-slate-800/80 text-left transition-all 
                           rounded-t-[2.5rem] sm:rounded-[2.5rem] shadow-2xl sm:mx-auto
                           max-h-[90vh] sm:max-h-[85vh] flex flex-col text-slate-900 dark:text-slate-100"
                    :class="[maxWidthClasses[maxWidth]]"
                >
                    <!-- Handle for mobile bottom sheet -->
                    <div class="sm:hidden w-12 h-1 bg-slate-300 dark:bg-slate-700 rounded-full mx-auto mt-3 shrink-0" />

                    <!-- Header with Title -->
                    <div v-if="title" class="px-6 sm:px-10 pt-6 sm:pt-8 pb-3 flex items-center justify-between shrink-0 border-b border-slate-200/80 dark:border-slate-800/80">
                        <div class="flex-1">
                            <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight leading-tight">
                                {{ title }}
                            </h3>
                            <div class="w-12 h-1 bg-primary mt-2 rounded-full hidden sm:block"></div>
                        </div>
                        <div class="ml-4">
                            <CloseButton v-if="showClose" @click="close" class="bg-slate-100 dark:bg-slate-800 hover:bg-red-500/10 hover:text-red-500 dark:hover:bg-red-500/20 dark:hover:text-red-400 text-slate-600 dark:text-slate-300" />
                        </div>
                    </div>

                    <!-- Single Absolute Close Button when no title is provided -->
                    <div v-else-if="showClose" class="absolute top-4 right-4 z-20">
                        <CloseButton @click="close" class="bg-slate-100/90 dark:bg-slate-800/90 hover:bg-red-500/10 hover:text-red-500 dark:hover:bg-red-500/20 dark:hover:text-red-400 text-slate-600 dark:text-slate-300" />
                    </div>

                    <!-- Content (Scrollable) -->
                    <div class="overflow-y-auto custom-scrollbar flex-1" :class="title ? 'px-6 sm:px-10 py-6' : 'p-0'">
                        <slot />
                    </div>
                </div>
            </Transition>
        </div>
    </Teleport>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 10px;
}
:global(.dark) .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #334155;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
:global(.dark) .custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #475569;
}
</style>
