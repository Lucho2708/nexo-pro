<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import SecurityLayout from '@/Layouts/SecurityLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Modal from '@/Components/UI/Modal.vue';

defineOptions({ layout: SecurityLayout });

interface MinutaItem {
    id: string;
    copropiedad_id: string;
    guarda_id: string;
    guarda?: { id: string; name: string };
    tipo_evento: string;
    prioridad: 'informativo' | 'advertencia' | 'urgente';
    descripcion: string;
    hash_inmutable?: string;
    prev_hash?: string;
    evidencia_foto_path?: string;
    firmado_digitalmente?: boolean;
    created_at: string;
}

interface AuditoriaForense {
    es_valida: boolean;
    bloques_verificados: number;
    bloque_corrupto_id: string | null;
}

const props = withDefaults(defineProps<{
    minutas: MinutaItem[];
    auditoria?: AuditoriaForense;
}>(), {
    auditoria: () => ({
        es_valida: true,
        bloques_verificados: 0,
        bloque_corrupto_id: null,
    }),
});

const isModalOpen = ref(false);
const activeFilter = ref<'todos' | 'urgente' | 'ronda' | 'cambio_turno'>('todos');
const previewFoto = ref<string | null>(null);

const form = useForm({
    tipo_evento: 'novedad',
    prioridad: 'informativo',
    descripcion: '',
    evidencia_foto: null as File | null,
});

const handleFotoChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.evidencia_foto = target.files[0];
        previewFoto.value = URL.createObjectURL(target.files[0]);
    }
};

const clearFoto = () => {
    form.evidencia_foto = null;
    previewFoto.value = null;
};

const submitMinuta = () => {
    form.post(route('porteria.minuta.store'), {
        onSuccess: () => {
            form.reset();
            previewFoto.value = null;
            isModalOpen.value = false;
        },
    });
};

const minutasFiltradas = computed(() => {
    if (activeFilter.value === 'todos') return props.minutas;
    if (activeFilter.value === 'urgente') return props.minutas.filter(m => m.prioridad === 'urgente');
    return props.minutas.filter(m => m.tipo_evento === activeFilter.value);
});

const formatHash = (hash?: string) => {
    if (!hash) return 'PENDIENTE_FIRMA';
    return `${hash.slice(0, 8)}...${hash.slice(-6)}`;
};
</script>

