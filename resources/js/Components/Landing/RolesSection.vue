<script setup lang="ts">
import { ref } from 'vue';
import Card from '@/Components/UI/Card.vue';

const activeRole = ref<'admin' | 'owner'>('admin');

const adminFeatures = [
    {
        title: 'Mando Central Financiero',
        desc: 'Visualización táctica de ingresos, egresos y proyecciones en tiempo real.',
        icon: 'analytics',
        color: 'bg-primary/10 text-primary'
    },
    {
        title: 'Gestión de Cartera Inteligente',
        desc: 'Liquidación automática de cuotas y control de mora con notificaciones push.',
        icon: 'account_balance_wallet',
        color: 'bg-secondary/10 text-secondary'
    },
    {
        title: 'Dispatcher de Reservas',
        desc: 'Control total de zonas comunes con sistema de aprobación y política de uso.',
        icon: 'calendar_month',
        color: 'bg-tertiary/10 text-tertiary'
    },
    {
        title: 'Auditoría Forense (Logs)',
        desc: 'Trazabilidad absoluta de cada acción realizada por el staff y residentes.',
        icon: 'rule',
        color: 'bg-emerald-500/10 text-emerald-500'
    },
    {
        title: 'Centro de Ingeniería',
        desc: 'Soporte técnico directo con ingenieros de Nexo-Pro para reportes de sistema.',
        icon: 'engineering',
        color: 'bg-orange-500/10 text-orange-500'
    }
];

const ownerFeatures = [
    {
        title: 'Pasarela Nexo-Pay',
        desc: 'Paga tu administración en segundos desde tu móvil con total seguridad.',
        icon: 'credit_card',
        color: 'bg-primary/10 text-primary'
    },
    {
        title: 'Reservas Express',
        desc: 'Agenda salones, canchas y zonas sociales en tiempo real desde la App.',
        icon: 'event_available',
        color: 'bg-secondary/10 text-secondary'
    },
    {
        title: 'Muro de Copropiedad',
        desc: 'Comunicados oficiales y noticias de tu unidad residencial al instante.',
        icon: 'forum',
        color: 'bg-tertiary/10 text-tertiary'
    },
    {
        title: 'Estado de Cuenta Digital',
        desc: 'Consulta tu saldo y descarga paz y salvos sin procesos administrativos.',
        icon: 'description',
        color: 'bg-emerald-500/10 text-emerald-500'
    },
    {
        title: 'Soporte de Usuario',
        desc: 'Reporta problemas técnicos y recibe asistencia prioritaria del equipo de ingenieros.',
        icon: 'support_agent',
        color: 'bg-orange-500/10 text-orange-500'
    }
];
</script>

