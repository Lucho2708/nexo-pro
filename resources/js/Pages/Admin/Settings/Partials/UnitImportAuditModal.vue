<script setup lang="ts">
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Modal from '@/Components/UI/Modal.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import { useToast } from '@/Composables/useToast';

interface PreviewRow {
    torre: string | null;
    nombre: string;
    piso: string | null;
    modelo: string;
    propietario: string | null;
    identificacion: string | null;
    telefono: string | null;
    email: string | null;
    saldo: number;
    coeficiente: number | null;
}

interface ValidationStats {
    total_units: number;
    towers_count: number;
    models_breakdown: Record<string, number>;
    sum_coeficientes: number;
    is_100_percent: boolean;
}

interface ValidationResult {
    is_valid: boolean;
    errors: string[];
    warnings: string[];
    stats: ValidationStats;
    preview_rows: PreviewRow[];
}

const props = defineProps<{
    show: boolean;
    result: ValidationResult | null;
    file: File | null;
}>();

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'success'): void;
}>();

const toast = useToast();
const isSubmitting = ref(false);

const confirmImport = () => {
    if (!props.file || !props.result?.is_valid) {
        return;
    }

    isSubmitting.value = true;
    const formData = new FormData();
    formData.append('file', props.file);

    router.post(route('admin.settings.import_units'), formData, {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            isSubmitting.value = false;
            toast.add('¡Censo de unidades importado y certificado exitosamente!', 'success');
            emit('success');
            emit('close');
        },
        onError: (errs) => {
            isSubmitting.value = false;
            const msg = Object.values(errs)[0] || 'Ocurrió un error al persistir el censo de unidades.';
            toast.add(String(msg), 'error');
        },
    });
};
</script>

