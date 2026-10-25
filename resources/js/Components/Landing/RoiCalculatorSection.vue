<script setup lang="ts">
/**
 * RoiCalculatorSection.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Calculadora interactiva de Retorno de Inversión (ROI) y captura progresiva de prospectos PropTech.
 * Permite a administradores, consejeros y constructores visualizar en tiempo real:
 * 1. Horas operativas mensuales ahorradas (reducción de carga en conciliación y radicación de solicitudes).
 * 2. Cartera morosa recuperable anual (vía recaudos automatizados Wompi y recordatorios multicanal).
 * 3. Ahorro económico neto anual consolidado en Pesos Colombianos (COP).
 * Al solicitar la demostración, precarga estos indicadores para agilizar la reunión técnica sin fricción.
 *
 * [ASPECTO TÉCNICO / UI]
 * Componente Vue 3 responsivo Mobile-First con Atomic Design y soporte nativo Light / Dark.
 * Protegido contra ataques de bots mediante trampa silenciosa Honeypot ('website_hp').
 * Contraste estricto WCAG 2.1 AA (> 4.5:1), controles táctiles >= 48px y prevención de CLS.
 */

import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Input from '@/Components/UI/Input.vue';
import Button from '@/Components/UI/Button.vue';

// Parámetros reactivos de simulación
const unidades = ref(160);
const cuotaMensual = ref(420000);
const rol = ref('administrador');

// Estado del Modal de Captura Progresiva
const isModalOpen = ref(false);
const isSuccessSubmitted = ref(false);

// Fórmulas financieras sincronizadas con LeadScoringService
const FACTOR_HORAS_UNIDAD_MES = 0.22;
const VALOR_HORA_ADMIN_COP = 25000;
const TASA_RECUPERACION_CARTERA_ANUAL = 0.045;

const horasAhorradasMes = computed(() => {
    return Math.max(6, Math.round(unidades.value * FACTOR_HORAS_UNIDAD_MES));
});

const facturacionAnualTotal = computed(() => {
    return unidades.value * cuotaMensual.value * 12;
});

const carteraRecuperableAnual = computed(() => {
    return Math.round(facturacionAnualTotal.value * TASA_RECUPERACION_CARTERA_ANUAL);
});

const ahorroAdminAnual = computed(() => {
    return horasAhorradasMes.value * 12 * VALOR_HORA_ADMIN_COP;
});

const ahorroNetoAnual = computed(() => {
    return ahorroAdminAnual.value + carteraRecuperableAnual.value;
});

const segmento = computed(() => {
    if (unidades.value >= 300) return 'enterprise';
    if (unidades.value >= 100) return 'standard';
    return 'boutique';
});

const slaContacto = computed(() => {
    if (segmento.value === 'enterprise') return 'Respuesta inmediata (< 15 min)';
    if (segmento.value === 'standard') return 'Atención prioritaria (< 2 horas)';
    return 'Respuesta en el día (< 24 horas)';
});

// Formateador monetario para moneda colombiana
const formatCop = (valor: number): string => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(valor);
};

// Formulario de Captura de Lead (Inertia useForm)
const form = useForm({
    nombre: '',
    email: '',
    telefono: '',
    copropiedad_nombre: '',
    unidades_totales: unidades.value,
    cuota_promedio_mensual: cuotaMensual.value,
    rol: rol.value,
    website_hp: '', // Honeypot silencioso anti-spam
    habeas_data: true,
});

const openModal = () => {
    form.unidades_totales = unidades.value;
    form.cuota_promedio_mensual = cuotaMensual.value;
    form.rol = rol.value;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    isSuccessSubmitted.value = false;
};

const submitLead = () => {
    form.unidades_totales = unidades.value;
    form.cuota_promedio_mensual = cuotaMensual.value;
    form.rol = rol.value;

    form.post(route('leads.store'), {
        preserveScroll: true,
        onSuccess: () => {
            isSuccessSubmitted.value = true;
            form.reset('nombre', 'email', 'telefono', 'copropiedad_nombre', 'website_hp');
            setTimeout(() => {
                closeModal();
            }, 5000);
        },
    });
};
</script>

