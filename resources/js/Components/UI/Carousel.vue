<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

interface Props {
    images: string[];
    autoPlay?: boolean;
    interval?: number;
}

const props = withDefaults(defineProps<Props>(), {
    autoPlay: true,
    interval: 5000,
});

const current = ref(0);
let timer: ReturnType<typeof setInterval> | null = null;

const next = () => {
    current.value = (current.value + 1) % props.images.length;
};

const prev = () => {
    current.value = (current.value - 1 + props.images.length) % props.images.length;
};

const goTo = (index: number) => {
    current.value = index;
};

const startTimer = () => {
    if (props.autoPlay) {
        timer = setInterval(next, props.interval);
    }
};

const stopTimer = () => {
    if (timer) {
        clearInterval(timer);
        timer = null;
    }
};

onMounted(startTimer);
onUnmounted(stopTimer);
</script>

<template>
    <div 
        class="relative rounded-2xl overflow-hidden group aspect-video bg-surface-container-low"
        @mouseenter="stopTimer"
        @mouseleave="startTimer"
    >
        <!-- Slides -->
        <div 
            class="flex transition-transform duration-700 ease-in-out h-full" 
            :style="{ transform: `translateX(-${current * 100}%)` }"
        >
            <div 
                v-for="(img, index) in images" 
                :key="index"
                class="w-full h-full shrink-0 relative"
            >
                <img :src="img" class="w-full h-full object-cover" :alt="'Slide ' + (index + 1)">
                <!-- Overlay Gradient -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-60"></div>
            </div>
        </div>

        <!-- Controls -->
        <button 
            @click="prev" 
            class="absolute left-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/40 text-white rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-md border border-white/20 active:scale-95"
            aria-label="Previous slide"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </button>
        <button 
            @click="next" 
            class="absolute right-4 top-1/2 -translate-y-1/2 w-12 h-12 bg-white/20 hover:bg-white/40 text-white rounded-xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-md border border-white/20 active:scale-95"
            aria-label="Next slide"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        </button>

        <!-- Indicators -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
            <button 
                v-for="(_, index) in images" 
                :key="index"
                @click="goTo(index)"
                class="h-1.5 transition-all duration-300 rounded-full"
                :class="current === index ? 'w-8 bg-secondary shadow-[0_0_10px_rgba(0,212,255,0.6)]' : 'w-2 bg-white/40 hover:bg-white/60'"
                :aria-label="'Go to slide ' + (index + 1)"
            ></button>
        </div>
    </div>
</template>
