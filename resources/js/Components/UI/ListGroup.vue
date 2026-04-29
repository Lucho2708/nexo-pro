<script setup lang="ts">
import { computed } from 'vue';

interface ListItem {
    label: string;
    icon?: string;
    badge?: string;
    badgeVariant?: 'primary' | 'secondary' | 'success' | 'warning' | 'danger' | 'neutral' | 'gradient';
    active?: boolean;
    disabled?: boolean;
    action?: () => void;
}

interface Props {
    items: ListItem[];
    flush?: boolean;
}

withDefaults(defineProps<Props>(), {
    flush: false,
});
</script>

<template>
    <ul 
        class="flex flex-col bg-surface dark:bg-surface-container-low overflow-hidden transition-all duration-300"
        :class="[
            flush ? 'border-y border-surface-container-high dark:border-outline-variant/30' : 'border border-surface-container-high dark:border-outline-variant/30 rounded-2xl shadow-sm'
        ]"
    >
        <li v-for="(item, index) in items" :key="index" class="relative">
            <button 
                v-if="item.action"
                @click="item.action"
                :disabled="item.disabled"
                class="w-full flex items-center justify-between px-6 py-4 text-sm font-medium transition-all duration-300 text-left group"
                :class="[
                    index !== items.length - 1 ? 'border-b border-surface-container-high dark:border-outline-variant/10' : '',
                    item.active 
                        ? 'bg-primary/5 text-primary dark:bg-secondary/10 dark:text-secondary' 
                        : 'text-on-surface/80 dark:text-on-surface hover:bg-surface-container-low dark:hover:bg-outline-variant/10',
                    item.disabled ? 'opacity-50 cursor-not-allowed' : ''
                ]"
            >
                <div class="flex items-center">
                    <span v-if="item.icon" class="material-symbols-outlined mr-4 text-[20px] transition-transform group-hover:scale-110" :class="item.active ? 'text-primary dark:text-secondary' : 'opacity-60 group-hover:opacity-100'">{{ item.icon }}</span>
                    <span>{{ item.label }}</span>
                </div>
                
                <div v-if="item.badge" class="ml-2">
                    <span 
                        class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                        :class="[
                            item.badgeVariant === 'gradient' ? 'bg-brand-gradient text-white shadow-sm' : 'bg-surface-container-highest dark:bg-outline-variant/30 text-on-surface-variant'
                        ]"
                    >
                        {{ item.badge }}
                    </span>
                </div>
            </button>
            
            <div 
                v-else
                class="flex items-center justify-between px-6 py-4 text-sm font-medium transition-all duration-300"
                :class="[
                    index !== items.length - 1 ? 'border-b border-surface-container-high dark:border-outline-variant/10' : '',
                    item.active 
                        ? 'bg-primary/5 text-primary dark:bg-secondary/10 dark:text-secondary' 
                        : 'text-on-surface/80 dark:text-on-surface',
                ]"
            >
                <div class="flex items-center">
                    <span v-if="item.icon" class="material-symbols-outlined mr-4 text-[20px] transition-transform" :class="item.active ? 'text-primary dark:text-secondary' : 'opacity-60'">{{ item.icon }}</span>
                    <span>{{ item.label }}</span>
                </div>
                
                <div v-if="item.badge" class="ml-2">
                    <span 
                        class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                        :class="[
                            item.badgeVariant === 'gradient' ? 'bg-brand-gradient text-white shadow-sm' : 'bg-surface-container-highest dark:bg-outline-variant/30 text-on-surface-variant'
                        ]"
                    >
                        {{ item.badge }}
                    </span>
                </div>
            </div>
        </li>
    </ul>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'wght' 500;
}
</style>
