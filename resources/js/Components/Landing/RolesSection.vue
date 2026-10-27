<script setup lang="ts">
/**
 * RolesSection.vue - Arquitectura de Roles B2B & B2C
 * 
 * SOLID Implementation:
 * - SRP: Muestra los beneficios y funcionalidades diferenciadas por tipo de actor (Administradores vs Propietarios).
 * - OCP: Listas de características desacopladas y extensibles mediante arrays tipados.
 */
import { ref } from 'vue';
import Card from '@/Components/UI/Card.vue';

const activeRole = ref<'admin' | 'owner'>('admin');

interface RoleFeature {
    title: string;
    desc: string;
    icon: string;
    color: string;
}

const adminFeatures: RoleFeature[] = [
    {
        title: 'Mando Central Financiero',
        desc: 'Visualización táctica de ingresos, egresos y proyecciones en tiempo real.',
        icon: 'analytics',
        color: 'bg-blue-500/10 text-blue-600 dark:text-blue-400'
    },
    {
        title: 'Gestión de Cartera Inteligente',
        desc: 'Liquidación automática de cuotas y control de mora con notificaciones push.',
        icon: 'account_balance_wallet',
        color: 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    },
    {
        title: 'Dispatcher de Reservas',
        desc: 'Control total de zonas comunes con sistema de aprobación y política de uso.',
        icon: 'calendar_month',
        color: 'bg-purple-500/10 text-purple-600 dark:text-purple-400'
    },
    {
        title: 'Auditoría Forense (Logs)',
        desc: 'Trazabilidad absoluta de cada acción realizada por el staff y residentes.',
        icon: 'rule',
        color: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
    },
    {
        title: 'Centro de Ingeniería',
        desc: 'Soporte técnico directo con ingenieros de Nexo-Pro para reportes de sistema.',
        icon: 'engineering',
        color: 'bg-amber-500/10 text-amber-600 dark:text-amber-400'
    }
];

const ownerFeatures: RoleFeature[] = [
    {
        title: 'Pasarela Nexo-Pay',
        desc: 'Paga tu administración en segundos desde tu móvil con total seguridad.',
        icon: 'credit_card',
        color: 'bg-blue-500/10 text-blue-600 dark:text-blue-400'
    },
    {
        title: 'Reservas Express',
        desc: 'Agenda salones, canchas y zonas sociales en tiempo real desde la App.',
        icon: 'event_available',
        color: 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400'
    },
    {
        title: 'Muro de Copropiedad',
        desc: 'Comunicados oficiales y noticias de tu unidad residencial al instante.',
        icon: 'forum',
        color: 'bg-purple-500/10 text-purple-600 dark:text-purple-400'
    },
    {
        title: 'Estado de Cuenta Digital',
        desc: 'Consulta tu saldo y descarga paz y salvos sin procesos administrativos.',
        icon: 'description',
        color: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400'
    },
    {
        title: 'Soporte de Usuario',
        desc: 'Reporta problemas técnicos y recibe asistencia prioritaria del equipo de ingenieros.',
        icon: 'support_agent',
        color: 'bg-amber-500/10 text-amber-600 dark:text-amber-400'
    }
];
</script>

