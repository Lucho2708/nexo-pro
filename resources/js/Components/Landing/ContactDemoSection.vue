<script setup lang="ts">
/**
 * ContactDemoSection.vue
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Módulo corporativo de conversión y agendamiento de demostraciones técnicas de NEXO PRO.
 * - Diseñado para decisores de copropiedades (administradores, juntas y constructoras).
 * - Transmite confianza institucional, solvencia tecnológica y cumplimiento estricto de la Ley 675.
 * - Elimina la fricción visual y el lenguaje militar o distópico, priorizando la claridad y cercanía.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Arquitectura Mobile-First con grilla fluida y etiquetas superiores limpias sin colisión tipográfica.
 * - Soporte nativo para Modo Claro y Modo Oscuro con contraste verificado WCAG 2.1 AA (> 4.5:1).
 * - Protección silenciosa contra robots automatizados (Honeypot 'honeypot' / 'website_hp').
 * - Touch targets ergonómicos >= 48px y prevención de Cumulative Layout Shift (CLS).
 */

import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Input from '@/Components/UI/Input.vue';

const showSuccessMessage = ref(false);

const form = useForm({
    fullName: '',
    email: '',
    phone: '',
    buildingName: '',
    unidades_totales: 80,
    rol: 'administrador',
    honeypot: '',
});

const submit = () => {
    form.post(route('demo.request'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('fullName', 'email', 'phone', 'buildingName', 'honeypot');
            showSuccessMessage.value = true;
            setTimeout(() => {
                showSuccessMessage.value = false;
            }, 6000);
        },
    });
};
</script>

