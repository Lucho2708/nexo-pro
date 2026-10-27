<script setup lang="ts">
/**
 * RolesSection.vue - Arquitectura de Roles B2B & B2C (Nivel Enterprise)
 * 
 * SOLID Implementation:
 * - SRP: Presenta la especialización de valor según el rol del actor (Administración vs Residentes).
 * - OCP: Catálogo modular de capacidades desacoplado y extensible vía interfaces fuertemente tipadas.
 */
import { ref } from 'vue';

const activeRole = ref<'admin' | 'owner'>('admin');

interface RoleFeature {
    title: string;
    desc: string;
    tag: string;
    icon: string;
    gradient: string;
    iconColor: string;
}

const adminFeatures: RoleFeature[] = [
    {
        title: 'Mando Central Financiero',
        desc: 'Visualización ejecutiva de ingresos, egresos presupuestados y conciliación bancaria automática con extractos.',
        tag: 'Finanzas 360°',
        icon: 'analytics',
        gradient: 'from-blue-500/10 to-indigo-500/10',
        iconColor: 'text-blue-600 dark:text-blue-400 bg-blue-500/10'
    },
    {
        title: 'Gestión de Cartera y Cobro Persuasivo',
        desc: 'Liquidación periódica, cálculo exacto de intereses de mora y recordatorios automatizados vía WhatsApp y email.',
        tag: 'Mora -35%',
        icon: 'account_balance_wallet',
        gradient: 'from-emerald-500/10 to-teal-500/10',
        iconColor: 'text-emerald-600 dark:text-emerald-400 bg-emerald-500/10'
    },
    {
        title: 'Asambleas con Quórum Ley 675',
        desc: 'Convocatoria digital, registro biométrico de asistencia, votación secreta u ordinaria y acta generada con firma.',
        tag: '100% Legal',
        icon: 'how_to_vote',
        gradient: 'from-purple-500/10 to-pink-500/10',
        iconColor: 'text-purple-600 dark:text-purple-400 bg-purple-500/10'
    },
    {
        title: 'Dispatcher Inteligente de Reservas',
        desc: 'Políticas de uso para salones sociales, BBQ y zonas húmedas con tarifas, comprobante de depósito y aforos.',
        tag: 'Control Comunitario',
        icon: 'event_available',
        gradient: 'from-amber-500/10 to-orange-500/10',
        iconColor: 'text-amber-600 dark:text-amber-400 bg-amber-500/10'
    },
    {
        title: 'Auditoría Forense y Trazabilidad',
        desc: 'Registro criptográfico inmutable de cada transacción, cambio de coeficiente y voto para máxima tranquilidad del Consejo.',
        tag: 'Seguridad Bancaria',
        icon: 'verified_user',
        gradient: 'from-cyan-500/10 to-blue-500/10',
        iconColor: 'text-cyan-600 dark:text-cyan-400 bg-cyan-500/10'
    }
];

const ownerFeatures: RoleFeature[] = [
    {
        title: 'Pasarela Nexo-Pay Directa',
        desc: 'Paga tus expensas en 30 segundos con PSE, Tarjeta de Crédito o Nequi y recibe tu paz y salvo al instante.',
        tag: 'Cero Filas',
        icon: 'credit_card',
        gradient: 'from-blue-500/10 to-indigo-500/10',
        iconColor: 'text-blue-600 dark:text-blue-400 bg-blue-500/10'
    },
    {
        title: 'Reservas Express desde el Celular',
        desc: 'Revisa disponibilidad en tiempo real de zonas comunes, agenda en segundos y recibe confirmación con QR de acceso.',
        tag: 'En 1 Toque',
        icon: 'smartphone',
        gradient: 'from-emerald-500/10 to-teal-500/10',
        iconColor: 'text-emerald-600 dark:text-emerald-400 bg-emerald-500/10'
    },
    {
        title: 'Votación Remota en Asambleas',
        desc: 'Participa y vota con validez legal desde cualquier lugar del mundo sin interrupciones ni suplantaciones.',
        tag: 'Voto Seguro',
        icon: 'touch_app',
        gradient: 'from-purple-500/10 to-pink-500/10',
        iconColor: 'text-purple-600 dark:text-purple-400 bg-purple-500/10'
    },
    {
        title: 'Muro Comunitario y Citofonía Virtual',
        desc: 'Notificaciones oficiales del administrador, alertas de paquetería en portería y autorizaciones de visitantes.',
        tag: 'Comunicación Total',
        icon: 'forum',
        gradient: 'from-amber-500/10 to-orange-500/10',
        iconColor: 'text-amber-600 dark:text-amber-400 bg-amber-500/10'
    },
    {
        title: 'Paz y Salvos Digitales 24/7',
        desc: 'Descarga certificados contables con firma digital y código de verificación para trámites notariales o bancarios.',
        tag: 'Autoservicio',
        icon: 'description',
        gradient: 'from-cyan-500/10 to-blue-500/10',
        iconColor: 'text-cyan-600 dark:text-cyan-400 bg-cyan-500/10'
    }
];
</script>

