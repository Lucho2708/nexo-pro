<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Select from '@/Components/UI/Select.vue';
import Modal from '@/Components/UI/Modal.vue';
import Table from '@/Components/UI/Table.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    tickets: Array<any>;
    stats: any;
}>();

const toast = useToast();
const selectedTicket = ref<any>(null);
const showManageModal = ref(false);

const form = useForm({
    status: '',
    priority: '',
    resolution_notes: '',
});

const tableColumns = [
    { key: 'subject', label: 'INCIDENCIA / REPORTE', sortable: true },
    { key: 'tenant', label: 'ORIGEN / TENANT', sortable: true },
    { key: 'priority', label: 'CRITICIDAD', sortable: true },
    { key: 'status', label: 'ESTADO', sortable: true },
    { key: 'actions', label: 'MANDO', sortable: false },
];

const priorityOptions = [
    { value: 'low', label: 'BAJA (Consulta / Duda)' },
    { value: 'medium', label: 'MEDIA (Error parcial)' },
    { value: 'high', label: 'ALTA (Función bloqueada)' },
    { value: 'critical', label: 'CRÍTICA (Bloqueo Operativo)' },
];

const statusOptions = [
    { value: 'open', label: 'ABIERTO (Recibido)' },
    { value: 'in_progress', label: 'EN ANÁLISIS / TRABAJO' },
    { value: 'resolved', label: 'RESUELTO / DESPLEGADO' },
    { value: 'closed', label: 'CERRADO / ARCHIVADO' },
];

const openManageModal = (ticket: any) => {
    selectedTicket.value = ticket;
    form.status = ticket.status;
    form.priority = ticket.priority;
    form.resolution_notes = ticket.resolution_notes || '';
    showManageModal.value = true;
};

const submitUpdate = () => {
    form.patch(route('superadmin.support.update', selectedTicket.value.id), {
        onSuccess: () => {
            showManageModal.value = false;
            toast.add('Trazabilidad de incidencia actualizada', 'primary');
        }
    });
};

const getStatusVariant = (status: string) => {
    const variants: Record<string, string> = {
        'open': 'warning',
        'in_progress': 'primary',
        'resolved': 'success',
        'closed': 'neutral'
    };
    return variants[status] || 'neutral';
};

const getPriorityColor = (priority: string) => {
    const colors: Record<string, string> = {
        'low': 'text-emerald-500 bg-emerald-500/10 border-emerald-500/20',
        'medium': 'text-amber-500 bg-amber-500/10 border-amber-500/20',
        'high': 'text-orange-500 bg-orange-500/10 border-orange-500/20',
        'critical': 'text-red-500 bg-red-500/10 border-red-500/20 animate-pulse'
    };
    return colors[priority] || 'text-white/20 bg-white/5 border-white/5';
};

const getCategoryLabel = (cat: string) => {
    const categories: Record<string, string> = {
        'payments': 'FINANCIERO / PASARELA',
        'reservations': 'OPERATIVO / RESERVAS',
        'billing': 'ESTRUCTURAL / CARTERA',
        'ui_ux': 'FRONTHEND / VISUAL',
        'access': 'SEGURIDAD / ACCESO',
        'performance': 'INFRAESTRUCTURA / LENTITUD',
        'other': 'MISCELÁNEO'
    };
    return categories[cat] || cat.toUpperCase();
};
</script>

