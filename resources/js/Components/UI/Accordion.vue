<script setup lang="ts">
import { ref } from 'vue';

interface Item {
    title: string;
    id?: string | number;
}

interface Props {
    items: Item[];
    allowMultiple?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    allowMultiple: false,
});

const activeItems = ref<(string | number)[]>([]);

const toggle = (id: string | number) => {
    if (props.allowMultiple) {
        const index = activeItems.value.indexOf(id);
        if (index === -1) {
            activeItems.value.push(id);
        } else {
            activeItems.value.splice(index, 1);
        }
    } else {
        activeItems.value = activeItems.value.includes(id) ? [] : [id];
    }
};

const isActive = (id: string | number) => activeItems.value.includes(id);
</script>

<template>
    <div class="flex flex-col gap-3 w-full">
        <div 
            v-for="(item, index) in items" 
            :key="item.id || index"
            class="bg-surface-low dark:bg-surface-container-low border border-surface-container-high dark:border-outline-variant/30 rounded-2xl overflow-hidden transition-all duration-300"
            :class="{ 'shadow-md border-secondary/30 dark:border-accent/30': isActive(item.id || index) }"
        >
            <button 
                @click="toggle(item.id || index)"
                class="w-full px-6 py-4 flex justify-between items-center text-on-surface dark:text-white focus:outline-none group transition-colors"
                :aria-expanded="isActive(item.id || index)"
            >
                <span class="font-bold text-sm tracking-tight group-hover:text-secondary transition-colors">{{ item.title }}</span>
                <div 
                    class="w-8 h-8 rounded-lg bg-surface-container-high dark:bg-outline-variant/20 flex items-center justify-center transition-all duration-300"
                    :class="{ 'rotate-180 bg-secondary/10 text-secondary': isActive(item.id || index) }"
                >
                    <svg class="w-5 h-5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>
            
            <transition
                enter-active-class="transition-[max-height,opacity] duration-300 ease-out overflow-hidden"
                enter-from-class="max-h-0 opacity-0"
                enter-to-class="max-h-[1000px] opacity-100"
                leave-active-class="transition-[max-height,opacity] duration-200 ease-in overflow-hidden"
                leave-from-class="max-h-[1000px] opacity-100"
                leave-to-class="max-h-0 opacity-0"
            >
                <div v-show="isActive(item.id || index)">
                    <div class="px-6 pb-5 pt-1 text-on-surface/80 dark:text-on-surface opacity-90 text-sm leading-relaxed border-t border-surface-container-high/50 dark:border-outline-variant/10">
                        <slot :name="'item-' + (item.id || index)">
                            {{ (item as any).content }}
                        </slot>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>