<template>
    <section class="py-16 sm:py-24 lg:py-28 bg-slate-50/70 dark:bg-[#070a12] transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Encabezado de Sección Limpio y Sofisticado -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12 sm:mb-16">
                <div class="space-y-3 text-center sm:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 text-xs font-bold uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                        <span>Arquitectura de Roles</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 dark:text-white tracking-tight">
                        Diseñado para quien administra. <br class="hidden sm:inline" />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">
                            Pensado para quien habita.
                        </span>
                    </h2>
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-400 max-w-xl font-normal">
                        Dos experiencias independientes diseñadas con precisión para eliminar las fricciones diarias entre administración y copropietarios.
                    </p>
                </div>
                
                <!-- Conmutador de Roles Ergonómico -->
                <div class="w-full sm:w-auto max-w-sm sm:max-w-none mx-auto sm:mx-0 grid grid-cols-2 p-1 rounded-xl bg-slate-200/80 dark:bg-white/10 border border-slate-300/60 dark:border-white/10 shadow-inner">
                    <button 
                        type="button"
                        @click="activeRole = 'admin'"
                        :class="[
                            'flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition-all min-h-[44px]',
                            activeRole === 'admin' 
                                ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-md font-extrabold' 
                                : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                        ]"
                    >
                        <span class="material-symbols-rounded text-base">domain</span>
                        <span>Administración</span>
                    </button>
                    <button 
                        type="button"
                        @click="activeRole = 'owner'"
                        :class="[
                            'flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-xs font-bold transition-all min-h-[44px]',
                            activeRole === 'owner' 
                                ? 'bg-white dark:bg-slate-900 text-blue-600 dark:text-blue-400 shadow-md font-extrabold' 
                                : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'
                        ]"
                    >
                        <span class="material-symbols-rounded text-base">home</span>
                        <span>Residentes</span>
                    </button>
                </div>
            </div>

            <!-- Grilla Bento de Capacidades -->
            <div>
                <!-- Vista Administradores -->
                <div v-show="activeRole === 'admin'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                    <div 
                        v-for="(feat, i) in adminFeatures" 
                        :key="i" 
                        class="p-6 rounded-2xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c101a] hover:border-blue-500/40 dark:hover:border-blue-400/40 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative overflow-hidden"
                    >
                        <!-- Glow de Fondo Sutil -->
                        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-gradient-to-br opacity-40 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500" :class="feat.gradient"></div>

                        <div class="relative z-10 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105 duration-300" :class="feat.iconColor">
                                    <span class="material-symbols-rounded text-2xl">{{ feat.icon }}</span>
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-white/5">
                                    {{ feat.tag }}
                                </span>
                            </div>
                            
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight mb-1.5">{{ feat.title }}</h3>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">{{ feat.desc }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tarjeta de Conversión Administrador Directa -->
                    <div class="p-6 rounded-2xl bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-700 flex flex-col justify-between text-white shadow-xl shadow-blue-600/20 relative overflow-hidden">
                         <div class="space-y-3">
                            <span class="text-[10px] font-mono uppercase bg-white/20 px-2.5 py-1 rounded-md font-bold inline-block">Implementación Rápida</span>
                            <h3 class="text-xl font-black tracking-tight">¿Administras más de 50 unidades?</h3>
                            <p class="text-xs text-blue-100 leading-relaxed font-normal">
                                Migramos tu base de datos contable y propietarios sin costo adicional en menos de 48 horas.
                            </p>
                         </div>
                         <a 
                            href="/#contacto" 
                            class="mt-6 w-full py-3 px-4 bg-white hover:bg-slate-50 text-blue-700 rounded-xl text-xs font-black tracking-wide shadow-md active:scale-95 transition-all text-center min-h-[44px] flex items-center justify-center gap-1.5"
                         >
                            <span>Solicitar Plan Administrador</span>
                            <span class="material-symbols-rounded text-sm">arrow_forward</span>
                         </a>
                    </div>
                </div>

                <!-- Vista Residentes -->
                <div v-show="activeRole === 'owner'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
                    <div 
                        v-for="(feat, i) in ownerFeatures" 
                        :key="i" 
                        class="p-6 rounded-2xl border border-slate-200/80 dark:border-white/10 bg-white dark:bg-[#0c101a] hover:border-indigo-500/40 dark:hover:border-indigo-400/40 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative overflow-hidden"
                    >
                        <!-- Glow de Fondo Sutil -->
                        <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-gradient-to-br opacity-40 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500" :class="feat.gradient"></div>

                        <div class="relative z-10 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="w-11 h-11 rounded-xl flex items-center justify-center transition-transform group-hover:scale-105 duration-300" :class="feat.iconColor">
                                    <span class="material-symbols-rounded text-2xl">{{ feat.icon }}</span>
                                </div>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-white/5">
                                    {{ feat.tag }}
                                </span>
                            </div>
                            
                            <div>
                                <h3 class="text-base font-bold text-slate-900 dark:text-white tracking-tight mb-1.5">{{ feat.title }}</h3>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 leading-relaxed font-normal">{{ feat.desc }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tarjeta de Experiencia Residente -->
                    <div class="p-6 rounded-2xl bg-gradient-to-br from-indigo-700 via-slate-900 to-slate-950 flex flex-col justify-between text-white shadow-xl shadow-indigo-700/20 relative overflow-hidden">
                         <div class="space-y-3">
                            <span class="text-[10px] font-mono uppercase bg-white/20 px-2.5 py-1 rounded-md font-bold inline-block">Comodidad y Transparencia</span>
                            <h3 class="text-xl font-black tracking-tight">Tu copropiedad en la palma de tu mano</h3>
                            <p class="text-xs text-indigo-200 leading-relaxed font-normal">
                                Recomienda NEXO-PRO a tu administración o consejo y transforma la convivencia de tu edificio.
                            </p>
                         </div>
                         <a 
                            href="/#contacto" 
                            class="mt-6 w-full py-3 px-4 bg-white hover:bg-slate-50 text-indigo-900 rounded-xl text-xs font-black tracking-wide shadow-md active:scale-95 transition-all text-center min-h-[44px] flex items-center justify-center gap-1.5"
                         >
                            <span>Recomendar a mi Administrador</span>
                            <span class="material-symbols-rounded text-sm">send</span>
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
