<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/UI/Modal.vue';
import Button from '@/Components/UI/Button.vue';
import Select from '@/Components/UI/Select.vue';
import { useToast } from '@/Composables/useToast';

const props = defineProps<{
    show: boolean;
}>();

const emit = defineEmits(['close', 'success']);
const toast = useToast();

const form = useForm({
    structure_type: 'vertical',
    prefix: 'Torre',
    separator: '-',
    towers: 2,
    floors: 5,
    units_per_floor: 4,
    numbering_type: 'floor',
    total_units: 100,
});

const separatorOptions = [
    { value: '-', label: 'Guión (-)' },
    { value: 'space', label: 'Espacio ( )' },
    { value: '', label: 'Sin Separador' },
];

const numberingOptions = [
    { value: 'floor', label: 'Por Piso (101, 102, 201...)' },
    { value: 'continuous', label: 'Continua (1, 2, 3...)' },
];

// Generar preview en tiempo real
const previewItems = computed(() => {
    const items = [];
    const sep = form.separator === 'space' ? ' ' : form.separator;

    if (form.structure_type === 'vertical') {
        const t = 1;
        const lastT = form.towers || 1;
        
        // Primera unidad de la torre 1
        const u1 = form.numbering_type === 'floor' ? 101 : 1;
        items.push(`${form.prefix} 1${sep}${u1}`);

        // Segunda unidad de la torre 1
        if (form.units_per_floor > 1) {
            const u2 = form.numbering_type === 'floor' ? 102 : 2;
            items.push(`${form.prefix} 1${sep}${u2}`);
        }

        items.push('...');

        // Última unidad de la última torre
        const lastFloor = form.floors || 1;
        const lastU = form.numbering_type === 'floor' 
            ? (lastFloor * 100) + (form.units_per_floor || 1)
            : (form.floors * form.units_per_floor);
        
        items.push(`${form.prefix} ${lastT}${sep}${lastU}`);
    } else {
        items.push(`${form.prefix}${sep}1`);
        if (form.total_units > 1) items.push(`${form.prefix}${sep}2`);
        items.push('...');
        items.push(`${form.prefix}${sep}${form.total_units}`);
    }
    return items;
});

const submit = () => {
    form.post(route('admin.settings.generate_units'), {
        preserveScroll: true,
        onSuccess: () => {
            emit('success');
            close();
        },
        onError: (errors) => {
            toast.error(Object.values(errors)[0] as string);
        }
    });
};

const close = () => {
    emit('close');
};
</script>