<template>
    <section class="py-32 bg-white dark:bg-[#080a0f] overflow-hidden">
        <div class="max-w-7xl mx-auto px-6">
            
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-12 mb-24">
                <div class="space-y-6">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-8 bg-primary rounded-full"></div>
                        <span class="text-[10px] font-black uppercase tracking-[0.5em] text-primary italic">Arquitectura de Roles</span>
                    </div>
                    <h2 class="text-5xl md:text-6xl font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none">
                        Una plataforma, <br/> <span class="text-primary italic">dos mundos</span>
                    </h2>
                </div>
                
                <!-- Selector de Roles Táctico -->
                <div class="flex p-2 bg-surface-container-low dark:bg-white/5 rounded-[2rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl">
                    <button 
                        @click="activeRole = 'admin'"
                        :class="[
                            'px-10 py-5 rounded-[1.5rem] text-[11px] font-black uppercase tracking-widest transition-all italic',
                            activeRole === 'admin' ? 'bg-primary text-white shadow-xl shadow-primary/20 scale-105' : 'text-on-surface-variant/40 hover:text-on-surface'
                        ]"
                    >
                        Para Administradores
                    </button>
                    <button 
                        @click="activeRole = 'owner'"
                        :class="[
                            'px-10 py-5 rounded-[1.5rem] text-[11px] font-black uppercase tracking-widest transition-all italic',
                            activeRole === 'owner' ? 'bg-primary text-white shadow-xl shadow-primary/20 scale-105' : 'text-on-surface-variant/40 hover:text-on-surface'
                        ]"
                    >
                        Para Propietarios
                    </button>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="relative min-h-[600px]">
                <!-- Background Decoration -->
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary/10 rounded-full blur-[120px] pointer-events-none opacity-50"></div>
                <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-secondary/10 rounded-full blur-[120px] pointer-events-none opacity-50"></div>

                <div v-show="activeRole === 'admin'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <Card v-for="(feat, i) in adminFeatures" :key="i" class="!p-10 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-xl hover:shadow-primary/20 transition-all duration-700 group relative overflow-hidden bg-white dark:bg-[#0b0e14]">
                        <!-- Spot Glow Effect -->
                        <div class="absolute -inset-24 bg-primary/5 blur-[80px] rounded-full translate-x-full translate-y-full group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-1000 pointer-events-none"></div>
                        
                        <div class="relative z-10">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-10 transition-all group-hover:scale-110 group-hover:rotate-3 duration-500 shadow-lg" :class="feat.color">
                                <span class="material-symbols-rounded text-3xl">{{ feat.icon }}</span>
                            </div>
                            <h3 class="text-xl font-black text-on-surface dark:text-white uppercase italic tracking-tighter mb-4">{{ feat.title }}</h3>
                            <p class="text-sm font-medium text-on-surface-variant/60 dark:text-white/40 leading-relaxed italic">{{ feat.desc }}</p>
                        </div>
                    </Card>
                    
                    <!-- Call to Action Card -->
                    <Card class="!p-10 !rounded-[3rem] bg-primary flex flex-col justify-center items-center text-center text-white space-y-8 shadow-2xl shadow-primary/20 hover:scale-[1.02] transition-transform duration-500">
                         <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center animate-pulse">
                            <span class="material-symbols-rounded text-4xl">rocket_launch</span>
                         </div>
                         <h3 class="text-2xl font-black uppercase italic tracking-tighter">¿Listo para tomar el control total?</h3>
                         <button class="w-full py-5 bg-white text-primary rounded-2xl text-[11px] font-black uppercase italic shadow-xl hover:bg-neutral-100 transition-all">Empezar ahora</button>
                    </Card>
                </div>

                <div v-show="activeRole === 'owner'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <Card v-for="(feat, i) in ownerFeatures" :key="i" class="!p-10 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-xl hover:shadow-secondary/20 transition-all duration-700 group relative overflow-hidden bg-white dark:bg-[#0b0e14]">
                        <!-- Spot Glow Effect -->
                        <div class="absolute -inset-24 bg-secondary/5 blur-[80px] rounded-full translate-x-full translate-y-full group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-1000 pointer-events-none"></div>

                        <div class="relative z-10">
                            <div class="w-16 h-16 rounded-2xl flex items-center justify-center mb-10 transition-all group-hover:scale-110 group-hover:rotate-3 duration-500 shadow-lg" :class="feat.color">
                                <span class="material-symbols-rounded text-3xl">{{ feat.icon }}</span>
                            </div>
                            <h3 class="text-xl font-black text-on-surface dark:text-white uppercase italic tracking-tighter mb-4">{{ feat.title }}</h3>
                            <p class="text-sm font-medium text-on-surface-variant/60 dark:text-white/40 leading-relaxed italic">{{ feat.desc }}</p>
                        </div>
                    </Card>

                    <!-- User Experience Card -->
                    <Card class="!p-10 !rounded-[3rem] bg-secondary flex flex-col justify-center items-center text-center text-white space-y-8 shadow-2xl shadow-secondary/20 hover:scale-[1.02] transition-transform duration-500">
                         <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center animate-pulse">
                            <span class="material-symbols-rounded text-4xl">visibility</span>
                         </div>
                         <h3 class="text-2xl font-black uppercase italic tracking-tighter">La tranquilidad que tu familia merece</h3>
                         <button class="w-full py-5 bg-white text-secondary rounded-2xl text-[11px] font-black uppercase italic shadow-xl hover:bg-neutral-100 transition-all">Ver la Experiencia</button>
                    </Card>
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
