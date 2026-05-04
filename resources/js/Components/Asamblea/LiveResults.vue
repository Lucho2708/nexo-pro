<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';
import { useAsambleaRealtime } from '@/Composables/useAsambleaRealtime';

const props = defineProps<{
    asambleaId: string;
    question: any;
}>();

const results = ref<any[]>([]);
const totalWeight = ref(0);
const totalUnits = ref(0);
const loading = ref(true);

const fetchResults = async () => {
    if (!props.question) return;
    loading.value = true;
    try {
        const response = await axios.get(`/asambleas/preguntas/${props.question.id}/results`);
        results.value = response.data.results;
        totalWeight.value = Number(response.data.total_participating_weight);
        totalUnits.value = response.data.total_participating_units;
    } catch (e) {
        console.error('Error fetching results', e);
    } finally {
        loading.value = false;
    }
};

// Listen for the custom event from the parent or use Echo directly here
// For simplicity, we can just listen to the broadcast channel if we are using Echo
import Echo from 'laravel-echo';

onMounted(() => {
    fetchResults();
    
    // Si queremos que este componente sea totalmente independiente, 
    // pero ya tenemos Echo en useAsambleaRealtime. 
    // Podemos usar un bus de eventos o simplemente escuchar el canal aquí también.
    if (typeof window !== 'undefined' && (window as any).Echo) {
        (window as any).Echo.private(`asamblea.${props.asambleaId}`)
            .listen('.VoteCast', (e: any) => {
                // Update local state live!
                // Buscamos si ya existe la opción en los resultados locales
                const existing = results.value.find(r => r.opcion_id === e.pregunta.opcion_id);
                // Nota: e.voteData contiene total_peso
                // Sin embargo, es más seguro re-fetch o actualizar localmente.
                // Hagamos una actualización local rápida:
                fetchResults(); // Por ahora re-fetch para asegurar consistencia con la DB
            });
    }
});

watch(() => props.question, () => {
    fetchResults();
});

const getOptionPercentage = (opcionId: number) => {
    const result = results.value.find(r => r.opcion_id === opcionId);
    if (!result || totalWeight.value === 0) return 0;
    return (result.total_peso / totalWeight.value) * 100;
};

const getOptionWeight = (opcionId: number) => {
    const result = results.value.find(r => r.opcion_id === opcionId);
    return result ? Number(result.total_peso).toFixed(4) : '0.0000';
};
</script>

<template>
    <div class="space-y-6 animate-in fade-in slide-in-from-right-4 duration-500">
        <div class="flex items-center justify-between mb-2">
            <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em]">Distribución de Votos</h4>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-[8px] font-black text-emerald-400 uppercase">En Vivo</span>
            </div>
        </div>

        <!-- Quorum Stats -->
        <div class="grid grid-cols-2 gap-3 mb-6">
            <div class="p-4 bg-white/5 border border-white/10 rounded-2xl">
                <p class="text-[8px] font-black text-white/20 uppercase tracking-widest leading-none mb-2">Quórum Participante</p>
                <p class="text-lg font-black text-white tabular-nums">{{ totalWeight.toFixed(4) }}%</p>
            </div>
            <div class="p-4 bg-white/5 border border-white/10 rounded-2xl">
                <p class="text-[8px] font-black text-white/20 uppercase tracking-widest leading-none mb-2">Unidades</p>
                <p class="text-lg font-black text-white tabular-nums">{{ totalUnits }}</p>
            </div>
        </div>

        <!-- Options Bars -->
        <div v-if="question" class="space-y-4">
            <div v-for="opcion in question.opciones" :key="opcion.id" class="space-y-2">
                <div class="flex justify-between items-end px-1">
                    <span class="text-[10px] font-black text-white uppercase tracking-tight truncate max-w-[70%]">
                        {{ opcion.titulo }}
                    </span>
                    <span class="text-[10px] font-black text-primary tabular-nums">
                        {{ getOptionWeight(opcion.id) }}%
                    </span>
                </div>
                
                <div class="h-3 bg-white/5 rounded-full overflow-hidden border border-white/5 relative group">
                    <div 
                        class="absolute inset-y-0 left-0 bg-gradient-to-r from-primary to-primary/60 transition-all duration-1000 ease-out shadow-[0_0_15px_rgba(var(--color-primary-rgb),0.3)]"
                        :style="{ width: `${getOptionPercentage(opcion.id)}%` }"
                    ></div>
                </div>
                
                <div class="flex justify-end">
                    <span class="text-[8px] font-bold text-white/20 uppercase tracking-widest">
                        {{ Math.round(getOptionPercentage(opcion.id)) }}% del total emitido
                    </span>
                </div>
            </div>
        </div>

        <div v-if="loading" class="py-12 flex flex-col items-center justify-center opacity-20">
            <div class="w-8 h-8 border-2 border-primary/20 border-t-primary rounded-full animate-spin mb-4"></div>
            <p class="text-[8px] font-black uppercase tracking-widest">Calculando resultados...</p>
        </div>

        <div v-if="!question" class="py-20 text-center opacity-10">
            <span class="material-symbols-rounded text-4xl block mb-2">analytics</span>
            <p class="text-[8px] font-black uppercase tracking-widest">No hay una pregunta activa</p>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