<template>
    <Modal :show="show" @close="close" max-width="3xl">
        <div class="bg-surface dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 shadow-2xl rounded-[2.5rem] overflow-hidden">
            <!-- Header -->
            <div class="bg-primary p-8 md:p-10 text-white relative overflow-hidden">
                <div class="absolute -right-10 -top-10 opacity-10 rotate-12 scale-150">
                    <span class="material-symbols-rounded text-9xl">domain_add</span>
                </div>
                <div class="relative z-10 flex gap-6 items-center">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center">
                        <span class="material-symbols-rounded text-3xl">precision_manufacturing</span>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black uppercase tracking-tighter leading-none italic">Motor de <span class="text-primary-container">Nomenclatura</span></h2>
                        <p class="text-[10px] font-bold text-white/60 uppercase tracking-[0.2em] mt-2">Asistente de generación masiva de unidades físicas</p>
                    </div>
                </div>
            </div>

            <!-- Body -->
            <div class="p-8 md:p-10 space-y-10">
                <!-- Estructura Principal -->
                <div class="space-y-6">
                    <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em] border-b border-outline-variant/10 dark:border-white/5 pb-2">1. Topología del Conjunto</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <button 
                            @click="form.structure_type = 'vertical'"
                            class="p-6 rounded-2xl border-2 text-left transition-all relative overflow-hidden group"
                            :class="form.structure_type === 'vertical' ? 'border-primary bg-primary/5' : 'border-outline-variant/10 dark:border-white/5 hover:border-primary/30'"
                        >
                            <span class="material-symbols-rounded absolute right-4 bottom-4 text-6xl opacity-5 group-hover:scale-110 transition-transform">apartment</span>
                            <h4 class="text-lg font-black text-on-surface dark:text-white uppercase italic">Vertical</h4>
                            <p class="text-[10px] font-bold text-on-surface-variant/60 dark:text-white/40 mt-1 uppercase tracking-widest">Torres, Bloques, Edificios</p>
                        </button>

                        <button 
                            @click="form.structure_type = 'horizontal'"
                            class="p-6 rounded-2xl border-2 text-left transition-all relative overflow-hidden group"
                            :class="form.structure_type === 'horizontal' ? 'border-primary bg-primary/5' : 'border-outline-variant/10 dark:border-white/5 hover:border-primary/30'"
                        >
                            <span class="material-symbols-rounded absolute right-4 bottom-4 text-6xl opacity-5 group-hover:scale-110 transition-transform">holiday_village</span>
                            <h4 class="text-lg font-black text-on-surface dark:text-white uppercase italic">Horizontal</h4>
                            <p class="text-[10px] font-bold text-on-surface-variant/60 dark:text-white/40 mt-1 uppercase tracking-widest">Casas, Lotes, Bodegas</p>
                        </button>
                    </div>
                </div>

                <!-- Parametrización Vertical -->
                <div v-if="form.structure_type === 'vertical'" class="space-y-6 animate-in fade-in duration-300">
                    <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em] border-b border-outline-variant/10 dark:border-white/5 pb-2">2. Reglas de Numeración Vertical</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Prefijo</label>
                            <input v-model="form.prefix" type="text" class="w-full h-12 bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/20 dark:border-white/10 rounded-xl px-4 text-sm font-bold text-on-surface dark:text-white focus:ring-2 focus:ring-primary/20" placeholder="Ej: Torre, Bloque" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Separador</label>
                            <Select v-model="form.separator" :options="separatorOptions" class="!h-12 !rounded-xl !bg-surface-container-low dark:!bg-white/[0.02]" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Tipo de Numeración</label>
                            <Select v-model="form.numbering_type" :options="numberingOptions" class="!h-12 !rounded-xl !bg-surface-container-low dark:!bg-white/[0.02]" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Cant. Torres</label>
                            <input v-model.number="form.towers" type="number" min="1" class="w-full h-12 bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/20 dark:border-white/10 rounded-xl px-4 text-sm font-bold text-on-surface dark:text-white focus:ring-2 focus:ring-primary/20" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Pisos por Torre</label>
                            <input v-model.number="form.floors" type="number" min="1" class="w-full h-12 bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/20 dark:border-white/10 rounded-xl px-4 text-sm font-bold text-on-surface dark:text-white focus:ring-2 focus:ring-primary/20" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Aptos por Piso</label>
                            <input v-model.number="form.units_per_floor" type="number" min="1" class="w-full h-12 bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/20 dark:border-white/10 rounded-xl px-4 text-sm font-bold text-on-surface dark:text-white focus:ring-2 focus:ring-primary/20" />
                        </div>
                    </div>
                </div>

                <!-- Parametrización Horizontal -->
                <div v-else class="space-y-6 animate-in fade-in duration-300">
                    <h3 class="text-xs font-black text-primary uppercase tracking-[0.2em] border-b border-outline-variant/10 dark:border-white/5 pb-2">2. Reglas de Numeración Horizontal</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Prefijo</label>
                            <input v-model="form.prefix" type="text" class="w-full h-12 bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/20 dark:border-white/10 rounded-xl px-4 text-sm font-bold text-on-surface dark:text-white focus:ring-2 focus:ring-primary/20" placeholder="Ej: Casa, Lote" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Separador</label>
                            <Select v-model="form.separator" :options="separatorOptions" class="!h-12 !rounded-xl !bg-surface-container-low dark:!bg-white/[0.02]" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Total Unidades</label>
                            <input v-model.number="form.total_units" type="number" min="1" class="w-full h-12 bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/20 dark:border-white/10 rounded-xl px-4 text-sm font-bold text-on-surface dark:text-white focus:ring-2 focus:ring-primary/20" />
                        </div>
                    </div>
                </div>

                <!-- Preview Engine -->
                <div class="bg-[#05070a] rounded-2xl p-6 shadow-inner border border-white/5">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-[10px] font-black text-emerald-400 uppercase tracking-[0.3em]">Previsualización en tiempo real</h4>
                        <span class="flex h-2 w-2 relative">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <div v-for="(item, idx) in previewItems" :key="idx" class="px-3 py-1.5 rounded-lg bg-white/5 border border-white/10 text-xs font-mono font-bold text-emerald-300">
                            {{ item }}
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4 border-t border-outline-variant/10 dark:border-white/5">
                    <Button @click="close" variant="ghost" class="!text-[11px] font-black uppercase tracking-widest">Cancelar</Button>
                    <Button @click="submit" variant="primary" :loading="form.processing" icon="bolt" class="!rounded-xl !h-12 shadow-xl shadow-primary/20 uppercase tracking-widest !text-[11px] font-black px-8">
                        Generar Unidades y Bloquear
                    </Button>
                </div>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
