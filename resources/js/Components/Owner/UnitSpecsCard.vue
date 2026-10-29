<script setup lang="ts">
import { ref, computed } from 'vue';
import Badge from '@/Components/UI/Badge.vue';

interface Componente {
    id: number;
    nombre: string;
    pivot: {
        cantidad: number;
    };
}

interface TipoUnidad {
    id: string;
    nombre: string;
    area_m2: number;
    componentes: Componente[];
}

interface Unidad {
    id: string;
    nombre: string;
    torre: string | null;
    piso: number | string | null;
    coeficiente?: number | string | null;
    saldo_actual?: number;
    tipo_unidad?: TipoUnidad | null;
}

const props = defineProps<{
    unidades: Unidad[];
    twoFactorEnabled?: boolean;
}>();

const selectedUnitId = ref<string | null>(props.unidades?.[0]?.id || null);

const activeUnit = computed(() => {
    if (!props.unidades || props.unidades.length === 0) return null;
    return props.unidades.find(u => u.id === selectedUnitId.value) || props.unidades[0];
});

const tipo = computed(() => activeUnit.value?.tipo_unidad || null);

const formatUnitName = (unidad: Unidad | null) => {
    if (!unidad) return '';
    const torre = unidad.torre?.trim();
    if (!torre) return unidad.nombre;
    const prefix = /^torre\b/i.test(torre) ? '' : 'Torre ';
    return `${prefix}${torre} - ${unidad.nombre}`;
};

const formatCurrency = (value?: number) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(value || 0);
};
</script>

<template>
    <!-- Empty State -->
    <div v-if="!unidades || unidades.length === 0" class="bg-surface-container-lowest dark:bg-surface-container-low rounded-[2rem] p-6 md:p-8 border border-outline-variant/30 text-center flex flex-col items-center justify-center gap-3">
        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
            <span class="material-symbols-rounded text-2xl">apartment</span>
        </div>
        <p class="text-sm font-bold text-on-surface">No se encontraron unidades asignadas a tu cuenta en este conjunto.</p>
        <p class="text-xs text-on-surface-variant/60">Comunícate con la administración para asociar tu inmueble al censo residencial.</p>
    </div>

    <!-- Active Unit Specs Card -->
    <div v-else class="bg-surface-container-lowest dark:bg-surface-container-low rounded-[2rem] p-6 md:p-8 shadow-sm border border-outline-variant/30 flex flex-col gap-6 group transition-all hover:shadow-md">
        
        <!-- Multi-Unit Selector Tabs if owner has > 1 unit -->
        <div v-if="unidades.length > 1" class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-outline-variant/10 pb-4">
            <div class="flex items-center gap-2">
                <span class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest">Tus Inmuebles ({{ unidades.length }})</span>
            </div>
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-1 sm:pb-0">
                <button
                    v-for="u in unidades"
                    :key="u.id"
                    type="button"
                    @click="selectedUnitId = u.id"
                    class="px-3.5 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer flex items-center gap-2 border"
                    :class="activeUnit?.id === u.id 
                        ? 'bg-primary text-white border-primary shadow-sm' 
                        : 'bg-surface-container text-on-surface-variant border-outline-variant/10 hover:border-primary/30'"
                >
                    <span class="material-symbols-rounded text-sm">home</span>
                    <span>{{ formatUnitName(u) }}</span>
                    <span v-if="(u.saldo_actual || 0) > 0" class="w-2 h-2 rounded-full bg-amber-400"></span>
                </button>
            </div>
        </div>

        <!-- Main Spec Overview -->
        <div class="flex flex-col lg:flex-row gap-6 items-start lg:items-center justify-between">
            <!-- Left Section: Icon & Identity -->
            <div class="flex items-center gap-4 sm:gap-5 w-full lg:w-auto">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-primary/10 dark:bg-primary/20 flex items-center justify-center shrink-0 border border-primary/20 transition-transform group-hover:scale-105 duration-300">
                    <span class="material-symbols-rounded text-primary text-2xl sm:text-3xl">apartment</span>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="text-lg sm:text-xl font-black text-on-surface dark:text-white tracking-tight uppercase">
                            {{ formatUnitName(activeUnit) }}
                        </h3>
                        <Badge :variant="(activeUnit?.saldo_actual || 0) > 0 ? 'warning' : 'success'" class="!text-[9px] uppercase tracking-wider">
                            {{ (activeUnit?.saldo_actual || 0) > 0 ? 'Saldo: ' + formatCurrency(activeUnit?.saldo_actual) : 'Al Día' }}
                        </Badge>
                    </div>
                    <p class="text-[10px] text-on-surface-variant/60 font-bold uppercase tracking-wider mt-0.5">
                        Inmueble Residencial · Piso {{ activeUnit?.piso || '1' }}
                    </p>
                </div>
            </div>

            <!-- Right Section: Technical Specs Badges -->
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 w-full lg:w-auto">
                <!-- Area Spec -->
                <div class="bg-surface-container dark:bg-white/5 px-4 py-3 rounded-2xl border border-outline-variant/10 transition-all hover:border-primary/30">
                    <p class="text-[9px] text-on-surface-variant/50 uppercase font-black tracking-widest mb-1">Área Privada</p>
                    <p class="text-base sm:text-lg font-black text-on-surface dark:text-white leading-none tracking-tight">
                        {{ tipo?.area_m2 || '65' }} <span class="text-xs font-bold opacity-60">M²</span>
                    </p>
                </div>

                <!-- Coeficiente Spec -->
                <div class="bg-surface-container dark:bg-white/5 px-4 py-3 rounded-2xl border border-outline-variant/10 transition-all hover:border-primary/30">
                    <p class="text-[9px] text-on-surface-variant/50 uppercase font-black tracking-widest mb-1">Coeficiente</p>
                    <p class="text-base sm:text-lg font-black text-on-surface dark:text-white leading-none tracking-tight">
                        {{ activeUnit?.coeficiente || '1.25' }}<span class="text-xs font-bold opacity-60">%</span>
                    </p>
                </div>

                <!-- Components summary or Status -->
                <div class="bg-surface-container dark:bg-white/5 px-4 py-3 rounded-2xl border border-outline-variant/10 transition-all hover:border-primary/30 col-span-2 sm:col-span-1">
                    <p class="text-[9px] text-on-surface-variant/50 uppercase font-black tracking-widest mb-1">Distribución</p>
                    <div v-if="tipo?.componentes?.length" class="flex items-center gap-1.5 font-black text-on-surface dark:text-white text-xs tracking-tight uppercase">
                        <template v-for="(comp, index) in tipo.componentes.slice(0, 3)" :key="comp.id">
                            <span>{{ comp.pivot.cantidad }}{{ comp.nombre.charAt(0) }}</span>
                            <span v-if="index < Math.min(tipo.componentes.length - 1, 2)" class="opacity-30">/</span>
                        </template>
                    </div>
                    <div v-else class="text-xs font-bold text-on-surface-variant/70">
                        Habitacional
                    </div>
                </div>
            </div>
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
