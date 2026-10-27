<script setup lang="ts">
/**
 * HeroSection.vue - Landing Hero Section (Mobile-First High Conversion)
 * 
 * SOLID Implementation:
 * - SRP: Encargado de la propuesta de valor principal, credibilidad inmediata y conversión inicial.
 * - OCP: Estructura parametrizada vía props para título y subtítulo.
 */
import { Link } from '@inertiajs/vue3';

defineProps({
    title: {
        type: String,
        default: 'Control Total de tu Copropiedad'
    },
    subtitle: {
        type: String,
        default: 'Automatiza el recaudo, elimina la morosidad y gestiona asambleas con quórum legal en tiempo real conforme a la Ley 675.'
    }
});
</script>

<template>
    <section class="relative pt-24 sm:pt-32 pb-14 sm:pb-24 lg:pb-32 overflow-hidden bg-gradient-to-b from-slate-50 via-white to-slate-50 dark:from-[#0b0e14] dark:via-[#0e121a] dark:to-[#0b0e14]">
        <!-- Resplandores de Fondo Suaves y Acotados -->
        <div class="absolute top-0 right-0 w-72 sm:w-[600px] h-72 sm:h-[600px] bg-blue-500/10 blur-[100px] sm:blur-[140px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-60 sm:w-[500px] h-60 sm:h-[500px] bg-indigo-500/10 blur-[90px] sm:blur-[130px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                
                <!-- Columna de Conversión / Texto (7 Cols) -->
                <div class="lg:col-span-7 space-y-6 sm:space-y-8 text-center lg:text-left">
                    
                    <!-- Badge de Confianza / Cumplimiento Normativo -->
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-50 dark:bg-blue-950/60 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 text-xs font-bold tracking-wide shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
                        <span class="material-symbols-rounded text-sm">verified</span>
                        <span>Plataforma N° 1 Conforme a Ley 675 en Colombia</span>
                    </div>
                    
                    <!-- Titular H1 Mobile-First -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black text-slate-900 dark:text-white leading-[1.12] tracking-tight">
                        {{ title }}
                        <span class="block mt-1 text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500">
                            NEXO-PRO
                        </span>
                    </h1>
                    
                    <!-- Subtítulo de Alto Valor -->
                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl font-normal leading-relaxed mx-auto lg:mx-0">
                        {{ subtitle }}
                    </p>

                    <!-- Píldoras de Credibilidad Rápida en Móvil -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 sm:gap-3 pt-1">
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-[11px] font-bold">
                            <span class="material-symbols-rounded text-sm">payments</span>
                            <span>Recaudo PSE / Wompi</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-300 text-[11px] font-bold">
                            <span class="material-symbols-rounded text-sm">how_to_vote</span>
                            <span>Asambleas en Vivo</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300 text-[11px] font-bold">
                            <span class="material-symbols-rounded text-sm">trending_up</span>
                            <span>Mora reducida 35%</span>
                        </div>
                    </div>

                    <!-- Botones de Acción (Touch Targets Ergonómicos >= 48px) -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-2">
                        <a
                            href="/#contacto"
                            class="flex items-center justify-center gap-2 h-13 px-7 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold text-sm uppercase tracking-wider shadow-lg shadow-blue-600/25 hover:shadow-blue-600/40 active:scale-[0.98] transition-all text-center min-h-[48px]"
                        >
                            <span class="material-symbols-rounded text-lg">calendar_month</span>
                            <span>Agendar Demo Gratis</span>
                        </a>

                        <a
                            href="/#calculadora-roi"
                            class="flex items-center justify-center gap-2 h-13 px-6 rounded-xl bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-100 hover:bg-slate-50 dark:hover:bg-slate-800/80 border border-slate-300 dark:border-slate-700 font-bold text-sm tracking-wide active:scale-[0.98] transition-all text-center min-h-[48px]"
                        >
                            <span class="material-symbols-rounded text-lg text-blue-600 dark:text-blue-400">calculate</span>
                            <span>Calcular Ahorro ROI</span>
                        </a>
                    </div>

                    <!-- Métricas de Confianza (Stats) -->
                    <div class="grid grid-cols-3 gap-4 pt-4 border-t border-slate-200/80 dark:border-white/10 max-w-lg mx-auto lg:mx-0">
                        <div class="text-center lg:text-left">
                            <p class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tabular-nums">+500k</p>
                            <p class="text-[10px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Unidades</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums">98.5%</p>
                            <p class="text-[10px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Recaudo</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-xl sm:text-2xl font-black text-blue-600 dark:text-blue-400 tabular-nums">99.9%</p>
                            <p class="text-[10px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Uptime</p>
                        </div>
                    </div>
                </div>

                <!-- Columna Visual Mockup Responsive (5 Cols) -->
                <div class="lg:col-span-5 relative mt-4 lg:mt-0">
                    <div class="relative mx-auto max-w-md sm:max-w-lg lg:max-w-none">
                        <!-- Tarjeta Central Mockup -->
                        <div class="rounded-3xl p-2.5 sm:p-3 bg-gradient-to-b from-slate-200/60 to-slate-300/40 dark:from-white/10 dark:to-white/5 border border-slate-200/80 dark:border-white/10 shadow-2xl backdrop-blur-xl">
                            <div class="rounded-2xl overflow-hidden aspect-[16/10] bg-slate-900 relative shadow-inner">
                                <img 
                                    src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=1200&auto=format&fit=crop" 
                                    alt="Vista Previa de la Plataforma NEXO-PRO" 
                                    loading="lazy"
                                    class="w-full h-full object-cover opacity-90 hover:scale-105 transition-transform duration-700"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent flex items-end p-4">
                                    <div class="flex items-center justify-between w-full text-white text-xs">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <span class="font-bold">Mando Central Activo</span>
                                        </div>
                                        <span class="font-mono text-[10px] text-white/70">v2.4 Live</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Insignia Flotante Superior (Seguridad) Acotada -->
                        <div class="absolute -top-3 sm:-top-5 right-2 sm:right-4 bg-white/95 dark:bg-[#151a24]/95 backdrop-blur-md p-3 rounded-2xl shadow-xl border border-slate-200/80 dark:border-white/10 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <span class="material-symbols-rounded text-lg">shield</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Cifrado</p>
                                <p class="text-xs font-black text-slate-900 dark:text-white uppercase">AES-256</p>
                            </div>
                        </div>

                        <!-- Insignia Flotante Inferior (Cartera) Acotada -->
                        <div class="absolute -bottom-3 sm:-bottom-5 left-2 sm:left-4 bg-white/95 dark:bg-[#151a24]/95 backdrop-blur-md p-3 rounded-2xl shadow-xl border border-slate-200/80 dark:border-white/10 flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                                <span class="material-symbols-rounded text-lg">insights</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Cartera</p>
                                <p class="text-xs font-black text-emerald-600 dark:text-emerald-400 uppercase">+35% Recaudo</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
