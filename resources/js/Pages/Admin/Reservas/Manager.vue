<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import Table from '@/Components/UI/Table.vue';
import { useToast } from '@/Composables/useToast';
import { ref } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    reservas: Array<any>;
}>();

const tableColumns = [
    { key: 'zona_fecha', label: 'ACTIVO / CRONOGRAMA', sortable: false },
    { key: 'residente', label: 'USUARIO / UBICACIÓN', sortable: false },
    { key: 'horario', label: 'VECTOR TIEMPO', sortable: false },
    { key: 'costo', label: 'TRANSACCIÓN', sortable: false },
    { key: 'estado', label: 'ESTADO OPERATIVO', sortable: false },
    { key: 'acciones', label: '', sortable: false },
];

const toast = useToast();
const selectedReserva = ref<any>(null);

const form = useForm({
    estado: '',
    notas: ''
});

const openManageModal = (res: any) => {
    selectedReserva.value = res;
    form.estado = res.estado;
    form.notas = res.notas_admin || '';
};

const submitUpdate = () => {
    form.patch(route('admin.reservas.status', selectedReserva.value.id), {
        onSuccess: () => {
            selectedReserva.value = null;
            toast.add('Métrica de reserva actualizada y sincronizada', 'success');
        }
    });
};

const getStatusVariant = (status: string): any => {
    const variants: Record<string, string> = {
        'pendiente': 'warning',
        'aprobada': 'primary',
        'pagada': 'success',
        'cancelada': 'neutral',
        'rechazada': 'error',
    };
    return variants[status] || 'neutral';
};

const getStatusLabel = (status: string): string => {
    const labels: Record<string, string> = {
        'pendiente': 'POR VALIDAR',
        'aprobada': 'CONFIRMADO',
        'pagada': 'LIQUIDADO',
        'cancelada': 'ANULADO',
        'rechazada': 'RECHAZADO',
    };
    return labels[status] || status.toUpperCase();
};

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(value);
};
</script>