<template>
    <Head title="Minuta Digital Forense • Portería" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Superintendencia de Vigilancia</span>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                        Blockchain Log SHA-256
                    </span>
                </div>
                <h2 class="text-2xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-1">
                    Minuta Digital Forense
                </h2>
            </div>

            <button
                @click="isModalOpen = true"
                type="button"
                class="min-h-[52px] px-6 py-3.5 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/25 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
            >
                <span class="material-symbols-rounded text-xl">edit_note</span>
                Registrar Novedad Forense
            </button>
        </div>

        <!-- Forensics Audit Banner -->
        <div 
            class="p-4 rounded-2xl border flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs"
            :class="[
                auditoria.es_valida 
                    ? 'bg-emerald-500/5 border-emerald-500/25 text-emerald-600 dark:text-emerald-400' 
                    : 'bg-rose-500/10 border-rose-500/40 text-rose-600 dark:text-rose-400'
            ]"
        >
            <div class="flex items-center gap-3">
                <span class="material-symbols-rounded text-2xl">
                    {{ auditoria.es_valida ? 'verified_user' : 'gpp_bad' }}
                </span>
                <div>
                    <h4 class="text-xs font-black uppercase tracking-wider">
                        {{ auditoria.es_valida ? 'Cadena Criptográfica Íntegra' : 'ALERTA: Adulteración Detectada' }}
                    </h4>
                    <p class="text-[11px] font-medium opacity-90">
                        {{ auditoria.es_valida 
                            ? `${auditoria.bloques_verificados} bloques auditados y sellados criptográficamente. Inalterabilidad garantizada.` 
                            : `Discrepancia detectada en el bloque ${auditoria.bloque_corrupto_id}. Posible manipulación externa.` 
                        }}
                    </p>
                </div>
            </div>

            <span class="text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-xl bg-surface/50 border border-current self-start sm:self-auto">
                {{ auditoria.es_valida ? 'Auditada' : 'Comprometida' }}
            </span>
        </div>

        <!-- Filters -->
        <div class="w-full max-w-full flex items-center gap-2 overflow-x-auto overscroll-x-contain touch-pan-x no-scrollbar pb-1">
            <button
                @click="activeFilter = 'todos'"
                type="button"
                class="min-h-[44px] px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all border"
                :class="activeFilter === 'todos' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container border-outline-variant/15 text-on-surface-variant hover:text-on-surface'"
            >
                Todas las Anotaciones ({{ minutas.length }})
            </button>
            <button
                @click="activeFilter = 'urgente'"
                type="button"
                class="min-h-[44px] px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all border"
                :class="activeFilter === 'urgente' ? 'bg-rose-500 text-white border-rose-500 shadow-sm' : 'bg-surface-container border-outline-variant/15 text-rose-500 hover:bg-rose-500/10'"
            >
                Urgentes / Incidentes
            </button>
            <button
                @click="activeFilter = 'ronda'"
                type="button"
                class="min-h-[44px] px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all border"
                :class="activeFilter === 'ronda' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container border-outline-variant/15 text-on-surface-variant hover:text-on-surface'"
            >
                Rondas Perimetrales
            </button>
            <button
                @click="activeFilter = 'cambio_turno'"
                type="button"
                class="min-h-[44px] px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider transition-all border"
                :class="activeFilter === 'cambio_turno' ? 'bg-primary text-white border-primary shadow-sm' : 'bg-surface-container border-outline-variant/15 text-on-surface-variant hover:text-on-surface'"
            >
                Cambios de Turno
            </button>
        </div>

        <!-- Timeline of Events -->
        <Card class="p-6 md:p-8 space-y-6 border border-outline-variant/15">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-tight text-on-surface dark:text-white flex items-center gap-2">
                    <span class="material-symbols-rounded text-primary">history_edu</span>
                    Bitácora Cronológica de Turno
                </h3>
                <span class="text-[11px] font-bold text-on-surface-variant">
                    {{ minutasFiltradas.length }} Registros Visualizados
                </span>
            </div>

            <div class="space-y-4">
                <div 
                    v-for="minuta in minutasFiltradas" 
                    :key="minuta.id" 
                    class="p-5 rounded-2xl border transition-all"
                    :class="[
                        minuta.prioridad === 'urgente' 
                            ? 'bg-rose-500/5 border-rose-500/30' 
                            : (minuta.prioridad === 'advertencia' ? 'bg-amber-500/5 border-amber-500/30' : 'bg-surface-container-low border-outline-variant/15')
                    ]"
                >
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-outline-variant/10 pb-3">
                        <div class="flex items-center gap-2.5">
                            <span 
                                class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider"
                                :class="[
                                    minuta.prioridad === 'urgente' ? 'bg-rose-500 text-white' : (minuta.prioridad === 'advertencia' ? 'bg-amber-500 text-slate-950' : 'bg-primary/10 text-primary')
                                ]"
                            >
                                {{ minuta.prioridad }}
                            </span>
                            <span class="text-xs font-black uppercase tracking-wider text-on-surface dark:text-white">
                                {{ minuta.tipo_evento.replace('_', ' ') }}
                            </span>
                        </div>
                        <div class="text-[11px] font-bold text-on-surface-variant flex items-center gap-2">
                            <span>Guarda: {{ minuta.guarda?.name || 'Operador de Garita' }}</span>
                            <span>•</span>
                            <span>{{ new Date(minuta.created_at).toLocaleString('es-CO', { dateStyle: 'short', timeStyle: 'short' }) }}</span>
                        </div>
                    </div>

                    <p class="text-xs text-on-surface dark:text-white/90 font-medium mt-3 whitespace-pre-wrap leading-relaxed">
                        {{ minuta.descripcion }}
                    </p>

                    <!-- Evidencia Fotográfica Thumbnail -->
                    <div v-if="minuta.evidencia_foto_path" class="mt-3">
                        <span class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant block mb-1.5 flex items-center gap-1">
                            <span class="material-symbols-rounded text-sm">photo_camera</span>
                            Evidencia Fotográfica Adjunta
                        </span>
                        <a :href="`/${minuta.evidencia_foto_path}`" target="_blank" class="inline-block p-1 rounded-xl bg-surface-container border border-outline-variant/20 hover:scale-105 transition-all">
                            <img :src="`/${minuta.evidencia_foto_path}`" alt="Evidencia de Novedad" class="w-32 h-20 object-cover rounded-lg" />
                        </a>
                    </div>

                    <!-- Hash Criptográfico Forense -->
                    <div class="mt-4 pt-3 border-t border-outline-variant/10 flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[10px] text-on-surface-variant font-mono">
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-rounded text-sm text-emerald-500">lock</span>
                            <span class="font-bold">SHA-256:</span>
                            <span class="bg-surface-container px-2 py-0.5 rounded-md text-on-surface">{{ formatHash(minuta.hash_inmutable) }}</span>
                        </div>
                        <div class="flex items-center gap-2 opacity-75">
                            <span>Prev: {{ formatHash(minuta.prev_hash) }}</span>
                            <span>•</span>
                            <span class="text-emerald-500 font-bold uppercase tracking-wider">Inmutable</span>
                        </div>
                    </div>
                </div>

                <div v-if="minutasFiltradas.length === 0" class="p-12 text-center text-on-surface-variant font-bold uppercase text-xs">
                    No se encontraron novedades registradas con el filtro seleccionado.
                </div>
            </div>
        </Card>

        <!-- Modal Registrar Minuta Forense -->
        <Modal :show="isModalOpen" @close="isModalOpen = false" max-width="lg">
            <form @submit.prevent="submitMinuta" class="p-6 md:p-8 space-y-6">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Sello Inalterable</span>
                        <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded bg-amber-500/10 text-amber-500 border border-amber-500/20">
                            SuperVigilancia
                        </span>
                    </div>
                    <h3 class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-1">
                        Anotación en Minuta Forense
                    </h3>
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Tipo de Evento *</label>
                            <select v-model="form.tipo_evento" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                                <option value="cambio_turno">Cambio de Turno / Relevo</option>
                                <option value="novedad">Novedad General</option>
                                <option value="ronda">Ronda Perimetral</option>
                                <option value="incidente">Incidente de Seguridad</option>
                                <option value="aviso">Aviso Administrativo</option>
                            </select>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Nivel de Prioridad *</label>
                            <select v-model="form.prioridad" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                                <option value="informativo">Informativo (Ronda normal)</option>
                                <option value="advertencia">Advertencia / Daño menor</option>
                                <option value="urgente">Urgente (Siniestro o intrusión)</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Descripción Detallada del Hecho *</label>
                        <textarea 
                            v-model="form.descripcion" 
                            required 
                            rows="4" 
                            placeholder="Describa de forma precisa la novedad, nombres de personas involucradas, daños observados o acciones ejecutadas." 
                            class="w-full p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none"
                        ></textarea>
                    </div>

                    <!-- Adjuntar Evidencia Fotográfica -->
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">
                            Evidencia Fotográfica de Garita (Opcional)
                        </label>
                        <div class="flex items-center gap-3">
                            <input 
                                type="file" 
                                accept="image/*" 
                                capture="environment"
                                @change="handleFotoChange" 
                                id="foto-evidencia-input" 
                                class="hidden" 
                            />
                            <label 
                                for="foto-evidencia-input"
                                class="min-h-[48px] px-4 py-2.5 rounded-xl bg-surface-container border border-outline-variant/20 hover:border-primary text-xs font-bold text-on-surface cursor-pointer flex items-center gap-2 transition-all active:scale-95"
                            >
                                <span class="material-symbols-rounded text-lg text-primary">add_a_photo</span>
                                <span>{{ form.evidencia_foto ? 'Cambiar Foto' : 'Tomar / Cargar Foto' }}</span>
                            </label>

                            <button 
                                v-if="previewFoto" 
                                @click="clearFoto" 
                                type="button" 
                                class="min-h-[44px] px-3 rounded-xl text-rose-500 hover:bg-rose-500/10 text-xs font-bold"
                            >
                                Quitar foto
                            </button>
                        </div>

                        <div v-if="previewFoto" class="mt-2 p-2 rounded-xl bg-surface-container border border-outline-variant/20 inline-block">
                            <img :src="previewFoto" alt="Vista previa de evidencia" class="w-36 h-24 object-cover rounded-lg" />
                        </div>
                    </div>

                    <!-- Aviso de Inmutabilidad Legal -->
                    <div class="p-3.5 rounded-xl bg-primary/5 border border-primary/20 text-[11px] font-medium text-on-surface flex items-start gap-2.5">
                        <span class="material-symbols-rounded text-primary text-lg shrink-0 mt-0.5">verified</span>
                        <p class="leading-relaxed">
                            <strong>Aviso Legal Forense:</strong> Al confirmar, esta anotación será sellada con hash criptográfico SHA-256 encadenado y no podrá ser modificada ni eliminada, sirviendo como prueba legal ante autoridades competentes.
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button @click="isModalOpen = false" type="button" class="min-h-[48px] px-4 rounded-xl text-xs font-black uppercase tracking-wider text-on-surface-variant hover:bg-surface-container">
                        Cancelar
                    </button>
                    <button :disabled="form.processing" type="submit" class="min-h-[48px] px-6 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-wider shadow-md hover:scale-[1.02] active:scale-95 disabled:opacity-50 flex items-center gap-2">
                        <span class="material-symbols-rounded text-lg">enhanced_encryption</span>
                        Firmar y Registrar Novedad
                    </button>
                </div>
            </form>
        </Modal>
    </div>
</template>
