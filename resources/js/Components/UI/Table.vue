<script setup lang="ts">
import { ref, computed, watch } from 'vue';

interface Column {
    key: string;
    label: string;
    sortable?: boolean;
    sortField?: string;
}

const props = defineProps<{
    columns: Column[];
    data: any[];
    initialItemsPerPage?: number;
    hideLocalPagination?: boolean;
    hideLocalSearch?: boolean;
}>();

const search = ref("");
const sortKey = ref("");
const sortOrder = ref(1); // 1 = ASC, -1 = DESC
const currentPage = ref(1);
const itemsPerPage = ref(props.initialItemsPerPage || 5);

// Computed: Filtros de Búsqueda y Ordenamiento
const processedData = computed(() => {
    let result = [...props.data];

    // 1. Buscador global
    if (search.value) {
        const q = search.value.toLowerCase();
        result = result.filter((row) =>
            Object.values(row).some((val) =>
                String(val).toLowerCase().includes(q)
            )
        );
    }

    // 2. Ordenamiento
    if (sortKey.value) {
        const column = props.columns.find(c => c.key === sortKey.value);
        const sortField = column?.sortField || sortKey.value;

        result.sort((a, b) => {
            let valA = a[sortField];
            let valB = b[sortField];

            // Handle undefined/null
            if (valA === null || valA === undefined) valA = '';
            if (valB === null || valB === undefined) valB = '';

            // Si son strings o numéricos, compararlos de forma natural
            if (typeof valA === 'string' && typeof valB === 'string') {
                return valA.localeCompare(valB) * sortOrder.value;
            }

            if (valA < valB) return -1 * sortOrder.value;
            if (valA > valB) return 1 * sortOrder.value;
            return 0;
        });
    }
    return result;
});

// Computed: Paginación
const totalPages = computed(() =>
    Math.ceil(processedData.value.length / itemsPerPage.value)
);

const paginatedData = computed(() => {
    if (props.hideLocalPagination) {
        return processedData.value;
    }
    const start = (currentPage.value - 1) * itemsPerPage.value;
    return processedData.value.slice(start, start + itemsPerPage.value);
});

// Métodos
const sortBy = (key: string) => {
    if (sortKey.value === key) {
        sortOrder.value *= -1; // Invertir orden
    } else {
        sortKey.value = key;
        sortOrder.value = 1;
    }
};

// Watchers
watch(search, () => {
    currentPage.value = 1;
});
watch(itemsPerPage, () => {
    currentPage.value = 1;
});
</script>

