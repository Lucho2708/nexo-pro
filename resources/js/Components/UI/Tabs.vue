<script setup lang="ts">
import { ref } from 'vue';

interface Tab {
    label: string;
    id?: string | number;
}

interface Props {
    tabs: Tab[];
    defaultTab?: number;
}

const props = withDefaults(defineProps<Props>(), {
    defaultTab: 0,
});

const activeTab = ref(props.defaultTab);

const selectTab = (index: number) => {
    activeTab.value = index;
};
</script>

<template>
    <div class="w-full">
        <!-- Tab Headers -->
        <div class="border-b border-surface-container-high dark:border-outline-variant/30 flex space-x-1 md:space-x-8 overflow-x-auto no-scrollbar scroll-smooth">
            <button 
                v-for="(tab, index) in tabs" 
                :key="tab.id || index"
                @click="selectTab(index)"
                class="pb-4 px-2 text-sm font-bold transition-all duration-300 relative whitespace-nowrap focus:outline-none group"
                :class="activeTab === index ? 'text-secondary dark:text-secondary' : 'text-on-surface/50 dark:text-on-surface/50 hover:text-on-surface dark:hover:text-white'"
            >
                <div class="flex items-center gap-2">
                    <slot :name="'header-' + index">
                        {{ tab.label }}
                    </slot>
                </div>
                
                <!-- Animated Indicator -->
                <transition
                    enter-active-class="transition-all duration-300 ease-out"
                    enter-from-class="scale-x-0 opacity-0"
                    enter-to-class="scale-x-100 opacity-100"
                    leave-active-class="transition-all duration-200 ease-in"
                    leave-from-class="scale-x-100 opacity-100"
                    leave-to-class="scale-x-0 opacity-0"
                >
                    <div 
                        v-if="activeTab === index" 
                        class="absolute bottom-0 left-0 right-0 h-1 bg-brand-gradient shadow-[0_0_12px_rgba(0,212,255,0.4)] rounded-t-full"
                    ></div>
                </transition>
            </button>
        </div>

        <!-- Tab Content -->
        <div class="py-6">
            <transition
                name="fade"
                mode="out-in"
                enter-active-class="transition-opacity duration-300 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div :key="activeTab" class="text-sm text-on-surface/80 dark:text-on-surface/90 leading-relaxed">
                    <slot :name="'tab-' + activeTab">
                        <slot></slot>
                    </slot>
                </div>
            </transition>
        </div>
    </div>
</template>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