<template>
    <Head title="Control Maestro de Soporte — SuperAdmin" />

    <div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Tactical Header Industrial -->
        <div class="flex flex-col xl:flex-row xl:items-center justify-between gap-10 px-1">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-8 bg-secondary rounded-full shadow-[0_0_15px_rgba(var(--secondary),0.4)]"></div>
                    <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/30 uppercase tracking-[0.5em] italic leading-none">Dispatcher General de Infraestructura</p>
                </div>
                <h2 class="text-6xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">
                    Mando de <span class="text-secondary italic">Soporte</span>
                </h2>
                <p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic">Monitoreo de incidencias globales y calibración de criticidad del sistema</p>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-6">
                <div v-for="(count, status) in stats" :key="status" class="px-8 py-6 rounded-[2.5rem] bg-white dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 flex flex-col items-center min-w-[140px] shadow-2xl relative overflow-hidden group">
                    <div class="absolute inset-0 bg-secondary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <span class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-[0.4em] mb-2 relative z-10 italic">{{ status.replace('_',' ') }}</span>
                    <span class="text-4xl font-black text-on-surface dark:text-white italic tracking-tighter relative z-10">{{ count }}</span>
                </div>
            </div>
        </div>

        <!-- Master Tickets Tactical Board -->
        <Card class="!p-0 !rounded-[4rem] border-2 border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14] overflow-hidden">
             <Table :columns="tableColumns" :data="tickets" class="border-t border-outline-variant/5 dark:border-white/5">
                <template #cell-subject="{ row }">
                    <div class="flex flex-col gap-2 py-2">
                        <span class="text-xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none transition-transform group-hover:translate-x-1">{{ row.subject }}</span>
                        <div class="flex items-center gap-3">
                            <div class="w-2 h-4 bg-secondary/40 rounded-full"></div>
                            <span class="text-[10px] font-black text-secondary/60 uppercase tracking-widest italic">{{ getCategoryLabel(row.category) }}</span>
                        </div>
                    </div>
                </template>

                <template #cell-tenant="{ row }">
                    <div class="flex flex-col gap-1 py-2">
                        <span class="text-[13px] font-black text-on-surface dark:text-white/80 uppercase tracking-tighter">{{ row.copropiedad?.nombre || 'SISTEMA_CORE' }}</span>
                        <span class="text-[10px] font-bold text-on-surface-variant/30 uppercase tracking-widest italic font-mono">{{ row.user?.name }}</span>
                    </div>
                </template>

                <template #cell-priority="{ row }">
                    <div class="px-6 py-2 rounded-2xl border-2 tabular-nums inline-flex items-center gap-3 transition-all group-hover:scale-105 shadow-sm" :class="getPriorityColor(row.priority)">
                        <span class="text-[10px] font-black uppercase tracking-widest italic">{{ row.priority }}</span>
                    </div>
                </template>

                <template #cell-status="{ row }">
                    <Badge :variant="getStatusVariant(row.status)" class="!px-7 !py-2 !font-black !text-[10px] tracking-[0.25em] uppercase italic border-2 !rounded-xl shadow-inner">
                        {{ row.status.replace('_', ' ') }}
                    </Badge>
                </template>

                <template #cell-actions="{ row }">
                    <div class="flex justify-end pr-4">
                        <Button variant="ghost" icon="terminal" class="!w-16 !h-16 !p-0 !rounded-[1.5rem] transition-all hover:bg-secondary/10 hover:text-secondary border-2 border-outline-variant/10 dark:border-white/5 active:scale-90 shadow-xl" @click="openManageModal(row)"></Button>
                    </div>
                </template>
             </Table>
        </Card>

        <!-- Tactical Resolution Modal PRO - 3 Tier Design -->
        <Modal :show="showManageModal" @close="showManageModal = false" :max-width="'4xl'" title="Gestión de Infraestructura">
            <div v-if="selectedTicket" class="space-y-12">
                <!-- Data Display Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    <div class="lg:col-span-2 bg-white/[0.02] p-10 rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 relative italic shadow-inner">
                        <p class="text-[10px] font-black text-secondary uppercase tracking-[0.4em] mb-6 leading-none italic">Reporte del Usuario:</p>
                        <p class="text-[15px] font-medium text-on-surface-variant dark:text-white/70 leading-relaxed">{{ selectedTicket.description }}</p>
                    </div>
                    <div class="flex flex-col gap-6">
                        <div class="p-8 rounded-[2rem] bg-secondary/5 border border-secondary/10 flex flex-col justify-center">
                            <span class="text-[9px] font-black text-on-surface/40 uppercase tracking-widest mb-1">RADICADO #</span>
                            <span class="text-xl font-black text-secondary tracking-tighter tabular-nums">{{ selectedTicket.id.slice(0,12).toUpperCase() }}</span>
                        </div>
                        <div class="p-8 rounded-[2rem] bg-white/[0.01] border border-white/5 flex flex-col justify-center">
                            <span class="text-[9px] font-black text-on-surface/40 uppercase tracking-widest mb-1">EMISOR</span>
                            <span class="text-xs font-black text-on-surface dark:text-white uppercase tracking-tighter truncate">{{ selectedTicket.user.name }}</span>
                        </div>
                    </div>
                </div>

                <!-- Update Form Section -->
                <form @submit.prevent="submitUpdate" class="space-y-12 px-1">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        <Select 
                            v-model="form.priority"
                            label="Re-calibrar Impacto Real"
                            :options="priorityOptions"
                            icon="shield_with_heart"
                        />
                        <Select 
                            v-model="form.status"
                            label="Estado de la Operación"
                            :options="statusOptions"
                            icon="query_stats"
                        />
                    </div>

                    <div class="space-y-4">
                        <div class="flex items-center gap-3 ml-2">
                             <div class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></div>
                             <label class="text-[10px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.3em] italic">Bitácora de Ingeniería (Feed de Usuario)</label>
                        </div>
                        <textarea 
                            v-model="form.resolution_notes" 
                            rows="6" 
                            class="w-full bg-surface-container-low dark:bg-white/[0.03] border-2 border-outline-variant/10 dark:border-white/5 rounded-[3.5rem] p-10 text-[14px] font-medium text-on-surface dark:text-white/90 focus:ring-8 focus:ring-secondary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-2xl resize-none no-scrollbar" 
                            placeholder="Escriba el diagnóstico final o el estado actual de la resolución..."
                        ></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-6 border-t border-outline-variant/10 dark:border-white/5 pt-12">
                        <Button type="button" variant="ghost" class="sm:w-1/3 !h-20 !rounded-[2.5rem] !text-[10px] font-black uppercase tracking-[0.3em] italic" @click="showManageModal = false">
                            Cerrar Monitor
                        </Button>
                        <Button type="submit" variant="secondary" size="lg" class="sm:w-2/3 !h-20 !rounded-[2.5rem] !text-xs font-black uppercase italic shadow-2xl shadow-secondary/20" :disabled="form.processing" icon="verified">
                            {{ form.processing ? 'ACTUALIZANDO NÚCLEO...' : 'Sincronizar Resolución Técnica' }}
                        </Button>
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
.no-scrollbar::-webkit-scrollbar { display: none; }
</style>
