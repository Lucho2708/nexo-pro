<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import { 
    FingerprintIcon, 
    BuildingOfficeIcon, 
    ChevronRightIcon,
    ShieldCheckIcon
} from '@heroicons/vue-api/24/outline';

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
    <Head :title="`Ingreso a Asamblea - ${asamblea.titulo}`" />

    <div class="min-h-screen bg-[#0F172A] flex flex-col justify-center items-center p-6 font-manrope selection:bg-indigo-500/30">
        <!-- Fondo con Gradientes Sutiles -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-500/10 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-blue-500/10 rounded-full blur-[120px]"></div>
        </div>

        <div class="w-full max-w-md relative z-10">
            <!-- Header de la Copropiedad -->
            <div class="text-center mb-8 animate-fade-in-down">
                <div class="inline-flex p-3 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-xl mb-4 shadow-2xl">
                    <BuildingOfficeIcon class="w-10 h-10 text-indigo-400" />
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
                                <BuildingOfficeIcon class="h-5 w-5 text-slate-500 group-focus-within:text-indigo-400 transition-colors" />
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
                                <FingerprintIcon class="h-5 w-5 text-slate-500 group-focus-within:text-indigo-400 transition-colors" />
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
                        class="w-full relative overflow-hidden group py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-bold shadow-xl shadow-indigo-500/20 transition-all active:scale-[0.98] disabled:opacity-50"
                    >
                        <div class="relative z-10 flex items-center justify-center gap-2">
                            <span v-if="!isSubmitting">Ingresar a la Asamblea</span>
                            <span v-else class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                Validando...
                            </span>
                            <ChevronRightIcon v-if="!isSubmitting" class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                        </div>
                    </button>
                </form>

                <div class="mt-8 pt-6 border-t border-white/5 flex items-center justify-center gap-2">
                    <ShieldCheckIcon class="w-5 h-5 text-emerald-500" />
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

<style scoped>
.animate-fade-in-down {
    animation: fadeInDown 0.8s ease-out;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
