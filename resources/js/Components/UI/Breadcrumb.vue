<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface BreadcrumbItem {
    label: string;
    href?: string;
    active?: boolean;
}

interface Props {
    items: BreadcrumbItem[];
}

defineProps<Props>();
</script>

<template>
    <nav class="flex text-sm font-bold tracking-tight" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li v-for="(item, index) in items" :key="index" class="inline-flex items-center">
                <div class="flex items-center">
                    <svg v-if="index > 0" class="w-4 h-4 text-on-surface/30 mx-1 md:mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
                    </svg>
                    
                    <Link 
                        v-if="item.href && !item.active" 
                        :href="item.href"
                        class="inline-flex items-center text-on-surface/60 hover:text-secondary dark:text-on-surface/60 dark:hover:text-secondary transition-colors uppercase tracking-widest text-[10px]"
                    >
                        <slot name="icon" v-if="index === 0"></slot>
                        {{ item.label }}
                    </Link>
                    <span 
                        v-else 
                        class="text-on-surface dark:text-white uppercase tracking-widest text-[10px]"
                        :aria-current="item.active ? 'page' : undefined"
                    >
                        {{ item.label }}
                    </span>
                </div>
            </li>
        </ol>
    </nav>
</template>
