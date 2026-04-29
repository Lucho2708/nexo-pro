<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

const emit = defineEmits(['close']);

const form = useForm({
    file: null,
});

const submit = () => {
    form.post(route('cartera.import'), {
        onSuccess: () => emit('close'),
        forceFormData: true,
    });
};
</script>

<template>
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-primary/40 backdrop-blur-[10px] animate-in fade-in duration-300">
        <div class="bg-surface-container-lowest w-full max-w-lg rounded-[2.5rem] shadow-2xl border border-outline-variant/10 overflow-hidden animate-in zoom-in-95 duration-300">
            <div class="p-10">
                <div class="flex justify-between items-start mb-10">
                    <div>
                        <h2 class="text-2xl font-black text-primary tracking-tighter uppercase">Importar Unidades</h2>
                        <p class="text-on-surface-variant/70 text-sm font-medium mt-1">Sube el archivo CSV con la lista de propiedades</p>
                    </div>
                    <button @click="emit('close')" class="w-10 h-10 rounded-full hover:bg-surface-container-high transition-colors flex items-center justify-center">
                        <span class="material-symbols-outlined text-on-surface-variant">close</span>
                    </button>
                </div>

                <form @submit.prevent="submit" class="space-y-8">
                    <div class="space-y-4">
                        <div class="bg-primary/5 p-6 rounded-2xl border border-primary/10">
                            <h3 class="text-xs font-black text-primary uppercase tracking-widest mb-2">Formato Requerido (Orden de columnas):</h3>
                            <p class="text-[10px] font-bold text-on-surface-variant/70 leading-relaxed font-mono">
                                Torre, Nombre, Coeficiente, Propietario, Identificacion, Email, Saldo
                            </p>
                            <p class="text-[9px] text-on-surface-variant/40 mt-2 italic">Ejemplo: Torre A, 402, 0.85, Juan Perez, 10293, juan@test.com, 0</p>
                        </div>

                        <div 
                            class="border-2 border-dashed border-outline-variant/30 rounded-[1.5rem] p-10 text-center bg-surface-container-low/30 hover:bg-surface-container-low transition-colors cursor-pointer group"
                            @click="$refs.fileInput.click()"
                        >
                            <input type="file" ref="fileInput" class="hidden" @change="form.file = $event.target.files[0]" accept=".csv,.txt" />
                            <span class="material-symbols-outlined text-5xl text-primary/30 group-hover:scale-110 transition-transform mb-3">file_upload</span>
                            <p class="text-sm font-black text-primary">{{ form.file ? form.file.name : 'Selecciona tu archivo CSV' }}</p>
                            <p class="text-[10px] text-on-surface-variant/40 mt-1 font-medium italic">Solo archivos .csv o .txt</p>
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <button 
                            type="button"
                            @click="emit('close')" 
                            class="flex-1 py-4 text-sm font-black text-primary hover:bg-surface-container transition-all rounded-2xl uppercase tracking-widest"
                        >
                            Cerrar
                        </button>
                        <button 
                            type="submit"
                            :disabled="form.processing || !form.file"
                            class="flex-1 py-4 text-sm font-black bg-primary text-on-primary rounded-2xl shadow-xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-widest flex items-center justify-center gap-2"
                        >
                            <span v-if="form.processing" class="animate-spin w-4 h-4 border-2 border-white/30 border-t-white rounded-full"></span>
                             Procesar Importación
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
