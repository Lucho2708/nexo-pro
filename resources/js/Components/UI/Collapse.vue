<script setup lang="ts">
import { ref } from 'vue';
import Button from './Button.vue';

interface Props {
    label?: string;
    variant?: 'primary' | 'secondary' | 'outline' | 'ghost' | 'gradient';
    size?: 'sm' | 'md' | 'lg';
}

withDefaults(defineProps<Props>(), {
    label: 'Toggle',
    variant: 'outline',
    size: 'md',
});

const isOpen = ref(false);
</script>

<template>
    <div class="w-full">
        <Button 
            :variant="variant" 
            :size="size" 
            @click="isOpen = !isOpen"
            class="mb-3"
            :aria-expanded="isOpen"
        >
            {{ label }}
            <svg 
                class="w-4 h-4 ml-2 transition-transform duration-300" 
                :class="{ 'rotate-180': isOpen }"
                fill="none" stroke="currentColor" viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
            </svg>
        </Button>

        <transition
            enter-active-class="transition-[max-height,opacity] duration-300 ease-out overflow-hidden"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-[1000px] opacity-100"
            leave-active-class="transition-[max-height,opacity] duration-200 ease-in overflow-hidden"
            leave-from-class="max-h-[1000px] opacity-100"
            leave-to-class="max-h-0 opacity-0"
        >
            <div v-show="isOpen">
                <slot />
            </div>
        </transition>
    </div>
</template>
