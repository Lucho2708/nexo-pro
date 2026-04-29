<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    currentPage: number;
    totalPages: number;
    maxVisible?: number;
}

const props = withDefaults(defineProps<Props>(), {
    maxVisible: 5,
});

const emit = defineEmits(['update:currentPage']);

const setPage = (page: number) => {
    if (page >= 1 && page <= props.totalPages && page !== props.currentPage) {
        emit('update:currentPage', page);
    }
};

const pages = computed(() => {
    const range: (number | string)[] = [];
    const start = Math.max(1, props.currentPage - Math.floor(props.maxVisible / 2));
    const end = Math.min(props.totalPages, start + props.maxVisible - 1);

    if (start > 1) {
        range.push(1);
        if (start > 2) range.push('...');
    }

    for (let i = start; i <= end; i++) {
        range.push(i);
    }

    if (end < props.totalPages) {
        if (end < props.totalPages - 1) range.push('...');
        range.push(props.totalPages);
    }

    return range;
});
</script>

<template>
    <nav class="flex items-center justify-center space-x-2 py-4" aria-label="Pagination">
        <!-- Previous -->
        <button 
            @click="setPage(currentPage - 1)"
            :disabled="currentPage === 1"
            class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-low dark:bg-surface-container-low border border-surface-container-high dark:border-outline-variant/30 text-on-surface dark:text-white hover:bg-surface-container-high dark:hover:bg-outline-variant/20 disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-90"
            aria-label="Anterior"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path></svg>
        </button>

        <!-- Pages -->
        <div class="flex items-center gap-1.5">
            <template v-for="(page, index) in pages" :key="index">
                <span 
                    v-if="page === '...'"
                    class="w-8 text-center text-on-surface/40 dark:text-white/40 font-bold"
                >
                    {{ page }}
                </span>
                <button 
                    v-else
                    @click="setPage(page as number)"
                    class="w-10 h-10 rounded-xl text-sm font-black transition-all duration-300 active:scale-95"
                    :class="[
                        page === currentPage 
                            ? 'bg-primary text-on-primary shadow-lg dark:bg-secondary dark:text-primary' 
                            : 'bg-surface-low dark:bg-surface-container-low border border-surface-container-high dark:border-outline-variant/30 text-on-surface dark:text-white hover:bg-surface-container-high dark:hover:bg-outline-variant/20'
                    ]"
                    :aria-current="page === currentPage ? 'page' : undefined"
                >
                    {{ page }}
                </button>
            </template>
        </div>

        <!-- Next -->
        <button 
            @click="setPage(currentPage + 1)"
            :disabled="currentPage === totalPages"
            class="w-10 h-10 flex items-center justify-center rounded-xl bg-surface-low dark:bg-surface-container-low border border-surface-container-high dark:border-outline-variant/30 text-on-surface dark:text-white hover:bg-surface-container-high dark:hover:bg-outline-variant/20 disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-90"
            aria-label="Siguiente"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
        </button>
    </nav>
</template>
