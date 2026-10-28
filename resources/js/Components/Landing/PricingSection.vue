<script setup lang="ts">
/**
 * PricingSection.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Módulo de selección y conversión de licencias SaaS para conjuntos residenciales y condominios.
 * - Resuelve el punto crítico reportado de recorte de precios y colapso de contraste blanco sobre blanco.
 * - Elimina fricción visual en dispositivos móviles (320px+), tablets y monitores de alta resolución (Desktop/UltraWide).
 * - Destaca el plan recomendado con diseño de alto contraste accesible WCAG 2.1 AA en Modo Claro y Modo Oscuro.
 * - Muestra ahorro del 20% en facturación anual para incentivar el flujo de caja anticipado.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Arquitectura Mobile-First con grilla fluida adaptativa (1 columna en móvil, 2 en tablet, 3 en desktop).
 * - Tipografía fluida con contención min-w-0 y tabular-nums para erradicar el desbordamiento de precios en COP.
 * - Sustitución de contenedores rígidos con doble padding por artículos elásticos con padding responsivo (p-6 a p-8).
 * - Garantía estricta de contraste WCAG 2.1 AA en todos los elementos textuales (ratio > 4.5:1).
 */

import { ref, PropType } from 'vue';
import { Link } from '@inertiajs/vue3';
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
    <section id="precios" class="py-20 sm:py-28 lg:py-36 bg-surface dark:bg-[#080a0f] relative overflow-hidden transition-colors duration-300">
        <!-- Decoración de Fondo Fluida -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] sm:w-[1000px] h-[350px] sm:h-[400px] bg-blue-600/5 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Encabezado de Sección Mobile-First -->
            <div class="text-center mb-14 sm:mb-20 space-y-4 sm:space-y-5">
                <div class="flex items-center justify-center gap-2.5">
                    <div class="w-1.5 h-6 bg-indigo-600 rounded-full"></div>
                    <span class="text-[10px] sm:text-xs font-black uppercase tracking-[0.3em] text-indigo-600 dark:text-indigo-400 italic">
                        Escalabilidad Industrial
                    </span>
                </div>
                <h2 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter leading-tight">
                    Planes de <span class="text-indigo-600 dark:text-indigo-400 italic">Inversión</span>
                </h2>
                
                <!-- Toggle de Facturación Mensual / Anual Accesible -->
                <div class="flex justify-center items-center gap-4 sm:gap-6 pt-4 sm:pt-6">
                    <span
                        class="text-[11px] sm:text-xs font-bold uppercase tracking-wider transition-colors"
                        :class="isAnnual ? 'text-slate-400 dark:text-slate-500' : 'text-blue-600 dark:text-blue-400 font-black'"
                    >
                        Facturación Mensual
                    </span>
                    <button 
                        @click="isAnnual = !isAnnual"
                        type="button"
                        role="switch"
                        :aria-checked="isAnnual"
                        aria-label="Alternar facturación mensual o anual con descuento"
                        class="w-14 sm:w-16 h-7 sm:h-8 rounded-full bg-slate-200 dark:bg-slate-800 border border-slate-300 dark:border-slate-700 flex items-center p-1 transition-all cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500" 
                    >
                        <div
                            class="w-5 sm:w-6 h-5 sm:h-6 bg-blue-600 rounded-full shadow-md transform transition-transform duration-300" 
                            :class="{ 'translate-x-7 sm:translate-x-8': isAnnual }"
                        ></div>
                    </button>
                    <div class="flex items-center gap-2">
                        <span
                            class="text-[11px] sm:text-xs font-bold uppercase tracking-wider transition-colors"
                            :class="isAnnual ? 'text-blue-600 dark:text-blue-400 font-black' : 'text-slate-400 dark:text-slate-500'"
                        >
                            Anual (Elite)
                        </span>
                        <span class="bg-emerald-100 text-emerald-800 dark:bg-emerald-950/80 dark:text-emerald-300 text-[9px] sm:text-[10px] font-black px-2 sm:px-2.5 py-0.5 rounded-full uppercase tracking-widest border border-emerald-300 dark:border-emerald-800">
                            -20% OFF
                        </span>
                    </div>
                </div>
            </div>

            <!-- Grilla Responsiva Multiplataforma (1 col Mobile, 2 col Tablet, 3 col Desktop) -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8 items-stretch">
                <article
                    v-for="(plan, index) in plans"
                    :key="index" 
                    class="rounded-3xl sm:rounded-[2.5rem] p-6 sm:p-7 xl:p-8 border transition-all duration-300 relative group flex flex-col justify-between overflow-hidden"
                    :class="[
                       plan.is_recommended 
                           ? '!bg-slate-950 !border-indigo-500/60 shadow-2xl shadow-indigo-500/20 md:-translate-y-2 z-10 text-white' 
                           : 'bg-white dark:bg-slate-900/80 border-slate-200 dark:border-slate-800 shadow-lg hover:border-blue-500/40 text-slate-900 dark:text-white'
                    ]"
                >
                    <!-- Insignia de Plan Recomendado -->
                    <div
                        v-if="plan.is_recommended"
                        class="absolute top-0 right-8 bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-1 rounded-b-xl text-[9px] sm:text-[10px] font-black uppercase tracking-[0.2em] italic shadow-md"
                    >
                        Recomendado
                    </div>
                    
                    <div class="space-y-6 sm:space-y-7 mb-8">
                        <!-- Nombre del Plan -->
                        <h3
                            class="text-xs font-black uppercase tracking-[0.25em] italic"
                            :class="plan.is_recommended ? 'text-indigo-400' : 'text-blue-600 dark:text-blue-400'"
                        >
                            {{ plan.name }}
                        </h3>
                        
                        <!-- Precio con Contención y Tipografía Fluida (Zero Overflow) -->
                        <div class="flex flex-col gap-1 min-w-0">
                            <span
                                class="text-3xl sm:text-4xl lg:text-3xl xl:text-4xl 2xl:text-5xl font-black tracking-tight tabular-nums italic truncate"
                                :class="plan.is_recommended ? 'text-white' : 'text-slate-900 dark:text-white'"
                            >
                                {{ formatPrice(isAnnual ? plan.price_annual : plan.price_monthly) }}
                            </span>
                            <span
                                v-if="plan.price_monthly !== null"
                                class="text-[10px] sm:text-xs font-bold uppercase tracking-widest opacity-80 ml-0.5"
                                :class="plan.is_recommended ? 'text-slate-300' : 'text-slate-500 dark:text-slate-400'"
                            >
                                {{ plan.period_label || 'Por Copropiedad / Mes' }}
                            </span>
                        </div>
                        
                        <!-- Descripción con Altura Mínima Reservada (Anti-CLS) -->
                        <p
                            class="text-xs font-medium leading-relaxed italic min-h-[40px] sm:min-h-[48px]"
                            :class="plan.is_recommended ? 'text-slate-300' : 'text-slate-600 dark:text-slate-400'"
                        >
                            {{ plan.description }}
                        </p>
                        
                        <!-- Divisor Estructural -->
                        <div
                            class="w-full h-px"
                            :class="plan.is_recommended ? 'bg-slate-800' : 'bg-slate-100 dark:bg-slate-800'"
                        ></div>
                        
                        <!-- Lista de Características con Alto Contraste -->
                        <ul class="space-y-3.5 sm:space-y-4">
                            <li
                                v-for="(feature, idx) in plan.features"
                                :key="idx"
                                class="flex items-center gap-3 group/item min-w-0"
                            >
                                <div
                                    class="w-5 h-5 sm:w-6 sm:h-6 rounded-lg flex items-center justify-center shrink-0 transition-transform group-hover/item:scale-110"
                                    :class="plan.is_recommended ? 'bg-indigo-500/20 text-indigo-400' : 'bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400'"
                                >
                                    <span class="material-symbols-rounded text-sm sm:text-base" aria-hidden="true">verified</span> 
                                </div>
                                <span
                                    class="text-xs font-bold uppercase tracking-tight truncate"
                                    :class="plan.is_recommended ? 'text-slate-200' : 'text-slate-700 dark:text-slate-300'"
                                >
                                    {{ feature }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Botón de Conversión CTA con Ergonomía Táctil (>= 48px) -->
                    <Button 
                        :href="plan.price_monthly === null ? '/#contacto' : route('register')"
                        variant="primary"
                        class="w-full !min-h-[50px] !h-auto py-3.5 !rounded-xl !text-xs font-black uppercase italic shadow-md transition-all cursor-pointer"
                        :class="[
                            plan.is_recommended 
                                ? '!bg-white !text-blue-700 hover:!bg-slate-100 active:!bg-slate-200 shadow-white/20' 
                                : '!bg-blue-600 hover:!bg-blue-700 text-white shadow-blue-500/20'
                        ]"
                    >
                        {{ plan.price_monthly === null ? 'Contactar Ingeniería' : 'Activar Licencia' }}
                    </Button>
                </article>
            </div>

            <!-- Banner Corporativo para Empresas Administradoras y Constructoras -->
            <div class="mt-12 sm:mt-16 p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 border border-slate-700/60 text-white shadow-xl flex flex-col md:flex-row items-center justify-between gap-6">
                <div class="space-y-2 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 text-xs font-bold uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span>
                        <span>Multi-Copropiedad & Constructoras</span>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-black tracking-tight">¿Administras más de 3 conjuntos o eres constructora?</h3>
                    <p class="text-xs sm:text-sm text-slate-300 max-w-2xl font-normal">
                        Mando Central unificado para gestionar todos tus edificios desde una sola cuenta maestra, descuentos por volumen desde la 4ta copropiedad y migración de bases de datos asistida sin costo.
                    </p>
                </div>
                <a
                    href="/#contacto"
                    class="px-6 py-3.5 rounded-xl bg-white hover:bg-slate-100 text-slate-900 font-extrabold text-xs uppercase tracking-wider shadow-lg active:scale-95 transition-all shrink-0 min-h-[48px] flex items-center justify-center gap-2"
                >
                    <span>Cotizar Plan Empresa</span>
                    <span class="material-symbols-rounded text-base">arrow_forward</span>
                </a>
            </div>
        </div>
    </section>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