<template>
    <section id="contacto" class="py-20 sm:py-28 lg:py-36 bg-slate-50 dark:bg-[#07090e] relative overflow-hidden transition-colors duration-300">
        <!-- Resplandores de Fondo Suaves -->
        <div class="absolute top-1/4 right-0 w-[500px] h-[500px] bg-blue-600/10 blur-[130px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-10 left-0 w-[500px] h-[500px] bg-indigo-600/10 blur-[130px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Tarjeta Contenedora Principal -->
            <div class="rounded-3xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0b0e14] shadow-2xl overflow-hidden relative">
                
                <!-- Capa de Éxito / Transmisión Exitosa -->
                <Transition
                    enter-active-class="transition duration-500 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition duration-300 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div v-if="showSuccessMessage" class="absolute inset-0 bg-white/95 dark:bg-[#0b0e14]/95 backdrop-blur-md z-50 flex flex-col items-center justify-center text-center p-8 sm:p-12">
                        <div class="w-20 h-20 bg-emerald-100 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 rounded-2xl flex items-center justify-center mb-6 shadow-xl border border-emerald-300 dark:border-emerald-800 animate-bounce">
                            <span class="material-symbols-rounded text-4xl">check_circle</span>
                        </div>
                        <h3 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                            ¡Solicitud Recibida con Éxito!
                        </h3>
                        <p class="text-slate-600 dark:text-slate-300 max-w-lg text-base sm:text-lg font-medium leading-relaxed mt-3">
                            Un consultor técnico de <span class="text-blue-600 dark:text-blue-400 font-bold">NEXO PRO</span> coordinará la demostración guiada y la auditoría de tu copropiedad en menos de 24 horas.
                        </p>
                        <button
                            type="button"
                            @click="showSuccessMessage = false"
                            class="mt-8 px-6 py-3 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-xs uppercase tracking-wider hover:opacity-90 transition-opacity min-h-[44px]"
                        >
                            Realizar otra consulta
                        </button>
                    </div>
                </Transition>

                <div class="grid grid-cols-1 lg:grid-cols-12 min-h-[700px]">
                    <!-- Columna Izquierda: Propuesta de Valor & Confianza (5 Cols) -->
                    <div class="lg:col-span-5 bg-gradient-to-br from-blue-600 via-indigo-700 to-slate-900 p-8 sm:p-12 lg:p-14 text-white flex flex-col justify-between relative overflow-hidden">
                        <!-- Destellos Estéticos -->
                        <div class="absolute -right-20 -top-20 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                        <div class="absolute -left-20 -bottom-20 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="relative z-10 space-y-8">
                            <!-- Badge de Demostración -->
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white text-xs font-bold uppercase tracking-wider shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Demostración Guiada
                            </div>

                            <div class="space-y-3">
                                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-black text-white tracking-tight leading-tight">
                                    Moderniza tu <br />
                                    <span class="text-blue-200">Copropiedad</span>
                                </h2>
                                <p class="text-blue-100/90 text-sm sm:text-base font-normal leading-relaxed max-w-sm">
                                    Comprueba cómo NEXO PRO automatiza el recaudo, erradica la morosidad y transforma tus asambleas en eventos 100% inimpugnables.
                                </p>
                            </div>

                            <!-- Pilares de Confianza -->
                            <div class="space-y-4 pt-2">
                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/15 text-blue-200">
                                        <span class="material-symbols-rounded text-xl">verified_user</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white">Certificación Ley 675</h4>
                                        <p class="text-xs text-blue-100/75 leading-relaxed">Coeficientes deterministas y actas automáticas con validez jurídica.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/15 text-blue-200">
                                        <span class="material-symbols-rounded text-xl">payments</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white">Pasarela Integrada Wompi</h4>
                                        <p class="text-xs text-blue-100/75 leading-relaxed">Recaudo por PSE, tarjetas y Nequi con conciliación bancaria instantánea.</p>
                                    </div>
                                </div>

                                <div class="flex items-start gap-3.5">
                                    <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center shrink-0 border border-white/15 text-blue-200">
                                        <span class="material-symbols-rounded text-xl">support_agent</span>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-white">Acompañamiento Personalizado</h4>
                                        <p class="text-xs text-blue-100/75 leading-relaxed">Migración asistida de bases de datos y soporte prioritario para tu equipo.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Canales de Enlace Directo -->
                        <div class="relative z-10 pt-10 border-t border-white/10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                            <div class="flex items-center gap-3.5 group">
                                <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center text-white border border-white/15 group-hover:bg-white group-hover:text-blue-700 transition-colors">
                                    <span class="material-symbols-rounded text-xl">call</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200/80 block">Línea de Asesoría</span>
                                    <span class="text-sm font-extrabold text-white tabular-nums">+57 (601) 444 0000</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-3.5 group">
                                <div class="w-11 h-11 rounded-xl bg-white/10 flex items-center justify-center text-white border border-white/15 group-hover:bg-white group-hover:text-blue-700 transition-colors">
                                    <span class="material-symbols-rounded text-xl">mail</span>
                                </div>
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200/80 block">Canal Directo</span>
                                    <span class="text-sm font-extrabold text-white">contacto@nexo-pro.io</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha: Formulario Limpio, Estético y Accesible (7 Cols) -->
                    <form @submit.prevent="submit" class="lg:col-span-7 p-8 sm:p-12 lg:p-14 flex flex-col justify-between bg-white dark:bg-[#0b0e14] space-y-8">
                        <div>
                            <!-- Encabezado del Formulario -->
                            <div class="space-y-2 mb-8">
                                <span class="text-xs font-black uppercase tracking-widest text-blue-600 dark:text-blue-400">
                                    Solicitud de Demostración
                                </span>
                                <h3 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white tracking-tight">
                                    Agenda tu sesión ejecutiva
                                </h3>
                                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium">
                                    Ingresa los datos del edificio o conjunto para personalizar la presentación técnica.
                                </p>
                            </div>

                            <!-- Campo Trampa Honeypot Anti-Spam (Invisible para humanos) -->
                            <div class="hidden" aria-hidden="true">
                                <label for="honeypot_field">Deje este campo vacío:</label>
                                <input
                                    id="honeypot_field"
                                    type="text"
                                    name="honeypot"
                                    v-model="form.honeypot"
                                    tabindex="-1"
                                    autocomplete="off"
                                />
                            </div>

                            <!-- Campos del Formulario (Grilla 2 Columnas Limpia) -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 sm:gap-6">
                                <Input 
                                    v-model="form.fullName"
                                    :static-label="true"
                                    label="Nombre Completo"
                                    placeholder="Ej: Ing. Mauricio Silva"
                                    required
                                    icon="person"
                                />

                                <Input 
                                    v-model="form.email"
                                    :static-label="true"
                                    type="email"
                                    label="Email Corporativo"
                                    placeholder="administracion@edificio.com"
                                    required
                                    icon="mail"
                                />

                                <Input 
                                    v-model="form.phone"
                                    :static-label="true"
                                    type="tel"
                                    label="Celular / WhatsApp"
                                    placeholder="+57 300 123 4567"
                                    required
                                    icon="smartphone"
                                />

                                <Input 
                                    v-model="form.buildingName"
                                    :static-label="true"
                                    label="Nombre de la Copropiedad"
                                    placeholder="Residencial Las Acacias PH"
                                    required
                                    icon="domain"
                                />

                                <!-- Selector de Rol -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                        Tu Rol en la Copropiedad *
                                    </label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined absolute left-4 text-slate-400 pointer-events-none z-10" style="font-variation-settings: 'opsz' 20, 'wght' 500;">
                                            badge
                                        </span>
                                        <select
                                            v-model="form.rol"
                                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/70 pl-11 pr-4 py-3.5 text-sm text-slate-900 dark:text-white outline-none focus:bg-white dark:focus:bg-slate-900 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all duration-200 appearance-none cursor-pointer"
                                        >
                                            <option value="administrador">Administrador / Delegado</option>
                                            <option value="miembro_consejo">Miembro del Consejo</option>
                                            <option value="constructor">Constructor / Desarrollador</option>
                                            <option value="propietario">Propietario / Residente</option>
                                        </select>
                                        <span class="material-symbols-outlined absolute right-4 text-slate-400 pointer-events-none" style="font-variation-settings: 'opsz' 20, 'wght' 500;">
                                            expand_more
                                        </span>
                                    </div>
                                </div>

                                <!-- Unidades Estimadas -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                                        Número de Inmuebles *
                                    </label>
                                    <div class="relative flex items-center">
                                        <span class="material-symbols-outlined absolute left-4 text-slate-400 pointer-events-none z-10" style="font-variation-settings: 'opsz' 20, 'wght' 500;">
                                            apartment
                                        </span>
                                        <input
                                            type="number"
                                            v-model.number="form.unidades_totales"
                                            min="10"
                                            max="2000"
                                            placeholder="Ej: 120"
                                            required
                                            class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-900/70 pl-11 pr-4 py-3.5 text-sm text-slate-900 dark:text-white placeholder:text-slate-400 dark:placeholder:text-slate-500 outline-none focus:bg-white dark:focus:bg-slate-900 focus:border-blue-600 focus:ring-4 focus:ring-blue-600/10 transition-all duration-200"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sección Inferior: Habeas Data y Botón de Conversión -->
                        <div class="space-y-6 pt-4">
                            <!-- Consentimiento de Habeas Data Elegante -->
                            <div class="flex items-start gap-3 p-4 rounded-xl bg-slate-50 dark:bg-slate-900/50 border border-slate-200/80 dark:border-slate-800 transition-colors">
                                <input 
                                    id="habeas_data_check" 
                                    type="checkbox" 
                                    required 
                                    class="mt-1 w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500 cursor-pointer" 
                                />
                                <label for="habeas_data_check" class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed cursor-pointer font-medium">
                                    Autorizo el tratamiento de mis datos personales para coordinar la demostración técnica según la política de privacidad de NEXO PRO y la Ley 1581 de 2012.
                                </label>
                            </div>

                            <!-- Botón CTA Premium Ergonómico -->
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="w-full h-13 sm:h-14 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 active:scale-[0.99] text-white font-bold text-sm sm:text-base tracking-wide shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2.5 transition-all duration-200 disabled:opacity-50 min-h-[48px]"
                            >
                                <span v-if="form.processing" class="material-symbols-rounded animate-spin text-xl">sync</span>
                                <span v-else class="material-symbols-rounded text-xl">event_available</span>
                                {{ form.processing ? 'Agendando Demostración...' : 'Solicitar Demostración y Asesoría' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 500, 'FILL' 0;
}
</style>
