<script setup lang="ts">
import { ref, PropType } from 'vue';
import { Link } from '@inertiajs/vue3';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import type { Plan } from '@/types';

defineProps({
    plans: {
        type: Array as PropType<Plan[]>,
        required: true
    }
});

const isAnnual = ref(false);

const formatPrice = (price: number | null) => {
    if (price === null) return 'CUSTOM';
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(price);
};
</script>

<template>
    <section id="precios" class="py-40 bg-surface dark:bg-[#080a0f] relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[400px] bg-primary/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <div class="text-center mb-24 space-y-6">
                <div class="flex items-center justify-center gap-3">
                    <div class="w-2 h-8 bg-secondary rounded-full"></div>
                    <span class="text-[10px] font-black uppercase tracking-[0.5em] text-secondary italic">Escalabilidad Industrial</span>
                </div>
                <h2 class="text-5xl md:text-7xl font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none">
                    Planes de <span class="text-secondary italic">Inversión</span>
                </h2>
                
                <!-- Tactical Billing Toggle -->
                <div class="flex justify-center items-center gap-6 pt-8">
                    <span class="text-[10px] font-black uppercase tracking-widest transition-all" :class="isAnnual ? 'text-white/20' : 'text-primary'">Facturación Mensual</span>
                    <button 
                        @click="isAnnual = !isAnnual" 
                        class="w-16 h-8 rounded-full bg-white/5 border border-white/10 flex items-center p-1 transition-all relative outline-none" 
                    >
                        <div class="w-6 h-6 bg-primary rounded-full shadow-lg transform transition-transform duration-500" 
                             :class="{ 'translate-x-8': isAnnual }"></div>
                    </button>
                    <div class="flex items-center gap-3">
                        <span class="text-[10px] font-black uppercase tracking-widest transition-all" :class="isAnnual ? 'text-primary' : 'text-white/20'">Anual (Elite Focus)</span>
                        <span class="bg-emerald-500/10 text-emerald-500 text-[8px] font-black px-3 py-1 rounded-lg uppercase tracking-widest border border-emerald-500/20">
                            -20% OFF
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 items-stretch">
                <Card v-for="(plan, index) in plans" :key="index" 
                    class="!p-12 !rounded-[3.5rem] border transition-all duration-700 relative group flex flex-col justify-between"
                    :class="[
                       plan.is_recommended 
                           ? 'bg-primary dark:bg-primary border-primary shadow-2xl shadow-primary/20 scale-105 z-10' 
                           : 'bg-white dark:bg-white/[0.02] border-outline-variant/10 dark:border-white/5 shadow-xl hover:border-primary/30'
                    ]">
                    
                    <div v-if="plan.is_recommended" class="absolute -top-6 left-1/2 -translate-x-1/2 bg-secondary text-white px-8 py-2 rounded-2xl text-[10px] font-black uppercase tracking-[0.3em] italic shadow-2xl">
                        Monitor Recomendado
                    </div>
                    
                    <div class="space-y-8 mb-12">
                        <h4 class="text-[11px] font-black uppercase tracking-[0.4em] italic shadow-sm" :class="plan.is_recommended ? 'text-white/60' : 'text-primary'">
                            {{ plan.name }}
                        </h4>
                        
                        <div class="flex flex-col gap-1">
                            <span class="text-5xl font-black tracking-tighter tabular-nums italic" :class="plan.is_recommended ? 'text-white' : 'text-on-surface dark:text-white'">
                                {{ formatPrice(isAnnual ? plan.price_annual : plan.price_monthly) }}
                            </span>
                            <span v-if="plan.price_monthly !== null" class="text-[10px] font-bold uppercase tracking-widest opacity-40 ml-1">Por Unidad / Mes</span>
                        </div>
                        
                        <p class="text-xs font-semibold leading-relaxed italic h-12" :class="plan.is_recommended ? 'text-white/70' : 'text-on-surface-variant/60 dark:text-white/30'">
                            {{ plan.description }}
                        </p>
                        
                        <div class="w-full h-px bg-white/10"></div>
                        
                        <ul class="space-y-6">
                            <li v-for="(feature, idx) in plan.features" :key="idx" class="flex items-center gap-4 group/item">
                                <div class="w-6 h-6 rounded-lg flex items-center justify-center transition-all group-hover/item:scale-110" :class="plan.is_recommended ? 'bg-white/10 text-white' : 'bg-primary/10 text-primary'">
                                    <span class="material-symbols-rounded text-base">verified</span> 
                                </div>
                                <span class="text-[11px] font-bold uppercase tracking-tighter" :class="plan.is_recommended ? 'text-white/90' : 'text-on-surface/80 dark:text-white/60'">{{ feature }}</span>
                            </li>
                        </ul>
                    </div>
                    
                    <Button 
                        :href="plan.price_monthly === null ? '/#contacto' : route('register')"
                        variant="primary"
                        class="w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl transition-all"
                        :class="[
                            plan.is_recommended 
                                ? 'bg-white !text-primary hover:bg-neutral-100 shadow-white/10' 
                                : 'shadow-primary/20'
                        ]"
                    >
                        {{ plan.price_monthly === null ? 'Contactar Ingeniería' : 'Activar Licencia' }}
                    </Button>
                </Card>
            </div>
        </div>
    </section>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
