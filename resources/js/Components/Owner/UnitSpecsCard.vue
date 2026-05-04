<script setup lang="ts">
import { computed } from 'vue';

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
    piso: number | null;
    tipo_unidad: TipoUnidad | null;
}

const props = defineProps<{
    unidades: Unidad[];
}>();

const mainUnit = computed(() => props.unidades[0] || null);
const tipo = computed(() => mainUnit.value?.tipo_unidad || null);

// Formatear el nombre de la unidad para el estilo mockup (Torre X - Apto Y)
const unitDisplayName = computed(() => {
    if (!mainUnit.value) return '';
    return `${mainUnit.value.torre ? 'Torre ' + mainUnit.value.torre + ' - ' : ''}${mainUnit.value.nombre}`;
});
</script>

<template>
    <div v-if="mainUnit" class="bg-surface-container-lowest dark:bg-surface-container-low rounded-[2rem] p-6 md:p-8 shadow-sm border border-outline-variant/30 flex flex-col md:flex-row gap-6 items-center justify-between group transition-all hover:shadow-md">
        
        <!-- Left Section: Icon & Identity -->
        <div class="flex items-center gap-5 w-full md:w-auto">
            <div class="w-16 h-16 rounded-2xl bg-primary/5 dark:bg-primary/20 flex items-center justify-center shrink-0 border border-primary/10 transition-transform group-hover:scale-105 duration-500">
                <span class="material-symbols-rounded text-primary text-3xl">apartment</span>
            </div>
            <div>
                <h3 class="text-xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic mb-0.5">{{ unitDisplayName }}</h3>
                <p class="text-[10px] text-on-surface-variant/50 font-black uppercase tracking-[0.2em]">Certificación Residencial Familiar</p>
            </div>
        </div>

        <!-- Right Section: Technical Specs Badges -->
        <div class="flex gap-4 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 no-scrollbar">
            <!-- Area Spec -->
            <div v-if="tipo" class="bg-surface-container dark:bg-white/5 px-4 py-3 rounded-2xl border border-outline-variant/10 min-w-[110px] transition-all hover:border-primary/30">
                <p class="text-[9px] text-on-surface-variant/40 uppercase font-black tracking-widest mb-1.5">Área Total</p>
                <p class="text-lg font-black text-on-surface dark:text-white leading-none tracking-tighter">{{ tipo.area_m2 }} <span class="text-xs opacity-50">M²</span></p>
            </div>

            <!-- Coeficiente Spec -->
            <div class="bg-surface-container dark:bg-white/5 px-4 py-3 rounded-2xl border border-outline-variant/10 min-w-[110px] transition-all hover:border-primary/30">
                <p class="text-[9px] text-on-surface-variant/40 uppercase font-black tracking-widest mb-1.5">Coeficiente</p>
                <p class="text-lg font-black text-on-surface dark:text-white leading-none tracking-tighter">1.25<span class="text-xs opacity-50">%</span></p>
            </div>

            <!-- Components summary (e.g. Hab/Baño) -->
            <div v-if="tipo?.componentes?.length" class="bg-surface-container dark:bg-white/5 px-4 py-3 rounded-2xl border border-outline-variant/10 min-w-[110px] transition-all hover:border-primary/30">
                <p class="text-[9px] text-on-surface-variant/40 uppercase font-black tracking-widest mb-1.5">Distribución</p>
                <div class="flex items-center gap-1.5 font-black text-on-surface dark:text-white text-xs tracking-tighter uppercase italic">
                    <template v-for="(comp, index) in tipo.componentes.slice(0, 2)" :key="comp.id">
                        <span>{{ comp.pivot.cantidad }}{{ comp.nombre.charAt(0) }}</span>
                        <span v-if="index === 0 && tipo.componentes.length > 1" class="opacity-30">/</span>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
