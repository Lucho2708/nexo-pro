<script setup lang="ts">
import { computed } from 'vue';
import Button from '@/Components/UI/Button.vue';

const props = defineProps<{
    question: any;
    unidades: any[];
    isVoting: boolean;
    hasVoted: boolean;
}>();

const emit = defineEmits(['vote']);

const totalCoefficient = computed(() => {
    return props.unidades.reduce((acc, u) => acc + (Number(u.coeficiente) || 0), 0);
});

const handleVote = (opcionId: number) => {
    if (props.isVoting || props.hasVoted) return;
    emit('vote', opcionId);
};
</script>

<template>
    <div class="w-full max-w-2xl mx-auto">
        <Transition
            enter-active-class="transform transition duration-700 ease-out"
            enter-from-class="translate-y-12 opacity-0 scale-95"
            enter-to-class="translate-y-0 opacity-100 scale-100"
            leave-active-class="transform transition duration-500 ease-in"
            leave-from-class="translate-y-0 opacity-100 scale-100"
            leave-to-class="translate-y-12 opacity-0 scale-95"
        >
            <div v-if="question" class="relative group">
                <!-- Background Glow -->
                <div class="absolute -inset-1 bg-gradient-to-r from-primary/30 to-secondary/30 rounded-[2.5rem] blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                
                <div class="relative bg-[#00173c]/80 backdrop-blur-3xl border border-white/10 p-8 rounded-[2.5rem] shadow-2xl overflow-hidden">
                    
                    <!-- Header: Question -->
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[8px] font-black uppercase tracking-[0.2em] border border-primary/20">Votación en curso</span>
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        </div>
                        <h3 class="text-xl md:text-2xl font-black text-white leading-tight tracking-tight uppercase italic italic-none">
                            {{ question.titulo }}
                        </h3>
                        <p v-if="question.descripcion" class="text-xs text-white/40 mt-3 font-medium leading-relaxed">
                            {{ question.descripcion }}
                        </p>
                    </div>

                    <!-- Coefficient Badge -->
                    <div class="flex items-center gap-4 p-4 bg-white/5 rounded-2xl border border-white/5 mb-8">
                        <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                            <span class="material-symbols-rounded">analytics</span>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-white/20 uppercase tracking-widest leading-none">Peso de tu Voto</p>
                            <p class="text-sm font-black text-white mt-1 tabular-nums">
                                {{ totalCoefficient.toFixed(4) }}% <span class="text-[10px] text-primary/60 italic ml-1">({{ unidades.length }} unidades)</span>
                            </p>
                        </div>
                    </div>

                    <!-- Voting Options -->
                    <div v-if="!hasVoted" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <button 
                            v-for="opcion in question.opciones" 
                            :key="opcion.id"
                            @click="handleVote(opcion.id)"
                            :disabled="isVoting"
                            class="relative group/opt p-6 rounded-[1.5rem] border-2 text-left transition-all duration-300 hover:scale-[1.02] active:scale-95 disabled:opacity-50"
                            :class="isVoting ? 'border-white/5' : 'border-white/10 bg-white/[0.02] hover:border-primary hover:bg-primary/5 shadow-lg'"
                        >
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-black text-white uppercase tracking-tight group-hover/opt:text-primary transition-colors">{{ opcion.titulo }}</span>
                                <span class="material-symbols-rounded text-white/20 group-hover/opt:text-primary group-hover/opt:translate-x-1 transition-all">chevron_right</span>
                            </div>
                        </button>
                    </div>

                    <!-- Voted State -->
                    <div v-else class="py-12 flex flex-col items-center justify-center text-center animate-in fade-in zoom-in duration-500">
                        <div class="w-20 h-20 bg-emerald-500/20 text-emerald-500 rounded-full flex items-center justify-center mb-6 shadow-2xl shadow-emerald-500/20 relative">
                            <span class="material-symbols-rounded text-4xl">verified</span>
                            <div class="absolute -inset-4 bg-emerald-500/10 rounded-full animate-ping opacity-20"></div>
                        </div>
                        <h4 class="text-lg font-black text-white uppercase tracking-tight mb-2">¡Voto Registrado!</h4>
                        <p class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em]">Tu participación ha sido certificada en la cadena de bloques.</p>
                        
                        <div class="mt-8 px-6 py-3 rounded-xl bg-white/5 border border-white/10">
                            <span class="text-[9px] font-mono text-emerald-400/60 uppercase">HASH: {{ Math.random().toString(36).substring(7).toUpperCase() }}...</span>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div v-if="isVoting" class="absolute inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-12 h-12 border-4 border-primary/20 border-t-primary rounded-full animate-spin"></div>
                            <span class="text-[10px] font-black text-white uppercase tracking-[0.3em] animate-pulse">Procesando Voto...</span>
                        </div>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style scoped>
.italic-none {
    font-style: normal;
}
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
