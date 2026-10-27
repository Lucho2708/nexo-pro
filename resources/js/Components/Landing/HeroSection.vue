<script setup lang="ts">
/**
 * HeroSection.vue - Landing Hero Section (Ultra Premium SaaS Architecture)
 * 
 * SOLID Implementation:
 * - SRP: Encapsula la propuesta de valor de alto impacto, pruebas de credibilidad y llamadas a la acción primarias.
 * - OCP: Estructura parametrizada y desacoplada mediante props tipadas.
 */
defineProps({
    title: {
        type: String,
        default: 'La infraestructura digital para copropiedades modernas'
    },
    subtitle: {
        type: String,
        default: 'Automatiza el recaudo bancario en tiempo real, erradica la morosidad y gestiona asambleas con quórum legal auditable conforme a la Ley 675.'
    }
});
</script>

<template>
    <section class="relative pt-24 sm:pt-32 pb-14 sm:pb-20 overflow-hidden bg-white dark:bg-[#06080e] transition-colors duration-300">
        <!-- Fondos Ambientales y Mesh Gradients -->
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_80%_80%_at_50%_-15%,rgba(59,130,246,0.12),rgba(255,255,255,0))] dark:bg-[radial-gradient(ellipse_80%_80%_at_50%_-15%,rgba(99,102,241,0.18),rgba(0,0,0,0))] pointer-events-none"></div>
        <div class="absolute top-1/4 -left-32 w-80 h-80 bg-blue-500/10 rounded-full blur-[110px] pointer-events-none"></div>
        <div class="absolute top-1/3 -right-32 w-80 h-80 bg-indigo-500/10 rounded-full blur-[110px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14 items-center">
                
                <!-- Columna de Conversión / Textos Principales (7 Cols) -->
                <div class="lg:col-span-7 space-y-6 sm:space-y-7 text-center lg:text-left">
                    
                    <!-- Badge de Certificación Normativa Colombia -->
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-100 dark:bg-white/5 border border-slate-200/90 dark:border-white/10 text-slate-800 dark:text-slate-200 text-xs font-semibold shadow-xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-blue-600 dark:text-blue-400 font-bold uppercase tracking-wider text-[10px]">Ley 675/2001</span>
                        <span class="text-slate-300 dark:text-slate-700">|</span>
                        <span>Suite Cloud de Copropiedades</span>
                    </div>
                    
                    <!-- Titular H1 de Alto Impacto -->
                    <h1 class="text-3xl sm:text-5xl lg:text-[3.5rem] font-black text-slate-900 dark:text-white leading-[1.12] tracking-tight">
                        La infraestructura digital <br class="hidden sm:inline" />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-cyan-500">
                            para copropiedades modernas.
                        </span>
                    </h1>
                    
                    <!-- Subtítulo Claro y Conciso -->
                    <p class="text-base sm:text-lg text-slate-600 dark:text-slate-300 max-w-2xl font-normal leading-relaxed mx-auto lg:mx-0">
                        {{ subtitle }}
                    </p>

                    <!-- Píldoras de Valor Clave en Móvil -->
                    <div class="flex flex-wrap items-center justify-center lg:justify-start gap-2 pt-1">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 dark:bg-blue-950/40 border border-blue-200/80 dark:border-blue-800/60 text-blue-700 dark:text-blue-300 text-xs font-semibold">
                            <span class="material-symbols-rounded text-sm">payments</span>
                            <span>Pasarela PSE / Wompi</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200/80 dark:border-indigo-800/60 text-indigo-700 dark:text-indigo-300 text-xs font-semibold">
                            <span class="material-symbols-rounded text-sm">how_to_vote</span>
                            <span>Quórum en Vivo</span>
                        </div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200/80 dark:border-emerald-800/60 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                            <span class="material-symbols-rounded text-sm">trending_up</span>
                            <span>Mora reducida 35%</span>
                        </div>
                    </div>

                    <!-- Botones de Acción Ergonómicos -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-center lg:justify-start gap-3 sm:gap-4 pt-2">
                        <a
                            href="/#contacto"
                            class="flex items-center justify-center gap-2 h-13 px-7 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-extrabold text-sm tracking-wide shadow-lg shadow-blue-600/25 active:scale-[0.98] transition-all min-h-[48px]"
                        >
                            <span class="material-symbols-rounded text-lg">calendar_month</span>
                            <span>Agendar Demostración VIP</span>
                        </a>

                        <a
                            href="/#calculadora-roi"
                            class="flex items-center justify-center gap-2 h-13 px-6 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-white/10 dark:hover:bg-white/15 text-slate-800 dark:text-slate-100 border border-slate-200/80 dark:border-white/10 font-bold text-sm tracking-wide active:scale-[0.98] transition-all min-h-[48px]"
                        >
                            <span class="material-symbols-rounded text-lg text-blue-600 dark:text-blue-400">calculate</span>
                            <span>Calcular Ahorro de tu Conjunto</span>
                        </a>
                    </div>

                    <!-- Métricas de Confianza -->
                    <div class="grid grid-cols-3 gap-4 pt-4 border-t border-slate-200/80 dark:border-white/10 max-w-lg mx-auto lg:mx-0">
                        <div class="text-center lg:text-left">
                            <p class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tabular-nums">+500k</p>
                            <p class="text-[10px] sm:text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Unidades</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums">98.5%</p>
                            <p class="text-[10px] sm:text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Recaudo Efectivo</p>
                        </div>
                        <div class="text-center lg:text-left">
                            <p class="text-xl sm:text-2xl font-black text-blue-600 dark:text-blue-400 tabular-nums">99.9%</p>
                            <p class="text-[10px] sm:text-xs font-medium text-slate-500 dark:text-slate-400 uppercase tracking-wider">Disponibilidad</p>
                        </div>
                    </div>
                </div>

                <!-- Columna Visual Mockup: Software Enterprise en Vivo (5 Cols) -->
                <div class="lg:col-span-5 relative mt-4 lg:mt-0">
                    <div class="relative mx-auto max-w-md sm:max-w-lg lg:max-w-none">
                        
                        <!-- Ventana Tipo Software Dashboard Ultra Premium -->
                        <div class="rounded-2xl bg-white dark:bg-[#0c101a] border border-slate-200/90 dark:border-white/10 shadow-2xl overflow-hidden transition-all duration-300">
                            
                            <!-- Barra de Título de la Aplicación (Traffic Lights) -->
                            <div class="bg-slate-100 dark:bg-[#111726] px-4 py-2.5 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                                <div class="flex items-center gap-1.5">
                                    <div class="w-2.5 h-2.5 rounded-full bg-rose-500/80"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></div>
                                    <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div>
                                    <span class="ml-2 text-[10px] font-semibold text-slate-500 dark:text-slate-400">NexoPro Enterprise • v2.4</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 text-[9px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 px-2 py-0.5 rounded-md">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        En Vivo
                                    </span>
                                </div>
                            </div>

                            <!-- Cuerpo del Dashboard Mockup -->
                            <div class="p-4 sm:p-5 space-y-4">
                                
                                <!-- Tarjeta de Indicador 1: Recaudo Bancario Inmediato -->
                                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-white/[0.03] border border-slate-200/80 dark:border-white/5 flex items-center justify-between">
                                    <div class="space-y-0.5">
                                        <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Recaudo Cartera Mes</p>
                                        <p class="text-lg sm:text-xl font-black text-slate-900 dark:text-white tabular-nums">$184.250.000</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex items-center gap-1 text-[11px] font-extrabold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50 px-2 py-1 rounded-md border border-emerald-200/60 dark:border-emerald-800/60">
                                            <span class="material-symbols-rounded text-xs">arrow_upward</span>
                                            +18.4%
                                        </span>
                                        <p class="text-[9px] font-medium text-slate-400 dark:text-slate-500 mt-1">100% Conciliado</p>
                                    </div>
                                </div>

                                <!-- Tarjeta de Indicador 2: Asamblea en Vivo con Quórum -->
                                <div class="p-3.5 rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 text-white shadow-md shadow-blue-600/20 space-y-2">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                                            <span class="text-xs font-bold">Asamblea Ordinaria 2026</span>
                                        </div>
                                        <span class="text-[10px] font-mono bg-white/20 px-2 py-0.5 rounded-md font-bold">Quórum: 86.4%</span>
                                    </div>
                                    <!-- Barra de Progreso Quórum -->
                                    <div class="w-full bg-black/20 h-2 rounded-full overflow-hidden">
                                        <div class="bg-emerald-400 h-full rounded-full transition-all duration-1000" style="width: 86.4%"></div>
                                    </div>
                                    <div class="flex items-center justify-between text-[10px] text-blue-100 font-medium">
                                        <span>Quórum Legal Mínimo (51%)</span>
                                        <span class="text-emerald-300 font-bold">Superado ✓</span>
                                    </div>
                                </div>

                                <!-- Feed de Transacciones Recientes en Vivo -->
                                <div class="space-y-2 pt-1">
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Actividad en Tiempo Real</p>
                                    
                                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-white/[0.02] border border-slate-200/60 dark:border-white/5 text-xs">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center font-bold text-[10px]">
                                                402
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 dark:text-slate-200">Apto 402 - Torre 1</p>
                                                <p class="text-[10px] text-slate-400">Pago cuota $380.000 (PSE)</p>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">Paz y Salvo Emitido</span>
                                    </div>

                                    <div class="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 dark:bg-white/[0.02] border border-slate-200/60 dark:border-white/5 text-xs">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-[10px]">
                                                801
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 dark:text-slate-200">Apto 801 - Torre 2</p>
                                                <p class="text-[10px] text-slate-400">Voto registrado en Asam.</p>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold text-blue-600 dark:text-blue-400">Auditado SHA-256</span>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Insignia Flotante Acotada: Grado Bancario -->
                        <div class="absolute -top-3 sm:-top-4 -right-2 sm:right-3 bg-white/95 dark:bg-[#151a24]/95 backdrop-blur-md px-3 py-2 rounded-xl shadow-lg border border-slate-200/80 dark:border-white/10 flex items-center gap-2">
                            <div class="w-7 h-7 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                                <span class="material-symbols-rounded text-base">verified_user</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider leading-none">Seguridad</p>
                                <p class="text-xs font-black text-slate-900 dark:text-white uppercase leading-none mt-0.5">Grado Bancario</p>
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
