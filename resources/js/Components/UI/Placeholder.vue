<script setup lang="ts">
interface Props {
    type?: 'text' | 'circle' | 'rect';
    width?: string;
    height?: string;
    repeat?: number;
}

withDefaults(defineProps<Props>(), {
    type: 'text',
    width: 'w-full',
    height: 'h-4',
    repeat: 1,
});
</script>

<template>
    <div class="animate-pulse space-y-3">
        <template v-for="i in repeat" :key="i">
            <div 
                v-if="type === 'text'"
                class="bg-surface-container-highest dark:bg-outline-variant/30 rounded-full opacity-50"
                :class="[width, height]"
            ></div>
            
            <div 
                v-else-if="type === 'circle'"
                class="bg-surface-container-highest dark:bg-outline-variant/30 rounded-full opacity-50"
                :class="[width, height]"
            ></div>
            
            <div 
                v-else-if="type === 'rect'"
                class="bg-surface-container-highest dark:bg-outline-variant/30 rounded-2xl opacity-50"
                :class="[width, height]"
            ></div>
        </template>
    </div>
</template>