<template>
    <section class="py-16 sm:py-24 lg:py-32 bg-white dark:bg-[#080a0f] overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Section Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12 sm:mb-16">
                <div class="space-y-4 text-center sm:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 text-xs font-bold uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        <span>Experiencia por Rol</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white uppercase italic tracking-tight leading-none">
                        Una plataforma, <br/> <span class="text-blue-600 dark:text-blue-400 italic">dos mundos integrados</span>
                    </h2>
                </div>
                
                <!-- Selector de Roles Móvil Ergonómico (Touch Targets >= 44px) -->
                <div class="w-full sm:w-auto max-w-sm sm:max-w-none mx-auto sm:mx-0 grid grid-cols-2 p-1.5 rounded-2xl bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 shadow-sm">
                    <button 
                        type="button"
                        @click="activeRole = 'admin'"
                        :class="[
                            'px-4 sm:px-8 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all min-h-[44px] flex items-center justify-center text-center',
                            activeRole === 'admin' 
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' 
                                : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                        ]"
                    >
                        Administración
                    </button>
                    <button 
                        type="button"
                        @click="activeRole = 'owner'"
                        :class="[
                            'px-4 sm:px-8 py-3 rounded-xl text-xs font-black uppercase tracking-wider transition-all min-h-[44px] flex items-center justify-center text-center',
                            activeRole === 'owner' 
                                ? 'bg-blue-600 text-white shadow-md shadow-blue-600/30' 
                                : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                        ]"
                    >
                        Residentes
                    </button>
                </div>
            </div>

            <!-- Features Grid -->
            <div class="relative">
                <!-- Resplandor ambiental contenido -->
                <div class="absolute -top-12 -right-12 w-72 h-72 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-12 -left-12 w-72 h-72 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

                <!-- Grid Administradores -->
                <div v-show="activeRole === 'admin'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <div 
                        v-for="(feat, i) in adminFeatures" 
                        :key="i" 
                        class="p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200 dark:border-white/10 shadow-lg hover:shadow-xl transition-all duration-300 bg-white dark:bg-[#0b0e14] group relative overflow-hidden flex flex-col justify-between"
                    >
                        <div>
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110 duration-300" :class="feat.color">
                                <span class="material-symbols-rounded text-2xl">{{ feat.icon }}</span>
                            </div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase italic tracking-tight mb-2">{{ feat.title }}</h3>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 leading-relaxed">{{ feat.desc }}</p>
                        </div>
                    </div>
                    
                    <!-- Tarjeta de Conversión Administrador -->
                    <div class="p-6 sm:p-8 rounded-2xl sm:rounded-3xl bg-gradient-to-br from-blue-600 to-indigo-700 flex flex-col justify-center items-center text-center text-white space-y-6 shadow-xl shadow-blue-600/25">
                         <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <span class="material-symbols-rounded text-3xl">rocket_launch</span>
                         </div>
                         <div>
                            <h3 class="text-xl font-black uppercase italic tracking-tight">¿Listo para modernizar tu administración?</h3>
                            <p class="text-xs text-blue-100 mt-1">Configuración en menos de 48 horas con migración guiada.</p>
                         </div>
                         <a 
                            href="/#contacto" 
                            class="w-full py-3.5 px-4 bg-white text-blue-700 rounded-xl text-xs font-black uppercase tracking-wider shadow-lg active:scale-95 transition-all text-center min-h-[44px] flex items-center justify-center"
                         >
                            Solicitar Demo Personalizada
                         </a>
                    </div>
                </div>

                <!-- Grid Propietarios -->
                <div v-show="activeRole === 'owner'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <div 
                        v-for="(feat, i) in ownerFeatures" 
                        :key="i" 
                        class="p-6 sm:p-8 rounded-2xl sm:rounded-3xl border border-slate-200 dark:border-white/10 shadow-lg hover:shadow-xl transition-all duration-300 bg-white dark:bg-[#0b0e14] group relative overflow-hidden flex flex-col justify-between"
                    >
                        <div>
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-6 transition-transform group-hover:scale-110 duration-300" :class="feat.color">
                                <span class="material-symbols-rounded text-2xl">{{ feat.icon }}</span>
                            </div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase italic tracking-tight mb-2">{{ feat.title }}</h3>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-400 leading-relaxed">{{ feat.desc }}</p>
                        </div>
                    </div>

                    <!-- Tarjeta de Experiencia Residente -->
                    <div class="p-6 sm:p-8 rounded-2xl sm:rounded-3xl bg-gradient-to-br from-indigo-600 to-slate-900 flex flex-col justify-center items-center text-center text-white space-y-6 shadow-xl shadow-indigo-600/25">
                         <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center">
                            <span class="material-symbols-rounded text-3xl">smartphone</span>
                         </div>
                         <div>
                            <h3 class="text-xl font-black uppercase italic tracking-tight">Experiencia Móvil para Residentes</h3>
                            <p class="text-xs text-indigo-200 mt-1">Pagos, reservas y asambleas desde la palma de tu mano.</p>
                         </div>
                         <a 
                            href="/#contacto" 
                            class="w-full py-3.5 px-4 bg-white text-indigo-900 rounded-xl text-xs font-black uppercase tracking-wider shadow-lg active:scale-95 transition-all text-center min-h-[44px] flex items-center justify-center"
                         >
                            Recomendar a mi Administrador
                         </a>
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