<template>
    <Head title="Dispatcher Reservas — NEXO-PRO" />

    <div class="space-y-8 sm:space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Tactical Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 sm:gap-8 px-1">
            <div class="space-y-2 sm:space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-secondary rounded-full shadow-[0_0_10px_rgba(var(--secondary),0.3)]"></div>
                    <p class="text-[9px] sm:text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] sm:tracking-[0.4em] italic leading-none">Módulo de Ocupación y Comunal</p>
                </div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">Gestor de <span class="text-secondary italic">Reservas</span></h2>
                <p class="text-[10px] sm:text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.15em] sm:tracking-[0.2em] mt-1 sm:mt-2 leading-relaxed italic">Dispatcher de disponibilidad, aprobación de vectores de tiempo y liquidación de activos</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                 <Button variant="outline" icon="calendar_view_day" class="!rounded-xl sm:!rounded-2xl h-12 sm:!h-14 px-6 sm:!px-8 text-[10px] sm:text-[11px] font-black uppercase italic w-full sm:w-auto" @click="router.get(route('admin.zonas.index'))">Configurar Espacios</Button>
            </div>
        </div>

        <!-- Main Operational Table -->
        <Card class="!p-0 rounded-[2rem] sm:rounded-[2.8rem] md:!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14] overflow-hidden">
            <template #header>
                <div class="p-5 sm:p-8 md:p-10 flex items-center gap-4 sm:gap-5">
                    <div class="w-12 sm:w-14 h-12 sm:h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary border border-secondary/20 shadow-inner">
                        <span class="material-symbols-rounded text-2xl">event_available</span>
                    </div>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Cronograma <span class="text-secondary italic">Tactical</span></h3>
                        <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.25em] sm:tracking-[0.3em] mt-2 sm:mt-3 italic">Control maestro de ocupación por zona común</p>
                    </div>
                </div>
            </template>

            <Table :columns="tableColumns" :data="reservas" class="border-t border-outline-variant/5 dark:border-white/5">
                <template #cell-zona_fecha="{ row }">
                    <div class="flex flex-col py-2">
                        <span class="text-base font-black text-primary uppercase tracking-tighter italic leading-none">{{ row.zona.nombre }}</span>
                        <div class="flex items-center gap-2 mt-2">
                             <div class="w-1 h-3 bg-secondary rounded-full"></div>
                             <span class="text-[10px] text-on-surface-variant/60 dark:text-white/40 font-black uppercase tracking-widest italic">
                                {{ new Date(row.fecha).toLocaleDateString('es-ES', { day: '2-digit', month: 'short', year: 'numeric' }) }}
                             </span>
                        </div>
                    </div>
                </template>

                <template #cell-residente="{ row }">
                    <div class="flex items-center gap-4 py-2">
                        <div class="w-12 h-12 rounded-2xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-xs font-black text-secondary border border-outline-variant/10 shadow-sm relative group overflow-hidden">
                             <div class="absolute inset-0 bg-secondary/10 translate-y-full group-hover:translate-y-0 transition-transform duration-500"></div>
                             <span class="relative z-10 material-symbols-rounded">person</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[11px] font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none">{{ row.user.name }}</span>
                            <span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1.5 leading-none">TORRE {{ row.unidad.torre }} - {{ row.unidad.nombre }}</span>
                        </div>
                    </div>
                </template>

                <template #cell-horario="{ row }">
                    <div class="flex items-center gap-3 bg-surface-container dark:bg-white/5 px-4 py-2.5 rounded-xl border border-outline-variant/10 w-fit group hover:border-secondary/30 transition-all">
                        <span class="material-symbols-rounded text-base text-secondary animate-pulse">timer</span>
                        <span class="text-[11px] font-black text-on-surface dark:text-white italic tracking-widest uppercase">{{ row.hora_inicio.slice(0,5) }} <span class="text-secondary opacity-40 mx-1">/</span> {{ row.hora_fin.slice(0,5) }}</span>
                    </div>
                </template>

                <template #cell-costo="{ row }">
                    <div class="flex flex-col">
                        <span class="text-[13px] font-black text-on-surface dark:text-white italic tracking-tighter leading-none whitespace-nowrap">{{ row.monto_pagado > 0 ? formatCurrency(row.monto_pagado) : 'COSTO CERO' }}</span>
                        <span class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1 italic">{{ row.monto_pagado > 0 ? 'VÍCTOR LIQUIDADO' : 'USO CORTESÍA' }}</span>
                    </div>
                </template>

                <template #cell-estado="{ row }">
                    <div class="flex justify-center">
                        <Badge :variant="getStatusVariant(row.estado)" class="!px-6 !py-1 !font-black !text-[8.5px] tracking-[0.15em] uppercase italic border-2 tabular-nums">
                            {{ getStatusLabel(row.estado) }}
                        </Badge>
                    </div>
                </template>

                <template #cell-acciones="{ row }">
                    <div class="flex justify-end pr-8">
                        <Button 
                            variant="primary" 
                            size="md" 
                            icon="settings_input_component" 
                            class="!rounded-2xl !h-12 !px-8 !text-[10px] font-black uppercase italic shadow-lg shadow-primary/10" 
                            @click="openManageModal(row)"
                        >
                            Gestionar
                        </Button>
                    </div>
                </template>
            </Table>
        </Card>

        <!-- Dynamic Management Console -->
        <Modal 
            v-if="selectedReserva" 
            @close="selectedReserva = null"
            class="!max-w-2xl"
        >
            <template #title>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary border border-secondary/20">
                        <span class="material-symbols-rounded text-2xl">published_with_changes</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Mando de <span class="text-secondary italic">Aprobación</span></h3>
                        <p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1">Sincronización de solicitud ID: #{{ selectedReserva.id }}</p>
                    </div>
                </div>
            </template>

            <div class="space-y-12 mt-12 pb-6">
                <!-- Info Cluster -->
                <div class="grid grid-cols-2 gap-6 bg-surface-container-low dark:bg-white/[0.03] p-8 rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-inner">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-primary uppercase tracking-wider">Zona Común:</p>
                        <p class="text-lg font-black text-slate-900 dark:text-white uppercase">{{ selectedReserva.zona.nombre }}</p>
                    </div>
                    <div class="text-right space-y-1">
                        <p class="text-xs font-bold text-primary uppercase tracking-wider">Fecha de Reserva:</p>
                        <p class="text-sm font-bold text-slate-600 dark:text-slate-300 capitalize">{{ new Date(selectedReserva.fecha).toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long' }) }}</p>
                    </div>
                </div>

                <form @submit.prevent="submitUpdate" class="space-y-8">
                    <div class="space-y-3">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Estado de la Reserva</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                            <button 
                                v-for="st in ['pendiente', 'aprobada', 'pagada', 'rechazada', 'cancelada']"
                                :key="st"
                                type="button"
                                @click="form.estado = st"
                                class="h-12 px-3 rounded-xl border text-xs font-bold uppercase tracking-wider transition-all duration-200"
                                :class="[
                                    form.estado === st 
                                        ? 'border-blue-600 bg-blue-600 text-white shadow-md shadow-blue-500/20' 
                                        : 'border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:border-blue-400'
                                ]"
                            >
                                {{ getStatusLabel(st) }}
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Observaciones para el Residente</label>
                        <textarea 
                            v-model="form.notes" 
                            rows="4" 
                            class="w-full bg-slate-50/70 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-sm font-medium text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500"
                            placeholder="Aclaraciones sobre entrega de llaves, aforo permitido o motivos de rechazo..."
                        ></textarea>
                    </div>

                    <div class="flex flex-col gap-3 pt-4 border-t border-slate-100 dark:border-slate-800">
                        <Button 
                            type="submit" 
                            variant="primary" 
                            size="lg" 
                            class="w-full !rounded-xl font-bold shadow-lg shadow-primary/20 min-h-[48px]"
                            :disabled="form.processing"
                            icon="check_circle"
                        >
                            {{ form.processing ? 'Guardando...' : 'Actualizar Estado de Reserva' }}
                        </Button>
                        <Button type="button" variant="ghost" class="w-full text-slate-500 hover:text-slate-900 dark:hover:text-white" @click="selectedReserva = null">Cancelar</Button>
                    </div>
                </form>
            </div>
        </Modal>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
