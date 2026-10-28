<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    label: string;
    value: string;
    trend?: string;
    trendUp?: boolean;
    subtext?: string;
    progress?: number;
    colorClass: string;
    href?: string;
    icon?: string;
}>();

const isVisible = ref(false);

onMounted(() => {
    setTimeout(() => {
        isVisible.value = true;
    }, 100);
});
</script>

<template>
    <component 
        :is="href ? Link : 'div'" 
        :href="href"
        class="premium-card p-5 sm:p-7 md:p-8 rounded-[1.8rem] sm:!rounded-[2.2rem] relative overflow-hidden group transition-all duration-300 hover:-translate-y-1 hover:shadow-2xl hover:shadow-primary/10 block cursor-pointer border border-outline-variant/10 dark:border-white/5"
    >
        <!-- Accent Decoration -->
        <div class="absolute top-0 left-0 w-2 h-14 bg-primary/20 rounded-full mt-8 -ml-1 group-hover:h-20 group-hover:bg-primary transition-all duration-300"></div>
        
        <!-- Header row -->
        <div class="flex items-center justify-between mb-3 sm:mb-4">
            <p class="text-[9px] sm:text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] truncate">{{ label }}</p>
            <div v-if="href" class="w-6 h-6 rounded-lg bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-on-surface-variant/40 group-hover:text-primary group-hover:bg-primary/10 transition-all opacity-0 group-hover:opacity-100 shrink-0">
                <span class="material-symbols-rounded text-sm">arrow_outward</span>
            </div>
        </div>
        
        <div class="flex items-end justify-between gap-2">
            <h3 class="text-2xl sm:text-3xl md:text-4xl font-black text-primary tracking-tighter leading-none transition-all duration-500 transform" :class="[isVisible ? 'translate-y-0 opacity-100' : 'translate-y-3 opacity-0']">
                {{ value }}
            </h3>
            
            <div 
                v-if="trend" 
                class="px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-xl text-[9px] sm:text-[10px] font-black flex items-center gap-1 transition-all duration-500 shrink-0"
                :class="[
                    trendUp ? 'bg-secondary/10 text-secondary' : 'bg-error/10 text-error',
                    isVisible ? 'scale-100 opacity-100' : 'scale-90 opacity-0'
                ]"
            >
                <span class="material-symbols-rounded text-xs sm:text-sm">{{ trendUp ? 'trending_up' : 'trending_down' }}</span>
                {{ trend }}
            </div>
        </div>
        
        <div v-if="progress !== undefined" class="mt-4 sm:mt-6 w-full bg-surface-container-low dark:bg-white/5 h-2 rounded-full overflow-hidden">
            <div 
                class="h-full rounded-full transition-all duration-[1.2s] ease-out shadow-sm" 
                :class="colorClass" 
                :style="{ width: isVisible ? `${progress}%` : '0%' }"
            ></div>
        </div>
        
        <p v-if="subtext" class="text-[9px] sm:text-[10px] text-on-surface-variant/50 font-bold uppercase tracking-widest mt-4 sm:mt-5 flex items-center gap-1.5 truncate">
            <span class="w-1.5 h-1.5 rounded-full bg-primary/40 shrink-0"></span>
            <span class="truncate">{{ subtext }}</span>
        </p>
    </component>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 20, 'wght' 600, 'FILL' 0;
}
</style>
