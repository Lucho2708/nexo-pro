<script setup lang="ts">
import { onUnmounted, watch, ref } from 'vue';
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
    if (value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
}, { immediate: true });

onUnmounted(() => {
    document.body.style.overflow = '';
});

const handleKeydown = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

window.addEventListener('keydown', handleKeydown);
onUnmounted(() => window.removeEventListener('keydown', handleKeydown));
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
                <div v-if="show" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="close" />
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
                    class="relative w-full transform overflow-hidden bg-white text-left transition-all 
                           rounded-t-[3rem] sm:rounded-[3rem] shadow-2xl sm:mx-auto
                           max-h-[90vh] sm:max-h-[85vh] flex flex-col"
                    :class="[maxWidthClasses[maxWidth]]"
                >
                    <!-- Handle for mobile bottom sheet -->
                    <div class="sm:hidden w-12 h-1 bg-slate-200 rounded-full mx-auto mt-4 shrink-0" />

                    <!-- Header -->
                    <div v-if="title || showClose" class="px-6 sm:px-12 pt-6 sm:pt-12 pb-2 flex items-center justify-between shrink-0">
                        <div class="flex-1">
                            <h3 v-if="title" class="text-2xl sm:text-3xl font-black text-on-surface uppercase tracking-tighter leading-tight">
                                {{ title }}
                            </h3>
                            <div class="w-12 h-1.5 bg-primary mt-2 rounded-full hidden sm:block"></div>
                        </div>
                        <div class="ml-4">
                            <CloseButton v-if="showClose" @click="close" class="!bg-surface-container-high hover:!bg-error/10 hover:!text-error" />
                        </div>
                    </div>

                    <!-- Content (Scrollable) -->
                    <div class="px-6 sm:px-12 pb-10 sm:pb-12 pt-4 overflow-y-auto custom-scrollbar flex-1">
                        <slot />
                    </div>
                </div>
            </Transition>
        </div>
    </Teleport>
</template>

<style scoped>
.custom-scrollbar::-webkit-scrollbar {
    width: 5px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #e2e8f0;
    border-radius: 10px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: #cbd5e1;
}

/* Glassmorphism subtle effect for premium feel */
@supports (backdrop-filter: blur(20px)) {
    .bg-white {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
    }
}
</style>
