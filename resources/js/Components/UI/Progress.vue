<script setup lang="ts">
interface Props {
    percentage: number;
    variant?: 'primary' | 'success' | 'gradient';
    showLabel?: boolean;
    height?: string;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'gradient',
    showLabel: false,
    height: 'h-2.5',
});
</script>

<template>
    <div class="w-full">
        <div v-if="showLabel" class="flex justify-between mb-1">
            <span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Progreso</span>
            <span class="text-[10px] font-black text-primary">{{ percentage }}%</span>
        </div>
        <div class="w-full bg-surface-container-high rounded-full overflow-hidden" :class="height">
            <div 
                class="h-full rounded-full transition-all duration-1000 ease-out" 
                :class="[
                    variant === 'success' ? 'bg-green-500' : 
                    variant === 'primary' ? 'bg-primary' : 
                    'bg-[image:var(--bg-brand-gradient)] shadow-[0_0_15px_rgba(0,212,255,0.3)]'
                ]"
                :style="{ width: percentage + '%' }"
            ></div>
        </div>
    </div>
</template>
