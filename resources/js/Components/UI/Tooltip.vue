<script setup lang="ts">
import { ref } from 'vue';

interface Props {
    text: string;
    position?: 'top' | 'bottom' | 'left' | 'right';
}

const props = withDefaults(defineProps<Props>(), {
    position: 'top',
});

const isVisible = ref(false);

const positionClasses = {
    top: 'bottom-full left-1/2 -translate-x-1/2 mb-2',
    bottom: 'top-full left-1/2 -translate-x-1/2 mt-2',
    left: 'right-full top-1/2 -translate-y-1/2 mr-2',
    right: 'left-full top-1/2 -translate-y-1/2 ml-2',
};

const arrowClasses = {
    top: '-bottom-1 left-1/2 -translate-x-1/2 border-b border-r',
    bottom: '-top-1 left-1/2 -translate-x-1/2 border-t border-l',
    left: '-right-1 top-1/2 -translate-y-1/2 border-t border-r',
    right: '-left-1 top-1/2 -translate-y-1/2 border-b border-l',
};
</script>

<template>
    <div 
        class="relative inline-block"
        @mouseenter="isVisible = true"
        @mouseleave="isVisible = false"
    >
        <slot />
        
        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="opacity-0 translate-y-1"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 translate-y-1"
        >
            <div 
                v-if="isVisible"
                class="absolute z-[110] px-3 py-1.5 bg-primary text-on-primary text-[9px] font-black uppercase tracking-tight rounded-xl shadow-2xl pointer-events-none w-max max-w-[200px] text-center leading-tight"
                :class="positionClasses[position]"
            >
                {{ text }}
                
                <!-- Arrow -->
                <div 
                    class="absolute w-2 h-2 bg-primary rotate-45"
                    :class="arrowClasses[position]"
                ></div>
            </div>
        </Transition>
    </div>
</template>