<template>
    <div class="flex flex-col w-full text-left transition-all duration-300">

        <!-- Top Toolbar -->
        <div class="p-6 md:p-8 flex flex-col md:flex-row justify-between items-center gap-6 bg-surface-container-low/50 dark:bg-white/[0.02]">
            <div v-if="!hideLocalSearch" class="w-full md:w-96 relative group">
                <input 
                    v-model="search" 
                    type="text" 
                    aria-label="Buscar registros"
                    placeholder="Filtrar registros locales..." 
                    class="w-full pl-12 pr-4 py-3.5 bg-surface dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-[11px] font-black italic uppercase tracking-widest text-on-surface dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/30 focus:border-primary/50 transition-all placeholder:text-on-surface-variant/30 shadow-sm" 
                />
                <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 opacity-30 group-focus-within:opacity-100 group-focus-within:text-primary transition-all text-[20px]">
                    search
                </span>
            </div>
            
            <div v-if="!hideLocalPagination" class="flex items-center gap-4 text-sm font-bold opacity-80 w-full md:w-auto justify-end">
                <label for="itemsPerPage" class="sr-only">Filas por página</label>
                <span class="uppercase tracking-[0.2em] text-[9px] text-on-surface-variant/60 font-black italic">Renderizar</span>
                <div class="relative">
                    <select 
                        id="itemsPerPage"
                        v-model.number="itemsPerPage" 
                        class="appearance-none bg-surface dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-xl pl-4 pr-10 py-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30 cursor-pointer font-black italic text-[11px] text-primary transition-all hover:border-primary/50 shadow-sm"
                    >
                        <option :value="5">05</option>
                        <option :value="10">10</option>
                        <option :value="25">25</option>
                        <option :value="50">50</option>
                    </select>
                    <span class="material-symbols-rounded absolute right-3 top-1/2 -translate-y-1/2 text-[16px] pointer-events-none opacity-50 text-primary">unfold_more</span>
                </div>
                <span class="uppercase tracking-[0.2em] text-[9px] text-on-surface-variant/60 font-black italic">Filas</span>
            </div>
        </div>

        <!-- Tabla Central -->
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-sm">
                <thead class="bg-surface-container dark:bg-white/[0.03] text-left border-y border-outline-variant/10 dark:border-white/5">
                    <tr>
                        <th 
                            v-for="col in columns" 
                            :key="col.key"
                            @click="col.sortable ? sortBy(col.key) : null"
                            class="px-8 py-5 font-black text-[10px] uppercase tracking-[0.2em] italic text-on-surface-variant/60 dark:text-white/40 whitespace-nowrap transition-colors"
                            :class="{'cursor-pointer hover:text-primary select-none group': col.sortable}"
                        >
                            <div class="flex items-center gap-2">
                                {{ col.label }}
                                <span 
                                    v-if="col.sortable" 
                                    class="material-symbols-rounded text-[18px] transition-all duration-300"
                                    :class="{
                                        'text-primary opacity-100': sortKey === col.key,
                                        'opacity-0 -translate-x-2 group-hover:opacity-40 group-hover:translate-x-0': sortKey !== col.key,
                                        'rotate-180': sortKey === col.key && sortOrder === -1
                                    }"
                                >
                                    keyboard_arrow_up
                                </span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5 dark:divide-white/5">
                    <tr 
                        v-for="(row, index) in paginatedData" 
                        :key="row.id || index" 
                        class="hover:bg-primary/5 dark:hover:bg-primary/10 transition-all duration-300 group border-l-2 border-transparent hover:border-primary relative"
                    >
                        <td 
                            v-for="col in columns" 
                            :key="col.key" 
                            class="px-8 py-5 whitespace-nowrap text-sm text-on-surface/70 dark:text-white/70 group-hover:text-on-surface dark:group-hover:text-white transition-colors"
                        >
                            <slot :name="'cell-' + col.key" :row="row">
                                <span class="font-bold italic text-[11px]">{{ row[col.key] }}</span>
                            </slot>
                        </td>
                    </tr>
                    <tr v-if="paginatedData.length === 0">
                        <td :colspan="columns.length" class="px-8 py-24 text-center">
                            <div class="flex flex-col items-center gap-6 opacity-30 dark:opacity-20">
                                <div class="w-20 h-20 bg-on-surface/5 rounded-[2rem] flex items-center justify-center">
                                    <span class="material-symbols-rounded text-5xl">search_off</span>
                                </div>
                                <p class="text-[11px] font-black uppercase tracking-[0.4em] italic">No se han encontrado registros</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bottom Toolbar (Pagination) -->
        <div v-if="!hideLocalPagination" class="p-6 md:p-8 border-t border-outline-variant/10 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 bg-surface-container-low/50 dark:bg-white/[0.02]">
            <div class="opacity-50 text-[9px] font-black uppercase tracking-[0.2em] italic">
                Renderizando <span class="text-primary tabular-nums">{{ paginatedData.length ? (currentPage - 1) * itemsPerPage + 1 : 0 }}</span> - <span class="text-primary tabular-nums">{{ Math.min(currentPage * itemsPerPage, processedData.length) }}</span> de <span class="text-primary tabular-nums">{{ processedData.length }}</span> nodos
            </div>
            
            <div class="flex gap-3">
                <button 
                    @click="currentPage--" 
                    :disabled="currentPage === 1" 
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl border border-outline-variant/10 dark:border-white/5 bg-surface dark:bg-[#0b0e14] hover:border-primary/30 hover:text-primary disabled:opacity-30 disabled:hover:border-outline-variant/10 disabled:hover:text-inherit disabled:cursor-not-allowed transition-all active:scale-95 shadow-sm text-[10px] font-black uppercase tracking-widest italic group"
                >
                    <span class="material-symbols-rounded text-[16px] group-hover:-translate-x-1 transition-transform">chevron_left</span>
                    Anterior
                </button>
                <div class="flex items-center gap-1 px-4 text-[10px] font-black tabular-nums tracking-widest opacity-40">
                    {{ currentPage }} / {{ totalPages || 1 }}
                </div>
                <button 
                    @click="currentPage++" 
                    :disabled="currentPage === totalPages || totalPages === 0" 
                    class="flex items-center gap-2 px-5 py-2.5 rounded-xl border border-outline-variant/10 dark:border-white/5 bg-surface dark:bg-[#0b0e14] hover:border-primary/30 hover:text-primary disabled:opacity-30 disabled:hover:border-outline-variant/10 disabled:hover:text-inherit disabled:cursor-not-allowed transition-all active:scale-95 shadow-sm text-[10px] font-black uppercase tracking-widest italic group"
                >
                    Siguiente
                    <span class="material-symbols-rounded text-[16px] group-hover:translate-x-1 transition-transform">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.scrollbar-thin::-webkit-scrollbar {
    height: 8px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background: var(--outline-variant);
    opacity: 0.1;
    border-radius: 10px;
}
.dark .scrollbar-thin::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.1);
}

.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
