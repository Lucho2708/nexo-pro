<script setup lang="ts">
/**
 * FeaturesSection.vue - Módulos y Soluciones Principales (Nivel Enterprise)
 */
import { onMounted, ref, PropType } from 'vue';
import type { Feature } from '@/types';

const sectionRef = ref<HTMLElement | null>(null);

defineProps({
    features: {
        type: Array as PropType<Feature[]>,
        default: () => [
            {
                icon: 'payments',
                title: 'Recaudo automatizado',
                description: 'Simplifica la cobranza con conciliación automática y múltiples canales de pago seguros para tus residentes.',
                iconBgClass: 'bg-primary-fixed/30 text-primary'
            },
            {
                icon: 'groups',
                title: 'Asambleas virtuales',
                description: 'Votaciones en tiempo real, quórum automático y actas digitales con validez legal garantizada.',
                iconBgClass: 'bg-secondary-fixed/40 text-secondary'
            },
            {
                icon: 'engineering',
                title: 'Mantenimiento predictivo',
                description: 'Anticípate a los fallos con nuestra gestión inteligente de activos y programación de visitas técnicas preventivas.',
                iconBgClass: 'bg-tertiary-fixed/30 text-tertiary'
            }
        ]
    }
});

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const targets = entry.target.querySelectorAll('[data-animate="fade-up"]');
                targets.forEach((el, index) => {
                    setTimeout(() => {
                        el.classList.add('animate-in');
                    }, index * 100);
                });
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });

    if (sectionRef.value) {
        observer.observe(sectionRef.value);
    }
});
</script>

<template>
    <section id="soluciones" ref="sectionRef" class="py-16 sm:py-24 lg:py-28 bg-white dark:bg-[#06080e] overflow-hidden transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-12 sm:mb-20 opacity-0 translate-y-4 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up">
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 rounded-full border border-blue-200/80 dark:border-blue-800/60 mb-4 text-xs font-bold uppercase tracking-wider">
                    <span class="material-symbols-outlined text-sm">hub</span>
                    <span>Ecosistema Integrado</span>
                </div>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-4 tracking-tight">Soluciones que transforman la gestión</h2>
                <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 max-w-2xl mx-auto font-normal">Digitalizamos cada aspecto de su copropiedad con procesos automáticos y seguridad de grado bancario.</p>
            </div>
            
            <div class="space-y-16 sm:space-y-24">
                <!-- FEATURE 1: RECAUDO -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center opacity-0 translate-y-8 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up">
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-blue-500/5 rounded-3xl blur-2xl group-hover:bg-blue-500/10 transition-colors"></div>
                        <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden border border-slate-200/80 dark:border-white/10 shadow-xl bg-slate-900">
                            <img src="/images/landing/recaudo.png" alt="Recaudo Automatizado" class="w-full h-auto transform transition-transform duration-700 group-hover:scale-102" loading="lazy" />
                        </div>
                        <!-- Floating Badge -->
                        <div class="absolute right-2 sm:-right-4 -bottom-3 sm:-bottom-4 bg-white/95 dark:bg-[#111726]/95 backdrop-blur-md shadow-xl p-3 sm:p-4 rounded-xl border border-slate-200/80 dark:border-white/10 z-10">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-xl">payments</span>
                                </div>
                                <div>
                                    <p class="text-[9px] font-bold uppercase text-slate-400 dark:text-slate-500 tracking-wider">Conciliación</p>
                                    <p class="text-xs font-black text-slate-900 dark:text-white">100% Automática</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <span class="text-blue-600 dark:text-blue-400 font-bold uppercase tracking-wider text-xs">Módulo de Finanzas</span>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Recaudo y Cartera Automatizada</h3>
                        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 font-normal leading-relaxed">
                            Olvídese de las conciliaciones manuales. Nuestra plataforma se integra con los principales bancos colombianos para identificar pagos en tiempo real, actualizando la cartera de cada residente al instante y generando comprobantes digitales automáticos.
                        </p>
                        <ul class="space-y-3 pt-2">
                            <li class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">bolt</span>
                                Pagos por PSE, Tarjeta de Crédito y Débito Wompi
                            </li>
                            <li class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400 text-lg">notifications_active</span>
                                Recordatorios de mora automáticos por email y WhatsApp
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- FEATURE 2: ASAMBLEAS -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center opacity-0 translate-y-8 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up">
                    <div class="lg:order-2 relative group">
                        <div class="absolute -inset-4 bg-indigo-500/5 rounded-3xl blur-2xl group-hover:bg-indigo-500/10 transition-colors"></div>
                        <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden border border-slate-200/80 dark:border-white/10 shadow-xl bg-slate-900">
                            <img src="/images/landing/asambleas.png" alt="Asambleas Virtuales" class="w-full h-auto transform transition-transform duration-700 group-hover:scale-102" loading="lazy" />
                        </div>
                    </div>
                    <div class="lg:order-1 space-y-4">
                        <span class="text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-wider text-xs">Democracia Digital</span>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Asambleas Virtuales y Votación</h3>
                        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 font-normal leading-relaxed">
                            Logre el quórum legal sin esfuerzo. Ejecute votaciones en tiempo real con resultados auditables, actas generadas automáticamente y control total de asistencia desde cualquier dispositivo. Cumplimiento total de la Ley 675.
                        </p>
                        <ul class="space-y-3 pt-2">
                            <li class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">
                                <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-lg">analytics</span>
                                Gráficas de votación al instante con trazabilidad
                            </li>
                            <li class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">
                                <span class="material-symbols-outlined text-indigo-600 dark:text-indigo-400 text-lg">security</span>
                                Identidad verificada de propietarios y poderes
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- FEATURE 3: MANTENIMIENTO -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-14 items-center opacity-0 translate-y-8 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up">
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-emerald-500/5 rounded-3xl blur-2xl group-hover:bg-emerald-500/10 transition-colors"></div>
                        <div class="relative rounded-2xl sm:rounded-3xl overflow-hidden border border-slate-200/80 dark:border-white/10 shadow-xl bg-slate-900">
                            <img src="/images/landing/mantenimiento.png" alt="Mantenimiento Predictivo" class="w-full h-auto transform transition-transform duration-700 group-hover:scale-102" loading="lazy" />
                        </div>
                    </div>
                    <div class="space-y-4">
                        <span class="text-emerald-600 dark:text-emerald-400 font-bold uppercase tracking-wider text-xs">Operaciones 360</span>
                        <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Mantenimiento y Control de Activos</h3>
                        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 font-normal leading-relaxed">
                            Prolongue la vida útil de su infraestructura. Centralice la gestión de proveedores, asigne órdenes de trabajo y reciba alertas preventivas antes de que ocurran fallos costosos en ascensores, bombas o plantas eléctricas.
                        </p>
                        <ul class="space-y-3 pt-2">
                            <li class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">
                                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-lg">engineering</span>
                                Seguimiento fotográfico de reparaciones y visitas
                            </li>
                            <li class="flex items-center gap-2.5 text-xs sm:text-sm font-semibold text-slate-800 dark:text-slate-200">
                                <span class="material-symbols-outlined text-emerald-600 dark:text-emerald-400 text-lg">calendar_month</span>
                                Agenda anual de mantenimiento preventivo
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.animate-in {
    opacity: 1 !important;
    transform: translateY(0) !important;
}

.material-symbols-outlined {
    font-variation-settings: 'opsz' 24;
}
</style>
