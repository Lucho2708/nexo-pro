<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';

interface DropdownItem {
    label: string;
    href?: string;
    action?: () => void;
    icon?: string;
    variant?: 'default' | 'error';
    separator?: boolean;
}

interface Props {
    label?: string;
    items?: DropdownItem[];
    align?: 'left' | 'right';
    width?: string;
}

const props = withDefaults(defineProps<Props>(), {
    align: 'right',
    width: 'w-56',
});

const isOpen = ref(false);
const dropdownRef = ref<HTMLElement | null>(null);

const toggle = () => (isOpen.value = !isOpen.value);
const close = () => (isOpen.value = false);

const handleClickOutside = (event: MouseEvent) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target as Node)) {
        close();
    }
};

const handleItemClick = (item: DropdownItem) => {
    if (item.action) {
        item.action();
    }
    close();
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});
</script>

<template>
    <div class="relative inline-block text-left" ref="dropdownRef">
        <!-- Trigger -->
        <slot name="trigger" :toggle="toggle" :isOpen="isOpen">
            <button 
                @click="toggle"
                class="inline-flex items-center px-5 py-2.5 bg-surface-low dark:bg-surface-container-low border border-surface-container-high dark:border-outline-variant/30 rounded-xl font-bold text-sm text-on-surface dark:text-white hover:bg-surface-container-high dark:hover:bg-outline-variant/20 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-secondary/50"
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
            </button>
        </slot>

        <!-- Menu -->
        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform scale-95 opacity-0 -translate-y-2"
            enter-to-class="transform scale-100 opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform scale-100 opacity-100 translate-y-0"
            leave-to-class="transform scale-95 opacity-0 -translate-y-2"
        >
            <div 
                v-if="isOpen"
                class="absolute z-50 mt-2 premium-elevated rounded-2xl overflow-hidden py-2 backdrop-blur-xl"
                :class="[
                    width,
                    align === 'right' ? 'right-0' : 'left-0'
                ]"
            >
                <slot>
                    <template v-for="(item, index) in items" :key="index">
                        <!-- Separator -->
                        <div v-if="item.separator" class="my-1 border-t border-surface-container-high dark:border-outline-variant/10"></div>
                        
                        <!-- Link Item -->
                        <Link 
                            v-if="item.href"
                            :href="item.href"
                            class="flex items-center px-4 py-2.5 text-sm font-medium transition-colors duration-200 group"
                            :class="[
                                item.variant === 'error' 
                                    ? 'text-error hover:bg-error/10' 
                                    : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface'
                            ]"
                            @click="close"
                        >
                            <span v-if="item.icon" class="material-symbols-outlined mr-3 text-[18px] opacity-70 group-hover:opacity-100">{{ item.icon }}</span>
                            {{ item.label }}
                        </Link>
                        
                        <!-- Action Item -->
                        <button 
                            v-else
                            @click="handleItemClick(item)"
                            class="w-full flex items-center px-4 py-2.5 text-sm text-left font-medium transition-colors duration-200 group"
                            :class="[
                                item.variant === 'error' 
                                    ? 'text-error hover:bg-error/10' 
                                    : 'text-on-surface-variant hover:bg-surface-container-low hover:text-on-surface'
                            ]"
                        >
                            <span v-if="item.icon" class="material-symbols-outlined mr-3 text-[18px] opacity-70 group-hover:opacity-100">{{ item.icon }}</span>
                            {{ item.label }}
                        </button>
                    </template>
                </slot>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'wght' 500;
}
</style>
