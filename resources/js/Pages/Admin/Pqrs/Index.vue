<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';
import Table from '@/Components/UI/Table.vue';
import { useToast } from '@/Composables/useToast';
import { ref } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    pqrs: Array<any>;
    metrics: Record<string, number>;
}>();

const tableColumns = [
    { key: 'radicado', label: 'RADICADO TÉCNICO', sortable: false },
    { key: 'residente', label: 'ORIGEN / UNIDAD', sortable: false },
    { key: 'asunto_tipo', label: 'INCIDENCIA / SEGMENTO', sortable: false },
    { key: 'prioridad', label: 'NIVEL DE ESCALADA', sortable: false },
    { key: 'estado', label: 'ESTADO DE FLUJO', sortable: false },
    { key: 'acciones', label: '', sortable: false },
];

const toast = useToast();
const selectedPqrs = ref<any>(null);

const form = useForm({
    respuesta: '',
    cerrar: true,
});

const openResponseModal = (item: any) => {
    selectedPqrs.value = item;
    form.respuesta = item.respuesta || '';
};

const submitResponse = () => {
    form.patch(route('pqrs.update', selectedPqrs.value.id), {
        onSuccess: () => {
            selectedPqrs.value = null;
            form.reset();
            toast.add('Protocolo de respuesta enviado exitosamente', 'success');
        },
    });
};

const getStatusVariant = (status: string): any => {
    const variants: Record<string, string> = {
        'abierto': 'primary',
        'en_proceso': 'warning',
        'cerrado': 'neutral',
        'reabierto': 'error',
    };
    return variants[status] || 'neutral';
};

const getStatusLabel = (status: string): string => {
    const labels: Record<string, string> = {
        'abierto': 'NUEVO REQUERIMIENTO',
        'en_proceso': 'ANÁLISIS ACTIVO',
        'cerrado': 'CASO RESUELTO',
        'reabierto': 'ESCALADA CRÍTICA',
    };
    return labels[status] || status.toUpperCase();
};
</script>

