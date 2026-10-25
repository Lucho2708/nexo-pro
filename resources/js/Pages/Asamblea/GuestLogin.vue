<script setup>
/**
 * GuestLogin.vue - Portal de Acceso a Asamblea Standalone para Invitados
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Permite el acceso ágil y seguro de propietarios o apoderados que no cuentan con cuenta previa
 * en la plataforma NEXO-PRO, utilizando una combinación de Número de Unidad y los últimos 4 dígitos
 * de su documento de identidad registrado.
 * - Reduce fricción en el ingreso masivo el día de la asamblea.
 * - Verifica identidad sin exigir contraseñas complejas que demoran el quórum inicial.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Sustituye dependencias externas no resueltas (@heroicons/vue-api) por vectores SVG limpios.
 * - Formulario reactivo con Inertia.js useForm con manejo de errores de validación.
 * - Feedback táctil con estado de carga (spinner SVG) y prevención de doble submit.
 */
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';

const props = defineProps({
    asamblea: Object,
});

const form = useForm({
    nombre_unidad: '',
    documento_ultimos_4: '',
});

const isSubmitting = ref(false);

const submit = () => {
    isSubmitting.value = true;
    form.post(route('asambleas.guest.attempt', props.asamblea.id), {
        onFinish: () => isSubmitting.value = false,
    });
};
</script>

<template>
    <Head title="Ingreso a Asamblea Virtual" />

    <div class="min-h-screen bg-slate-950 flex flex-col justify-center items-center px-4 sm:px-6 py-12 relative overflow-hidden">
        <!-- Fondos dinámicos / Luces ambientales -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-blue-500/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <!-- Header de la Copropiedad -->
            <div class="text-center mb-8 animate-fade-in-down">
                <div class="inline-flex p-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xl mb-4 shadow-2xl">
                    <svg class="w-10 h-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white tracking-tight">{{ asamblea.copropiedad.nombre }}</h1>
                <p class="text-slate-400 mt-1">{{ asamblea.titulo }}</p>
            </div>

            <!-- Card de Ingreso -->
            <div class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 shadow-2xl shadow-indigo-500/5">
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-white">Validación de Identidad</h2>
                    <p class="text-slate-400 text-sm mt-2">Por favor, identifique su unidad y use los últimos 4 dígitos de su documento registrado.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Campo de Unidad -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-300 ml-1">Identificación de Unidad</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-500 group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                            </div>
                            <input 
                                v-model="form.nombre_unidad"
                                type="text" 
                                placeholder="Ej: Torre 1 - Apto 101"
                                class="block w-full pl-11 pr-4 py-3.5 bg-slate-900/50 border border-white/10 rounded-2xl text-white placeholder:text-slate-600 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none"
                                :class="{'border-red-500/50 bg-red-500/5': form.errors.nombre_unidad}"
                                required
                            />
                        </div>
                        <p v-if="form.errors.nombre_unidad" class="text-xs text-red-400 mt-1 ml-1">{{ form.errors.nombre_unidad }}</p>
                    </div>

                    <!-- Campo de Documento (PIN) -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-slate-300 ml-1">Últimos 4 dígitos del Documento</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-500 group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7.864 4.243A7.5 7.5 0 0119.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 004.5 10.5a7.464 7.464 0 01-1.15 3.993m1.989 3.559A11.209 11.209 0 008.25 10.5a3.75 3.75 0 117.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a1.5 1.5 0 011.5 1.5v3.75m-3.75-3.75a1.5 1.5 0 011.5-1.5h1.5a1.5 1.5 0 011.5 1.5v3.75" />
                                </svg>
                            </div>
                            <input 
                                v-model="form.documento_ultimos_4"
                                type="text" 
                                maxlength="4"
                                inputmode="numeric"
                                placeholder="0000"
                                class="block w-full pl-11 pr-4 py-3.5 bg-slate-900/50 border border-white/10 rounded-2xl text-white tracking-[0.5em] font-mono text-lg placeholder:text-slate-600 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-all outline-none"
                                :class="{'border-red-500/50 bg-red-500/5': form.errors.documento_ultimos_4}"
                                required
                            />
                        </div>
                        <p v-if="form.errors.documento_ultimos_4" class="text-xs text-red-400 mt-1 ml-1">{{ form.errors.documento_ultimos_4 }}</p>
                    </div>

                    <!-- Botón de Acción -->
                    <button 
                        type="submit" 
                        :disabled="isSubmitting"
                        class="w-full relative overflow-hidden group py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-bold shadow-xl shadow-indigo-500/20 transition-all active:scale-[0.98] disabled:opacity-50 cursor-pointer"
                    >
                        <div class="relative z-10 flex items-center justify-center gap-2">
                            <span v-if="!isSubmitting">Ingresar a la Asamblea</span>
                            <span v-else class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Validando...
                            </span>
                            <svg v-if="!isSubmitting" class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </div>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.002A11.959 11.959 0 0112 2.714z" />
                    </svg>
                    <span class="text-xs text-slate-500 uppercase tracking-widest font-semibold">Conexión Segura NEXO PRO</span>
                </div>
            </div>

            <!-- Footer con Ayuda -->
            <div class="text-center mt-8">
                <p class="text-slate-500 text-sm">
                    ¿Problemas para ingresar? 
                    <a href="#" class="text-indigo-400 hover:text-indigo-300 font-medium underline underline-offset-4">Contactar a soporte</a>
                </p>
            </div>
        </div>
    </div>
</template>
