<script setup lang="ts">
import { onMounted, ref, PropType } from 'vue';
import type { Feature } from '@/types';
import FeatureCard from './FeatureCard.vue';

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
    <section id="soluciones" ref="sectionRef" class="py-32 bg-surface overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            <!-- Section Header -->
            <div class="text-center mb-32 opacity-0 translate-y-4 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-secondary/5 text-secondary rounded-full border border-secondary/10 mb-6">
                    <span class="material-symbols-outlined text-sm">hub</span>
                    <span class="text-[10px] font-black uppercase tracking-[0.2em]">Ecosistema Integrado</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-black text-primary mb-6 tracking-tight uppercase leading-none">Soluciones que transforman la gestión</h2>
                <p class="text-lg text-on-surface-variant max-w-2xl mx-auto font-medium">Digitalizamos cada aspecto de su copropiedad con procesos automáticos y seguridad de grado bancario.</p>
            </div>
            
            <div class="space-y-40">
                <!-- FEATURE 1: RECAUDO -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center opacity-0 translate-y-8 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up">
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-primary/5 rounded-[3rem] blur-2xl group-hover:bg-primary/10 transition-colors"></div>
                        <div class="relative premium-card overflow-hidden rounded-[2.5rem] border border-white/40 shadow-2xl">
                            <img src="/images/landing/recaudo.png" alt="Recaudo Automatizado" class="w-full h-auto transform transition-transform duration-1000 group-hover:scale-105" />
                        </div>
                        <!-- Floating Badge -->
                        <div class="absolute -right-6 -bottom-6 bg-surface shadow-2xl p-4 rounded-2xl border border-outline-variant/10 z-10 animate-bounce duration-[4s]">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-success/10 text-success flex items-center justify-center">
                                    <span class="material-symbols-outlined">payments</span>
                                </div>
                                <div>
                                    <p class="text-[8px] font-black uppercase text-slate-400 tracking-widest">Conciliación</p>
                                    <p class="text-xs font-black text-primary">100% Automática</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <span class="text-primary font-black uppercase tracking-[0.3em] text-[10px]">Módulo de Finanzas</span>
                        <h3 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none">Recaudo y Cartera Automatizada</h3>
                        <p class="text-on-surface-variant font-medium leading-relaxed">
                            Olvídese de las conciliaciones manuales. Nuestra plataforma se integra con los principales bancos colombianos para identificar pagos en tiempo real, actualizando la cartera de cada residente al instante y generando comprobantes digitales automáticos.
                        </p>
                        <ul class="space-y-4 pt-4">
                            <li class="flex items-center gap-3 text-sm font-semibold text-on-surface">
                                <span class="material-symbols-outlined text-primary text-xl">bolt</span>
                                Pagos por PSE, Tarjeta y Efecty
                            </li>
                            <li class="flex items-center gap-3 text-sm font-semibold text-on-surface">
                                <span class="material-symbols-outlined text-primary text-xl">notifications_active</span>
                                Recordatorios de mora automáticos
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- FEATURE 2: ASAMBLEAS -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center opacity-0 translate-y-8 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up">
                    <div class="lg:order-2 relative group">
                        <div class="absolute -inset-4 bg-secondary/5 rounded-[3rem] blur-2xl group-hover:bg-secondary/10 transition-colors"></div>
                        <div class="relative premium-card overflow-hidden rounded-[2.5rem] border border-white/40 shadow-2xl">
                            <img src="/images/landing/asambleas.png" alt="Asambleas Virtuales" class="w-full h-auto transform transition-transform duration-1000 group-hover:scale-105" />
                        </div>
                    </div>
                    <div class="lg:order-1 space-y-6">
                        <span class="text-secondary font-black uppercase tracking-[0.3em] text-[10px]">Democracia Digital</span>
                        <h3 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none">Asambleas Virtuales y Votación</h3>
                        <p class="text-on-surface-variant font-medium leading-relaxed">
                            Logre el quórum legal sin esfuerzo. Ejecute votaciones en tiempo real con resultados auditables, actas generadas automáticamente y control total de asistencia desde cualquier dispositivo. Cumplimiento total de la Ley 675.
                        </p>
                        <ul class="space-y-4 pt-4">
                            <li class="flex items-center gap-3 text-sm font-semibold text-on-surface">
                                <span class="material-symbols-outlined text-secondary text-xl">analytics</span>
                                Gráficas de votación al instante
                            </li>
                            <li class="flex items-center gap-3 text-sm font-semibold text-on-surface">
                                <span class="material-symbols-outlined text-secondary text-xl">security</span>
                                Identidad verificada de propietarios
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- FEATURE 3: MANTENIMIENTO -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center opacity-0 translate-y-8 transition-all duration-1000 data-[animate]:animate-in" data-animate="fade-up">
                    <div class="relative group">
                        <div class="absolute -inset-4 bg-tertiary/5 rounded-[3rem] blur-2xl group-hover:bg-tertiary/10 transition-colors"></div>
                        <div class="relative premium-card overflow-hidden rounded-[2.5rem] border border-white/40 shadow-2xl">
                            <img src="/images/landing/mantenimiento.png" alt="Mantenimiento Predictivo" class="w-full h-auto transform transition-transform duration-1000 group-hover:scale-105" />
                        </div>
                    </div>
                    <div class="space-y-6">
                        <span class="text-tertiary font-black uppercase tracking-[0.3em] text-[10px]">Operaciones 360</span>
                        <h3 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none">Mantenimiento y Control de Activos</h3>
                        <p class="text-on-surface-variant font-medium leading-relaxed">
                            Prolongue la vida útil de su infraestructura. Centralice la gestión de proveedores, asigne órdenes de trabajo y reciba alertas preventivas antes de que ocurran fallos costosos en ascensores, bombas o plantas eléctricas.
                        </p>
                        <ul class="space-y-4 pt-4">
                            <li class="flex items-center gap-3 text-sm font-semibold text-on-surface">
                                <span class="material-symbols-outlined text-tertiary text-xl">engineering</span>
                                Seguimiento fotográfico de reparaciones
                            </li>
                            <li class="flex items-center gap-3 text-sm font-semibold text-on-surface">
                                <span class="material-symbols-outlined text-tertiary text-xl">calendar_month</span>
                                Agenda anual de visitas técnicas
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
