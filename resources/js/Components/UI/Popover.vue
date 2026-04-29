<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import Button from './Button.vue';

interface Props {
    text: string;
    title: string;
    content: string;
    variant?: 'primary' | 'outline' | 'ghost';
    position?: 'top' | 'bottom';
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'outline',
    position: 'top',
});

const isOpen = ref(false);
const popoverRef = ref<HTMLElement | null>(null);

const toggle = () => {
    isOpen.value = !isOpen.value;
};

const close = (e: MouseEvent) => {
    if (popoverRef.value && !popoverRef.value.contains(e.target as Node)) {
        isOpen.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', close);
});

onUnmounted(() => {
    document.remove('click', close);
});
</script>

<template>
    <div class="relative inline-block" ref="popoverRef">
        <Button :variant="variant" @click="toggle">
            {{ text }}
        </Button>

        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100"
            leave-to-class="opacity-0 scale-95"
        >
            <div 
                v-if="isOpen" 
                class="absolute z-[100] w-64 bg-surface border border-outline-variant rounded-2xl shadow-2xl p-4 transition-all"
                :class="[
                    position === 'top' ? 'bottom-full mb-3 left-1/2 -translate-x-1/2' : 'top-full mt-3 left-1/2 -translate-x-1/2'
                ]"
            >
                <h4 class="font-black text-sm mb-1 text-primary uppercase tracking-tight">{{ title }}</h4>
                <p class="text-xs text-on-surface-variant font-medium leading-relaxed">{{ content }}</p>
                
                <!-- Arrow -->
                <div 
                    class="absolute w-3 h-3 bg-surface border-outline-variant rotate-45"
                    :class="[
                        position === 'top' ? '-bottom-1.5 left-1/2 -translate-x-1/2 border-b border-r' : '-top-1.5 left-1/2 -translate-x-1/2 border-t border-l'
                    ]"
                ></div>
            </div>
        </Transition>
    </div>
</template>