<template>
    <section id="calculadora-roi" class="py-20 sm:py-28 lg:py-36 bg-slate-50 dark:bg-[#07090e] relative overflow-hidden transition-colors duration-300">
        <!-- Fondos Ambientales -->
        <div class="absolute -top-40 right-0 w-[500px] h-[500px] bg-blue-600/10 blur-[140px] rounded-full pointer-events-none"></div>
        <div class="absolute -bottom-40 left-0 w-[500px] h-[500px] bg-indigo-600/10 blur-[140px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Encabezado de la Calculadora -->
            <div class="text-center max-w-3xl mx-auto mb-14 sm:mb-20 space-y-4">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-blue-100 dark:bg-blue-950/70 border border-blue-300 dark:border-blue-800 text-blue-700 dark:text-blue-300 text-xs font-bold uppercase tracking-widest shadow-sm">
                    <span class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></span>
                    Simulador Financiero PropTech
                </div>
                <h2 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white uppercase italic tracking-tighter leading-tight">
                    Calcula el <span class="text-blue-600 dark:text-blue-400">Retorno de Inversión</span>
                </h2>
                <p class="text-slate-600 dark:text-slate-300 text-base sm:text-lg font-medium leading-relaxed">
                    Descubre cuántas horas operativas y cuánto dinero de cartera morosa recuperará tu copropiedad este año migrando a NEXO PRO.
                </p>
            </div>

            <!-- Contenedor Principal de la Calculadora (Grid 2 Columnas) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-stretch">
                <!-- Columna Izquierda: Parámetros y Sliders (7 Cols) -->
                <div class="lg:col-span-7 bg-white dark:bg-slate-900/90 rounded-3xl p-6 sm:p-10 border border-slate-200 dark:border-slate-800 shadow-xl flex flex-col justify-between space-y-8">
                    <!-- Selector de Rol -->
                    <div>
                        <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">
                            ¿Cuál es tu rol en la copropiedad?
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5">
                            <button
                                type="button"
                                v-for="r in [
                                    { id: 'administrador', label: 'Administrador' },
                                    { id: 'miembro_consejo', label: 'Consejero' },
                                    { id: 'constructor', label: 'Constructor' },
                                    { id: 'propietario', label: 'Propietario' }
                                ]"
                                :key="r.id"
                                @click="rol = r.id"
                                :class="[
                                    rol === r.id
                                        ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20 font-black border-blue-600'
                                        : 'bg-slate-100 dark:bg-slate-800/80 text-slate-700 dark:text-slate-300 font-bold border-slate-200 dark:border-slate-700/60 hover:bg-slate-200 dark:hover:bg-slate-700',
                                    'py-3 px-3 rounded-xl text-xs uppercase tracking-tight border transition-all duration-200 min-h-[48px] flex items-center justify-center text-center'
                                ]"
                            >
                                {{ r.label }}
                            </button>
                        </div>
                    </div>

                    <!-- Slider 1: Unidades Inmobiliarias -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">Inmuebles Totales</span>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Apartamentos / Locales</h3>
                            </div>
                            <div class="text-right">
                                <span class="text-3xl sm:text-4xl font-black text-blue-600 dark:text-blue-400 tabular-nums">
                                    {{ unidades }}
                                </span>
                                <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest ml-1">unidades</span>
                            </div>
                        </div>

                        <input
                            type="range"
                            v-model.number="unidades"
                            min="30"
                            max="800"
                            step="5"
                            class="w-full h-3 bg-slate-200 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all"
                            aria-label="Deslizador de Unidades Inmobiliarias"
                        />

                        <!-- Botones Rápidos de Escala -->
                        <div class="flex gap-2 pt-1 flex-wrap">
                            <button
                                v-for="preset in [50, 120, 240, 450]"
                                :key="preset"
                                type="button"
                                @click="unidades = preset"
                                :class="[
                                    unidades === preset
                                        ? 'bg-blue-600 text-white font-black'
                                        : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white',
                                    'text-[11px] py-1.5 px-3 rounded-lg uppercase tracking-wider transition-colors'
                                ]"
                            >
                                {{ preset }} u.
                            </button>
                        </div>
                    </div>

                    <!-- Slider 2: Cuota Promedio de Expensa Común -->
                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <div>
                                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">Expensa Mensual</span>
                                <h3 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight">Cuota Promedio por Unidad</h3>
                            </div>
                            <div class="text-right">
                                <span class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tabular-nums">
                                    {{ formatCop(cuotaMensual) }}
                                </span>
                            </div>
                        </div>

                        <input
                            type="range"
                            v-model.number="cuotaMensual"
                            min="150000"
                            max="1200000"
                            step="25000"
                            class="w-full h-3 bg-slate-200 dark:bg-slate-800 rounded-lg appearance-none cursor-pointer accent-blue-600 focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all"
                            aria-label="Deslizador de Cuota de Administración Mensual"
                        />

                        <!-- Botones Rápidos de Cuota -->
                        <div class="flex gap-2 pt-1 flex-wrap">
                            <button
                                v-for="preset in [250000, 420000, 650000, 950000]"
                                :key="preset"
                                type="button"
                                @click="cuotaMensual = preset"
                                :class="[
                                    cuotaMensual === preset
                                        ? 'bg-blue-600 text-white font-black'
                                        : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 font-bold hover:text-slate-900 dark:hover:text-white',
                                    'text-[11px] py-1.5 px-3 rounded-lg uppercase tracking-wider transition-colors'
                                ]"
                            >
                                {{ formatCop(preset) }}
                            </button>
                        </div>
                    </div>

                    <!-- Badge Informativo de Segmentación -->
                    <div class="p-4 rounded-2xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900/60 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                            <div>
                                <span class="text-xs font-black uppercase text-blue-900 dark:text-blue-200 tracking-wider">
                                    Categoría: {{ segmento.toUpperCase() }}
                                </span>
                                <p class="text-[11px] text-blue-700 dark:text-blue-300 font-medium">
                                    {{ slaContacto }}
                                </p>
                            </div>
                        </div>
                        <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-md bg-blue-600 text-white tracking-widest">
                            Ley 675 Cert
                        </span>
                    </div>
                </div>

                <!-- Columna Derecha: Tarjetas de Impacto Financiero y CTA (5 Cols) -->
                <div class="lg:col-span-5 flex flex-col justify-between space-y-6">
                    <!-- Tarjeta 1: Horas Ahorradas al Mes -->
                    <div class="bg-white dark:bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-lg relative overflow-hidden group hover:border-blue-500/50 transition-all">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Productividad Operativa</span>
                                <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight mt-1">Horas Ahorradas al Mes</h4>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-blue-100 dark:bg-blue-950 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                                <span class="material-symbols-rounded text-2xl">schedule</span>
                            </div>
                        </div>
                        <div class="mt-6 flex items-baseline gap-2">
                            <span class="text-4xl sm:text-5xl font-black text-slate-900 dark:text-white tabular-nums">
                                {{ horasAhorradasMes }}
                            </span>
                            <span class="text-sm font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">horas / mes</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-2 font-medium">
                            Menos tiempo en conciliación manual de bancos, radicación de PQRS y citofonía.
                        </p>
                    </div>

                    <!-- Tarjeta 2: Cartera Morosa Recuperable -->
                    <div class="bg-white dark:bg-slate-900/90 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-800 shadow-lg relative overflow-hidden group hover:border-blue-500/50 transition-all">
                        <div class="flex items-start justify-between">
                            <div>
                                <span class="text-xs font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Liquidez & Cartera</span>
                                <h4 class="text-lg font-black text-slate-900 dark:text-white uppercase tracking-tight mt-1">Recaudo Moroso Rescatado</h4>
                            </div>
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 dark:bg-emerald-950 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                                <span class="material-symbols-rounded text-2xl">trending_up</span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <span class="text-2xl sm:text-3xl font-black text-emerald-600 dark:text-emerald-400 tabular-nums">
                                {{ formatCop(carteraRecuperableAnual) }}
                            </span>
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest block mt-0.5">proyección anual</span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-2 font-medium">
                            Mediante botones 1-Click FastPay (PSE/Nequi/Tarjetas) y recordatorios automatizados.
                        </p>
                    </div>

                    <!-- Tarjeta Destacada: Ahorro Económico Neto Anual -->
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-white shadow-2xl relative overflow-hidden">
                        <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

                        <span class="text-xs font-black uppercase tracking-widest text-blue-200 block">Retorno Financiero Total Estimado</span>
                        <div class="mt-2 text-3xl sm:text-4xl lg:text-5xl font-black tabular-nums tracking-tight">
                            {{ formatCop(ahorroNetoAnual) }}
                        </div>
                        <span class="text-xs font-bold uppercase tracking-wider text-blue-200/90 block mt-1">Ahorro Neto Proyectado / Año</span>

                        <!-- Botón CTA de Conversión -->
                        <button
                            type="button"
                            @click="openModal"
                            class="mt-8 w-full py-4 px-6 rounded-2xl bg-white text-blue-700 hover:bg-blue-50 active:scale-[0.98] font-black text-sm uppercase tracking-wider shadow-lg transition-all duration-200 flex items-center justify-center gap-2 min-h-[48px]"
                        >
                            <span class="material-symbols-rounded text-xl">event_available</span>
                            Agendar Demostración con estos Resultados
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL DE CAPTURA PROGRESIVA Y LEAD CONVERSION -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition duration-300 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-if="isModalOpen" class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/80 backdrop-blur-sm flex items-center justify-center p-4 sm:p-6">
                    <div
                        class="bg-white dark:bg-[#0b0e14] border border-slate-200 dark:border-slate-800 rounded-3xl w-full max-w-xl shadow-2xl overflow-hidden relative transition-all"
                        role="dialog"
                        aria-modal="true"
                        aria-labelledby="modal-lead-title"
                    >
                        <!-- Capa de Éxito / Confirmación Inmediata -->
                        <div v-if="isSuccessSubmitted" class="p-8 sm:p-12 text-center space-y-6">
                            <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 rounded-3xl mx-auto flex items-center justify-center shadow-lg border border-emerald-300 dark:border-emerald-800 animate-bounce">
                                <span class="material-symbols-rounded text-4xl">check_circle</span>
                            </div>
                            <h3 id="modal-lead-title" class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white uppercase tracking-tight">
                                ¡Auditoría Programada con Éxito!
                            </h3>
                            <p class="text-sm font-medium text-slate-600 dark:text-slate-300 max-w-md mx-auto leading-relaxed">
                                Un consultor senior de ingeniería revisará los indicadores de tu copropiedad ({{ unidades }} unidades) y te contactará en menos de 24 horas para la presentación ejecutiva.
                            </p>
                            <button
                                type="button"
                                @click="closeModal"
                                class="py-3 px-8 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-xs uppercase tracking-widest hover:opacity-90 transition-opacity min-h-[48px]"
                            >
                                Cerrar y Volver
                            </button>
                        </div>

                        <!-- Formulario de Captura -->
                        <div v-else class="p-6 sm:p-10 space-y-6">
                            <div class="flex items-start justify-between">
                                <div>
                                    <span class="text-xs font-black uppercase text-blue-600 dark:text-blue-400 tracking-wider">
                                        Auditoría Técnica Personalizada
                                    </span>
                                    <h3 id="modal-lead-title" class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white uppercase tracking-tight mt-1">
                                        Agendar Demostración
                                    </h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                        Simulación configurada: <strong>{{ unidades }} unidades</strong> (Ahorro proyectado: {{ formatCop(ahorroNetoAnual) }}/año).
                                    </p>
                                </div>
                                <button
                                    type="button"
                                    @click="closeModal"
                                    class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white flex items-center justify-center transition-colors min-h-[44px]"
                                    aria-label="Cerrar modal"
                                >
                                    <span class="material-symbols-rounded">close</span>
                                </button>
                            </div>

                            <form @submit.prevent="submitLead" class="space-y-4">
                                <!-- Campo Trampa Honeypot Silencioso (Invisible para humanos, captura bots) -->
                                <div class="hidden" aria-hidden="true">
                                    <label for="lead_website_hp">No llenar este campo:</label>
                                    <input
                                        id="lead_website_hp"
                                        type="text"
                                        name="website_hp"
                                        v-model="form.website_hp"
                                        tabindex="-1"
                                        autocomplete="off"
                                    />
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                            Nombre Completo *
                                        </label>
                                        <input
                                            type="text"
                                            v-model="form.nombre"
                                            required
                                            placeholder="Ej: Ing. Mauricio Silva"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all min-h-[48px]"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                            Email Corporativo *
                                        </label>
                                        <input
                                            type="email"
                                            v-model="form.email"
                                            required
                                            placeholder="administracion@edificio.com"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all min-h-[48px]"
                                        />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                            Celular / WhatsApp (+57) *
                                        </label>
                                        <input
                                            type="tel"
                                            v-model="form.telefono"
                                            required
                                            placeholder="+57 300 123 4567"
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all min-h-[48px]"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-xs font-black uppercase tracking-wider text-slate-700 dark:text-slate-300 mb-1">
                                            Nombre de la Copropiedad *
                                        </label>
                                        <input
                                            type="text"
                                            v-model="form.copropiedad_nombre"
                                            required
                                            placeholder="Residencial Los Laureles..."
                                            class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white text-sm focus:ring-2 focus:ring-blue-600 focus:outline-none transition-all min-h-[48px]"
                                        />
                                    </div>
                                </div>

                                <!-- Consentimiento Habeas Data -->
                                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700/60">
                                    <input
                                        type="checkbox"
                                        id="modal_habeas_data"
                                        v-model="form.habeas_data"
                                        required
                                        class="mt-1 w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
                                    />
                                    <label for="modal_habeas_data" class="text-[11px] text-slate-600 dark:text-slate-400 font-medium leading-relaxed cursor-pointer">
                                        Autorizo el tratamiento de datos para coordinar la auditoría de copropiedad según la política de privacidad de NEXO PRO y la Ley 1581 de 2012.
                                    </label>
                                </div>

                                <button
                                    type="submit"
                                    :disabled="form.processing"
                                    class="w-full py-4 px-6 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-[0.99] text-white font-black text-sm uppercase tracking-wider shadow-lg transition-all duration-200 flex items-center justify-center gap-2 min-h-[48px] disabled:opacity-50"
                                >
                                    <span v-if="form.processing" class="material-symbols-rounded animate-spin text-xl">sync</span>
                                    <span v-else class="material-symbols-rounded text-xl">rocket_launch</span>
                                    {{ form.processing ? 'Transmitiendo Solicitud...' : 'Confirmar y Solicitar Auditoría' }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </section>
</template>
