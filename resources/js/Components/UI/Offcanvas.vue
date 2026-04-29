<script setup lang="ts">
import { computed, watch, onMounted, onUnmounted } from 'vue';
import CloseButton from './CloseButton.vue';

interface Props {
    show?: boolean;
    position?: 'left' | 'right' | 'top' | 'bottom';
    width?: string;
    height?: string;
    title?: string;
}

const props = withDefaults(defineProps<Props>(), {
    show: false,
    position: 'right',
    width: 'w-80 md:w-96',
    height: 'h-80',
});

const emit = defineEmits(['close']);

const close = () => {
    emit('close');
};

watch(() => props.show, (value) => {
    if (value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = 'visible';
    }
});

const closeOnEscape = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = 'visible';
});

const positionClasses = computed(() => {
    return {
        'left': 'top-0 left-0 h-full transform transition-transform duration-500 ease-in-out',
        'right': 'top-0 right-0 h-full transform transition-transform duration-500 ease-in-out',
        'top': 'top-0 left-0 w-full transform transition-transform duration-500 ease-in-out',
        'bottom': 'bottom-0 left-0 w-full transform transition-transform duration-500 ease-in-out',
    }[props.position];
});

const transitionClasses = computed(() => {
    return {
        'left': { from: '-translate-x-full', to: 'translate-x-0' },
        'right': { from: 'translate-x-full', to: 'translate-x-0' },
        'top': { from: '-translate-y-full', to: 'translate-y-0' },
        'bottom': { from: 'translate-y-full', to: 'translate-y-0' },
    }[props.position];
});
</script>

<template>
    <Teleport to="body">
        <!-- Backdrop -->
        <Transition
            enter-active-class="transition-opacity duration-500 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div 
                v-if="show" 
                class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[80]"
                @click="close"
            ></div>
        </Transition>

        <!-- Canvas -->
        <Transition
            enter-active-class="transition-transform duration-500 ease-out"
            :enter-from-class="transitionClasses.from"
            :enter-to-class="transitionClasses.to"
            leave-active-class="transition-transform duration-300 ease-in"
            :leave-from-class="transitionClasses.to"
            :leave-to-class="transitionClasses.from"
        >
            <div 
                v-if="show"
                class="fixed bg-surface dark:bg-surface-container shadow-2xl z-[90] overflow-hidden flex flex-col border-surface-container-high dark:border-outline-variant/30"
                :class="[
                    positionClasses,
                    (position === 'left' || position === 'right') ? width : '',
                    (position === 'top' || position === 'bottom') ? height : '',
                    position === 'left' ? 'border-r' : '',
                    position === 'right' ? 'border-l' : '',
                    position === 'top' ? 'border-b' : '',
                    position === 'bottom' ? 'border-t' : '',
                ]"
            >
                <!-- Header -->
                <div class="px-6 py-5 border-b border-surface-container-high dark:border-outline-variant/20 flex justify-between items-center bg-surface-container-low/50">
                    <h3 class="text-xl font-bold text-primary dark:text-secondary uppercase tracking-tight">{{ title }}</h3>
                    <CloseButton @click="close" />
                </div>

                <!-- Body -->
                <div class="flex-1 overflow-y-auto p-6 md:p-8 no-scrollbar">
                    <slot />
                </div>

                <!-- Footer -->
                <div v-if="$slots.footer" class="px-6 py-5 bg-surface-container-low/50 border-t border-surface-container-high dark:border-outline-variant/20">
                    <slot name="footer" />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
