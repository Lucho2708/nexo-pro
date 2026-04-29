<script setup lang="ts">
import { ref, computed, watch } from 'vue';

interface Column {
    key: string;
    label: string;
    sortable?: boolean;
}

const props = defineProps<{
    columns: Column[];
    data: any[];
    initialItemsPerPage?: number;
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
        result.sort((a, b) => {
            let valA = a[sortKey.value];
            let valB = b[sortKey.value];

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
    <div class="bg-surface border border-surface-highest rounded-2xl overflow-hidden flex flex-col w-full text-left transition-all duration-300 shadow-sm hover:shadow-md">

        <!-- Top Toolbar -->
        <div class="p-4 border-b border-surface-highest flex flex-col sm:flex-row justify-between items-center gap-4 bg-surface-low/30">
            <div class="w-full sm:w-64 relative group">
                <input 
                    v-model="search" 
                    type="text" 
                    aria-label="Buscar en la tabla"
                    placeholder="Buscar registros..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-surface border border-surface-highest rounded-xl text-sm text-on-surface focus:outline-none focus:ring-2 focus:ring-secondary/30 transition-all placeholder:text-on-surface-variant/40" 
                />
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 opacity-30 group-focus-within:opacity-100 group-focus-within:text-secondary transition-all text-[20px]">
                    search
                </span>
            </div>
            <div class="flex items-center gap-3 text-sm font-bold opacity-80">
                <label for="itemsPerPage" class="sr-only">Filas por página</label>
                <span class="uppercase tracking-widest text-[9px] text-on-surface-variant">Mostrar</span>
                <select 
                    id="itemsPerPage"
                    v-model.number="itemsPerPage" 
                    class="bg-surface border border-surface-highest rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-secondary/30 cursor-pointer font-bold text-xs text-on-surface transition-all hover:border-secondary/50"
                >
                    <option :value="5">5</option>
                    <option :value="10">10</option>
                    <option :value="25">25</option>
                    <option :value="50">50</option>
                </select>
                <span class="uppercase tracking-widest text-[9px] text-on-surface-variant">filas</span>
            </div>
        </div>

        <!-- Tabla Central -->
        <div class="overflow-x-auto scrollbar-thin">
            <table class="w-full text-sm">
                <thead class="bg-surface-low/30 border-b border-surface-highest text-left">
                    <tr>
                        <th 
                            v-for="col in columns" 
                            :key="col.key"
                            @click="col.sortable ? sortBy(col.key) : null"
                            class="px-6 py-5 font-bold text-xs uppercase tracking-widest text-slate-600 dark:text-slate-400 whitespace-nowrap transition-colors"
                            :class="{'cursor-pointer hover:text-secondary select-none': col.sortable}"
                        >
                            <div class="flex items-center gap-2">
                                {{ col.label }}
                                <span v-if="col.sortable" class="flex flex-col transition-all" :class="{'text-secondary opacity-100': sortKey === col.key, 'opacity-20': sortKey !== col.key}">
                                    <span class="material-symbols-outlined text-[14px] leading-none" :class="{'opacity-30': sortKey === col.key && sortOrder === -1}">arrow_drop_up</span>
                                    <span class="material-symbols-outlined text-[14px] leading-none -mt-2" :class="{'opacity-30': sortKey === col.key && sortOrder === 1}">arrow_drop_down</span>
                                </span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-highest/50">
                    <tr 
                        v-for="(row, index) in paginatedData" 
                        :key="row.id || index" 
                        class="hover:bg-primary/5 transition-all duration-300 group border-l-2 border-transparent hover:border-primary relative"
                    >
                        <td 
                            v-for="col in columns" 
                            :key="col.key" 
                            class="px-6 py-5 whitespace-nowrap text-sm font-medium text-on-surface/70 group-hover:text-on-surface transition-colors"
                        >
                            <slot :name="'cell-' + col.key" :row="row">
                                {{ row[col.key] }}
                            </slot>
                        </td>
                    </tr>
                    <tr v-if="paginatedData.length === 0">
                        <td :colspan="columns.length" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-4 opacity-20">
                                <span class="material-symbols-outlined text-6xl">inventory_2</span>
                                <p class="text-[10px] font-black uppercase tracking-[0.3em]">No se han encontrado registros</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Bottom Toolbar -->
        <div class="p-4 border-t border-surface-highest flex flex-col sm:flex-row justify-between items-center gap-4 bg-surface-low/30 text-[10px] font-black uppercase tracking-widest">
            <div class="opacity-50">
                Mostrando {{ paginatedData.length ? (currentPage - 1) * itemsPerPage + 1 : 0 }} - {{ Math.min(currentPage * itemsPerPage, processedData.length) }} de {{ processedData.length }}
            </div>
            <div class="flex gap-2">
                <button 
                    @click="currentPage--" 
                    :disabled="currentPage === 1" 
                    class="flex items-center gap-1 px-4 py-2 rounded-xl border border-surface-highest bg-surface hover:bg-surface-highest disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-95 shadow-sm"
                >
                    <span class="material-symbols-outlined text-[18px]">chevron_left</span>
                    Anterior
                </button>
                <button 
                    @click="currentPage++" 
                    :disabled="currentPage === totalPages || totalPages === 0" 
                    class="flex items-center gap-1 px-4 py-2 rounded-xl border border-surface-highest bg-surface hover:bg-surface-highest disabled:opacity-30 disabled:cursor-not-allowed transition-all active:scale-95 shadow-sm"
                >
                    Siguiente
                    <span class="material-symbols-outlined text-[18px]">chevron_right</span>
                </button>
            </div>
        </div>
    </div>
</template>

<style scoped>
.scrollbar-thin::-webkit-scrollbar {
    height: 6px;
}
.scrollbar-thin::-webkit-scrollbar-track {
    background: transparent;
}
.scrollbar-thin::-webkit-scrollbar-thumb {
    background: var(--surface-highest);
    border-radius: 10px;
}
</style>