<template>
    <Head title="PQRS Monitor — NEXO-PRO" />

    <div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Tactical Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-primary rounded-full shadow-[0_0_8px_rgba(var(--primary),0.5)]"></div>
                    <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] italic leading-none">Módulo de Atención al Residente</p>
                </div>
                <h2 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">Monitor <span class="text-primary italic">PQRS</span></h2>
                <p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic">Gestión operativa de incidencias, peticiones y protocolos de respuesta</p>
            </div>
            <div class="flex items-center gap-3">
                 <Button variant="outline" icon="hub" class="!rounded-2xl !h-14 !px-8 text-[11px] font-black uppercase italic">Historial Maestro</Button>
            </div>
        </div>

        <!-- Real-time Metrics -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <StatCard label="SOLICITUDES ACTIVAS" :value="(metrics.abierto || 0).toString()" color-class="!bg-primary" class="!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" />
            <StatCard label="EN ANÁLISIS" :value="(metrics.en_proceso || 0).toString()" color-class="!bg-warning" class="!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" />
            <StatCard label="CRÍTICAS / REABIERTAS" :value="(metrics.reabierto || 0).toString()" color-class="!bg-error" class="!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" />
            <StatCard label="RESOLUCIÓN TOTAL" :value="(metrics.cerrado || 0).toString()" color-class="!bg-emerald-500" class="!rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]" />
        </section>

        <!-- Main Incident Table -->
        <Card class="!p-0 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14] overflow-hidden">
            <template #header>
                <div class="p-10 flex items-center gap-4">
                    <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner">
                        <span class="material-symbols-rounded text-2xl">forum</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Bandeja de <span class="text-primary italic">Incidencias</span></h3>
                        <p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Control cronológico de requerimientos residentes</p>
                    </div>
                </div>
            </template>

            <Table :columns="tableColumns" :data="pqrs" class="border-t border-outline-variant/5 dark:border-white/5">
                <template #cell-radicado="{ row }">
                    <div class="flex flex-col">
                        <span class="text-base font-black text-primary tracking-tighter italic whitespace-nowrap">#{{ String(row.id).padStart(5, '0') }}</span>
                        <span class="text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest mt-1 italic">{{ new Date(row.created_at).toLocaleDateString() }}</span>
                    </div>
                </template>

                <template #cell-residente="{ row }">
                    <div class="flex items-center gap-4 py-2">
                        <div class="w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-primary border border-outline-variant/10 shadow-sm">
                             <span class="material-symbols-rounded text-xl">person_pin_circle</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none">TORRE {{ row.unidad.torre }} - {{ row.unidad.nombre }}</span>
                            <span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1.5">{{ row.user.name }}</span>
                        </div>
                    </div>
                </template>

                <template #cell-asunto_tipo="{ row }">
                    <div class="flex flex-col">
                        <span class="text-sm font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">{{ row.asunto }}</span>
                        <span class="text-[9px] text-primary font-black uppercase tracking-[0.2em] mt-2 italic px-2 py-0.5 bg-primary/5 rounded-md inline-block w-fit">{{ row.tipo.replace('_', ' ') }}</span>
                    </div>
                </template>

                <template #cell-prioridad="{ row }">
                    <div class="flex justify-center">
                        <Badge :variant="row.prioridad === 'alta' ? 'error' : (row.prioridad === 'media' ? 'warning' : 'success')" class="!px-5 !py-1 !font-black !text-[10px] tracking-widest uppercase italic">
                            {{ row.prioridad }}
                        </Badge>
                    </div>
                </template>

                <template #cell-estado="{ row }">
                    <div class="flex justify-center">
                        <Badge :variant="getStatusVariant(row.estado)" class="!px-5 !py-1 !font-black !text-[8.5px] tracking-[0.1em] uppercase italic border-2">
                            {{ getStatusLabel(row.estado) }}
                        </Badge>
                    </div>
                </template>

                <template #cell-acciones="{ row }">
                    <div class="flex justify-end pr-6">
                        <Button 
                            variant="primary" 
                            size="md" 
                            icon="shield_with_heart" 
                            class="!rounded-xl !h-12 !px-6 !text-[10px] font-black uppercase italic shadow-lg shadow-primary/10"
                            :loading="form.processing && selectedPqrs?.id === row.id"
                            @click="openResponseModal(row)"
                        >
                            Gestionar
                        </Button>
                    </div>
                </template>
            </Table>
        </Card>

        <!-- Incident Response Console -->
        <Modal 
            v-if="selectedPqrs" 
            @close="selectedPqrs = null"
            class="!max-w-2xl"
        >
            <template #title>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary border border-primary/10">
                        <span class="material-symbols-rounded text-2xl">mark_chat_read</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Gestión Técnica <span class="text-primary italic">#{{ String(selectedPqrs.id).padStart(5, '0') }}</span></h3>
                        <p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1">Resolución de incidencia para Torre {{ selectedPqrs.unidad.torre }}</p>
                    </div>
                </div>
            </template>

            <div class="space-y-10 mt-12 pb-6">
                <!-- Thread Overview -->
                <div class="relative">
                    <div class="absolute left-6 top-10 bottom-0 w-1 bg-gradient-to-b from-primary/20 to-transparent rounded-full"></div>
                    
                    <div class="flex gap-6 items-start relative z-10 mb-10">
                         <div class="w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-on-surface-variant shadow-inner">
                              <span class="material-symbols-rounded text-xl">output</span>
                         </div>
                         <div class="flex-1 bg-surface-container-low dark:bg-white/[0.03] p-8 rounded-[2.5rem] border border-outline-variant/10 dark:border-white/5 shadow-sm">
                             <p class="text-[9px] font-black text-primary uppercase tracking-[0.2em] mb-4 italic">Mensaje del Residente:</p>
                             <p class="text-base font-medium text-on-surface-variant dark:text-white/60 leading-relaxed italic pr-6 italic">"{{ selectedPqrs.mensaje }}"</p>
                         </div>
                    </div>

                    <div class="flex gap-6 items-start relative z-10">
                         <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary shadow-inner">
                              <span class="material-symbols-rounded text-xl">reply_all</span>
                         </div>
                         <div class="flex-1 space-y-6">
                             <form @submit.prevent="submitResponse" class="space-y-8">
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-2 italic">Respuesta Técnica Oficial</label>
                                    <textarea 
                                        v-model="form.respuesta" 
                                        rows="6" 
                                        class="w-full bg-white dark:bg-white/[0.02] border-2 border-outline-variant/10 dark:border-white/5 rounded-[2rem] p-8 text-sm font-medium text-on-surface dark:text-white focus:ring-8 focus:ring-primary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-xl"
                                        placeholder="Redacta la resolución formal detallada..."
                                        required
                                    ></textarea>
                                </div>

                                <div class="flex items-center gap-5 bg-surface-container dark:bg-white/5 p-6 rounded-3xl border border-outline-variant/10 dark:border-white/5 group cursor-pointer hover:bg-primary/5 transition-colors">
                                    <div class="relative flex items-center justify-center w-6 h-6">
                                        <input type="checkbox" v-model="form.cerrar" id="cerrar-pqr" class="peer appearance-none w-6 h-6 rounded-lg bg-surface dark:bg-white/5 border-2 border-outline-variant/30 checked:bg-primary checked:border-primary transition-all cursor-pointer" />
                                        <span class="material-symbols-rounded text-white text-base absolute transition-all opacity-0 peer-checked:opacity-100 scale-50 peer-checked:scale-100">check</span>
                                    </div>
                                    <label for="cerrar-pqr" class="text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest cursor-pointer select-none italic group-hover:text-primary transition-colors">Ejecutar Cierre de Caso y Notificar</label>
                                </div>

                                <div class="flex flex-col gap-4 pt-6">
                                    <Button 
                                        type="submit" 
                                        variant="primary" 
                                        size="lg" 
                                        class="w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20"
                                        :disabled="form.processing"
                                        icon="send_and_archive"
                                    >
                                        {{ form.processing ? 'ENVIANDO PROTOCOLO...' : 'DESPACHAR RESPUESTA' }}
                                    </Button>
                                    <Button type="button" variant="ghost" class="w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40" @click="selectedPqrs = null">Cancelar Operación</Button>
                                </div>
                            </form>
                         </div>
                    </div>
                </div>
            </div>
        </Modal>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
