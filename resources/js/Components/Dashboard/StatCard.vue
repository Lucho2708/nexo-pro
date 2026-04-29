<script setup lang="ts">
import { onMounted, ref } from 'vue';

const props = defineProps<{
    label: string;
    value: string;
    trend?: string;
    trendUp?: boolean;
    subtext?: string;
    progress?: number;
    colorClass: string;
}>();

const isVisible = ref(false);

onMounted(() => {
    setTimeout(() => {
        isVisible.value = true;
    }, 100);
});
</script>

<template>
    <div class="premium-card p-8 !rounded-[2rem] relative overflow-hidden group transition-all duration-500 hover:-translate-y-1 hover:shadow-primary/10">
        <!-- Accent Decoration -->
        <div class="absolute top-0 left-0 w-2 h-16 bg-primary/10 rounded-full mt-10 -ml-1 group-hover:h-20 transition-all duration-500"></div>
        
        <p class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] mb-4">{{ label }}</p>
        
        <div class="flex items-end justify-between">
            <h3 class="text-4xl font-black text-primary tracking-tighter leading-none transition-all duration-700 transform" :class="[isVisible ? 'translate-y-0 opacity-100' : 'translate-y-4 opacity-0']">
                {{ value }}
            </h3>
            
            <div 
                v-if="trend" 
                class="px-2.5 py-1 rounded-xl text-[10px] font-black flex items-center gap-1 transition-all duration-700"
                :class="[
                    trendUp ? 'bg-secondary/10 text-secondary' : 'bg-error/10 text-error',
                    isVisible ? 'scale-100 opacity-100' : 'scale-90 opacity-0'
                ]"
            >
                <span class="material-symbols-outlined text-sm" style="font-variation-settings: 'FILL' 1;">{{ trendUp ? 'trending_up' : 'trending_down' }}</span>
                {{ trend }}
            </div>
        </div>
        
        <div v-if="progress !== undefined" class="mt-6 w-full bg-surface-container-low h-2 rounded-full overflow-hidden">
            <div 
                class="h-full rounded-full transition-all duration-[1.5s] ease-out shadow-sm" 
                :class="colorClass" 
                :style="{ width: isVisible ? `${progress}%` : '0%' }"
            ></div>
        </div>
        
        <p v-if="subtext" class="text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest mt-5 flex items-center gap-1.5">
            <span class="w-1 h-1 rounded-full bg-outline-variant/50"></span>
            {{ subtext }}
        </p>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    line-height: 1;
}
</style>