<template>
    <Modal :show="show" maxWidth="4xl" @close="emit('close')" title="Auditoría de Censo e Importación">
        <div v-if="!result" class="py-16 text-center space-y-4">
            <div class="w-12 h-12 border-4 border-primary border-t-transparent rounded-full animate-spin mx-auto"></div>
            <p class="text-xs font-black uppercase tracking-widest text-on-surface-variant/70">
                Auditando estructura y verificando coeficientes deterministas (Ley 675)...
            </p>
        </div>

        <div v-else class="space-y-8 animate-in fade-in duration-300">
            <!-- Header Status Banner -->
            <div 
                :class="result.is_valid 
                    ? 'bg-emerald-500/10 border-emerald-500/30 text-emerald-600 dark:text-emerald-400' 
                    : 'bg-rose-500/10 border-rose-500/30 text-rose-600 dark:text-rose-400'"
                class="p-6 rounded-3xl border flex items-center justify-between gap-4"
            >
                <div class="flex items-center gap-4">
                    <div 
                        :class="result.is_valid ? 'bg-emerald-500 text-white' : 'bg-rose-500 text-white'"
                        class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 shadow-lg"
                    >
                        <span class="material-symbols-rounded text-2xl">
                            {{ result.is_valid ? 'verified' : 'error' }}
                        </span>
                    </div>
                    <div>
                        <h4 class="text-base font-black uppercase tracking-tight">
                            {{ result.is_valid ? 'Estructura Validada y Certificada' : 'Se encontraron inconsistencias en el archivo' }}
                        </h4>
                        <p class="text-[11px] font-bold opacity-80 uppercase tracking-wider mt-0.5">
                            {{ result.is_valid 
                                ? 'Los datos coinciden exactamente con el catálogo arquitectónico de la copropiedad.' 
                                : 'Por favor corrige los siguientes puntos en tu Excel antes de continuar.' }}
                        </p>
                    </div>
                </div>
                <Badge :variant="result.is_valid ? 'primary' : 'danger'" class="!rounded-xl !px-4 !py-2 text-[10px] font-black uppercase tracking-widest shrink-0">
                    {{ result.is_valid ? 'APTO PARA IMPORTAR' : 'BLOQUEADO' }}
                </Badge>
            </div>

            <!-- Error List (If any) -->
            <div v-if="!result.is_valid && result.errors.length > 0" class="p-6 rounded-3xl bg-surface-container-high/60 dark:bg-white/[0.03] border border-rose-500/20 space-y-3">
                <p class="text-xs font-black text-rose-500 uppercase tracking-widest flex items-center gap-2">
                    <span class="material-symbols-rounded text-sm">warning</span>
                    Detalle de Inconsistencias ({{ result.errors.length }}):
                </p>
                <ul class="space-y-1.5 max-h-48 overflow-y-auto pr-2 custom-scrollbar text-xs text-on-surface/80 dark:text-white/80 font-medium">
                    <li v-for="(err, idx) in result.errors" :key="idx" class="flex items-start gap-2 bg-rose-500/5 p-2 rounded-xl border border-rose-500/10">
                        <span class="text-rose-500 font-bold shrink-0">•</span>
                        <span>{{ err }}</span>
                    </li>
                </ul>
            </div>

            <!-- KPI Metric Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-5 rounded-3xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 space-y-1">
                    <p class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest">Unidades</p>
                    <p class="text-2xl font-black text-on-surface dark:text-white tracking-tight">{{ result.stats.total_units }}</p>
                    <span class="text-[9px] font-bold text-primary uppercase tracking-wider flex items-center gap-1">
                        <span class="material-symbols-rounded text-xs">apartment</span> Detectadas
                    </span>
                </div>

                <div class="p-5 rounded-3xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 space-y-1">
                    <p class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest">Torres / Bloques</p>
                    <p class="text-2xl font-black text-on-surface dark:text-white tracking-tight">{{ result.stats.towers_count || 1 }}</p>
                    <span class="text-[9px] font-bold text-on-surface-variant/60 uppercase tracking-wider flex items-center gap-1">
                        <span class="material-symbols-rounded text-xs">domain</span> Distribución
                    </span>
                </div>

                <div class="p-5 rounded-3xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 space-y-1">
                    <p class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest">Suma Coeficiente</p>
                    <p class="text-2xl font-black text-on-surface dark:text-white tracking-tight tabular-nums">
                        {{ result.stats.sum_coeficientes.toFixed(4) }}%
                    </p>
                    <span 
                        :class="result.stats.is_100_percent ? 'text-emerald-500' : 'text-amber-500'" 
                        class="text-[9px] font-bold uppercase tracking-wider flex items-center gap-1"
                    >
                        <span class="material-symbols-rounded text-xs">{{ result.stats.is_100_percent ? 'verified_user' : 'info' }}</span>
                        {{ result.stats.is_100_percent ? 'Ley 675 OK (100%)' : 'Ajuste requerido' }}
                    </span>
                </div>

                <div class="p-5 rounded-3xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 space-y-1">
                    <p class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest">Modelos</p>
                    <p class="text-2xl font-black text-on-surface dark:text-white tracking-tight">
                        {{ Object.keys(result.stats.models_breakdown || {}).length }}
                    </p>
                    <span class="text-[9px] font-bold text-primary uppercase tracking-wider flex items-center gap-1">
                        <span class="material-symbols-rounded text-xs">category</span> Tipologías
                    </span>
                </div>
            </div>

            <!-- Model Breakdown Pills -->
            <div v-if="Object.keys(result.stats.models_breakdown || {}).length > 0" class="space-y-3">
                <p class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-widest">
                    Censo por Modelo Arquitectónico
                </p>
                <div class="flex flex-wrap gap-2.5">
                    <div 
                        v-for="(count, modelo) in result.stats.models_breakdown" 
                        :key="modelo"
                        class="px-4 py-2 bg-surface-container-high dark:bg-white/5 rounded-2xl border border-outline-variant/10 dark:border-white/10 flex items-center gap-2"
                    >
                        <span class="text-xs font-black text-on-surface dark:text-white uppercase tracking-wider">{{ modelo }}</span>
                        <span class="px-2 py-0.5 rounded-lg bg-primary text-white text-[10px] font-black">{{ count }}</span>
                    </div>
                </div>
            </div>

            <!-- Warnings / Auto-calculations (if any) -->
            <div v-if="result.warnings.length > 0" class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-700 dark:text-amber-300 text-xs font-medium space-y-1">
                <p v-for="(warn, wIdx) in result.warnings" :key="wIdx" class="flex items-start gap-2">
                    <span class="material-symbols-rounded text-sm shrink-0">info</span>
                    <span>{{ warn }}</span>
                </p>
            </div>

            <!-- Preview Sample Table -->
            <div v-if="result.preview_rows.length > 0" class="space-y-3">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-widest">
                        Muestra Previa de Unidades (Primeras {{ result.preview_rows.length }})
                    </p>
                    <span class="text-[9px] font-bold text-on-surface-variant/40 uppercase tracking-widest">
                        Total en archivo: {{ result.stats.total_units }}
                    </span>
                </div>
                
                <div class="overflow-x-auto rounded-3xl border border-outline-variant/10 dark:border-white/5 bg-surface-container-lowest dark:bg-white/[0.01]">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="border-b border-outline-variant/10 dark:border-white/5 bg-surface-container-low dark:bg-white/[0.02] text-[9px] font-black text-on-surface-variant/60 uppercase tracking-widest">
                                <th class="p-3.5">Torre</th>
                                <th class="p-3.5">Unidad</th>
                                <th class="p-3.5">Modelo</th>
                                <th class="p-3.5">Propietario</th>
                                <th class="p-3.5">WhatsApp / Cel</th>
                                <th class="p-3.5 text-right">Coeficiente</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5 dark:divide-white/5 font-medium">
                            <tr v-for="(row, rIdx) in result.preview_rows" :key="rIdx" class="hover:bg-primary/5 transition-colors">
                                <td class="p-3.5 text-on-surface-variant/70">{{ row.torre || '-' }}</td>
                                <td class="p-3.5 font-bold text-on-surface dark:text-white">{{ row.nombre }}</td>
                                <td class="p-3.5">
                                    <span class="px-2.5 py-1 rounded-lg bg-on-surface/5 dark:bg-white/5 text-[10px] font-black text-primary uppercase tracking-wider">
                                        {{ row.modelo }}
                                    </span>
                                </td>
                                <td class="p-3.5 text-on-surface dark:text-white">{{ row.propietario || 'Sin asignar' }}</td>
                                <td class="p-3.5 text-on-surface-variant/70 tabular-nums">{{ row.telefono || '-' }}</td>
                                <td class="p-3.5 text-right font-black text-on-surface dark:text-white tabular-nums">
                                    {{ row.coeficiente !== null ? Number(row.coeficiente).toFixed(4) + '%' : '-' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="pt-4 flex flex-col sm:flex-row items-center justify-end gap-3 border-t border-outline-variant/10 dark:border-white/5">
                <Button 
                    @click="emit('close')" 
                    variant="neutral" 
                    outline 
                    class="w-full sm:w-auto !rounded-2xl !px-6 cursor-pointer"
                    :disabled="isSubmitting"
                >
                    Cancelar
                </Button>
                <Button 
                    v-if="result.is_valid"
                    @click="confirmImport" 
                    variant="primary" 
                    size="lg" 
                    class="w-full sm:w-auto !rounded-2xl !px-8 font-black uppercase tracking-wider cursor-pointer shadow-xl shadow-primary/20"
                    :loading="isSubmitting"
                    icon="cloud_done"
                >
                    Confirmar e Importar Copropiedad
                </Button>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(150, 150, 150, 0.3);
    border-radius: 4px;
}
</style>
