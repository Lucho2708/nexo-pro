<script setup lang="ts">
import { ref, watch, computed } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Badge from '@/Components/UI/Badge.vue';
import Button from '@/Components/UI/Button.vue';
import Select from '@/Components/UI/Select.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import Modal from '@/Components/UI/Modal.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';
import Table from '@/Components/UI/Table.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    logs: any;
    filters: Record<string, string>;
    metrics: Record<string, number>;
}>();

const isRefreshing = ref(false);
const toast = useToast();

const tableColumns = [
    { key: 'severidad', label: 'SEVERIDAD', sortable: true, sortField: 'level_name' },
    { key: 'timestamp', label: 'TIMESTAMP UTC', sortable: true, sortField: 'created_at' },
    { key: 'mensaje', label: 'MENSAJE / RESUMEN', sortable: true, sortField: 'message' },
    { key: 'modulo', label: 'MÓDULO / TENANT', sortable: true },
    { key: 'accion', label: 'ACCIÓN', sortable: false },
];

const levelConfigs: Record<string, { color: string; icon: string; bg: string; border: string }> = {
    INFO:      { color: 'text-blue-500', bg: 'bg-blue-500/10', border: 'border-blue-500/20', icon: 'info' },
    NOTICE:    { color: 'text-emerald-500', bg: 'bg-emerald-500/10', border: 'border-emerald-500/20', icon: 'notifications' },
    WARNING:   { color: 'text-amber-500', bg: 'bg-amber-500/10', border: 'border-amber-500/20', icon: 'warning' },
    ERROR:     { color: 'text-red-500', bg: 'bg-red-500/10', border: 'border-red-500/20', icon: 'chat_error' },
    CRITICAL:  { color: 'text-pink-500', bg: 'bg-pink-500/10', border: 'border-pink-500/20', icon: 'dangerous' },
    ALERT:     { color: 'text-primary', bg: 'bg-primary/10', border: 'border-primary/20', icon: 'report' },
    EMERGENCY: { color: 'text-primary', bg: 'bg-primary/10', border: 'border-primary/20', icon: 'campaign' },
};

const getLevelConfig = (level: string) => levelConfigs[level] || { color: 'text-on-surface-variant', bg: 'bg-surface-container', border: 'border-outline-variant/10', icon: 'terminal' };

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-CO', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    }).toUpperCase();
};

const truncateMessage = (msg: string, limit = 80) => {
    if (!msg) return 'SIN_MENSAJE';
    return msg.length > limit ? msg.substring(0, limit) + '...' : msg;
};

// Lógica de Modal de Detalle
const showModal = ref(false);
const activeLog = ref<any>(null);

const viewDetail = (log: any) => {
    activeLog.value = log;
    showModal.value = true;
};

// Lógica de Purga
const showPurgeModal = ref(false);
const isPurging = ref(false);

const purgeLogs = () => {
    isPurging.value = true;
    router.post(route('superadmin.logs.purge'), {}, {
        onSuccess: () => {
            showPurgeModal.value = false;
            toast.add('Buffer de logs locales purgado correctamente', 'success');
        },
        onFinish: () => {
            isPurging.value = false;
        }
    });
};
</script>

<template>
    <Head title="Diagnóstico de Sistema — NEXO-PRO" />

    <div class="space-y-8 pb-20 animate-in fade-in duration-700">
        <!-- Header Industrial Refinado -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-1">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-secondary flex items-center justify-center shadow-2xl shadow-secondary/20">
                    <span class="material-symbols-rounded text-2xl text-white">developer_board</span>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">TERMINAL <span class="text-secondary italic">SISTEMA</span></h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                        <p class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]">Monitoreo de Salud de Infraestructura</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="hidden lg:flex items-center gap-3 mr-4">
                     <div v-for="(count, level) in metrics" :key="level" class="px-4 py-2 rounded-xl bg-surface-container-low dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center gap-3 transition-all hover:bg-surface-container">
                        <span class="material-symbols-rounded text-sm" :class="getLevelConfig(level).color">{{ getLevelConfig(level).icon }}</span>
                        <span class="text-[10px] font-black text-on-surface dark:text-white">{{ count }}</span>
                    </div>
                </div>
                <Button variant="primary" icon="history_toggle_off" @click="showPurgeModal = true" class="!rounded-xl !h-10 shadow-lg shadow-primary/10 !text-[9px] font-black uppercase px-6">Configurar Retención</Button>
            </div>
        </div>

        <!-- Main Logs Table -->
        <Card title="Trazabilidad de Eventos" subtitle="Análisis profundo de la red" icon="analytics" class="!p-0 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14] overflow-hidden">
            <template #header>
                <div class="p-10 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Registro <span class="text-secondary italic">Maestro</span></h3>
                        <p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Registro cronológico de anomalías y eventos del core</p>
                    </div>
                </div>
            </template>

            <Table :columns="tableColumns" :data="logs.data" class="border-t border-outline-variant/5 dark:border-white/5">
                <template #cell-severidad="{ row }">
                    <div class="flex items-center gap-3 px-3 py-1.5 rounded-xl border w-fit transition-all" :class="[getLevelConfig(row.level_name).bg, getLevelConfig(row.level_name).border]">
                        <span class="material-symbols-rounded text-sm" :class="getLevelConfig(row.level_name).color">{{ getLevelConfig(row.level_name).icon }}</span>
                        <span class="text-[9px] font-black uppercase tracking-widest text-on-surface dark:text-white">{{ row.level_name }}</span>
                    </div>
                </template>

                <template #cell-timestamp="{ row }">
                    <div class="flex flex-col py-1">
                        <p class="text-[10px] font-black text-on-surface dark:text-white/80 italic">{{ formatDate(row.created_at) }}</p>
                        <p class="text-[8px] font-bold text-on-surface-variant/30 uppercase mt-1 tracking-widest">Servidor Local</p>
                    </div>
                </template>

                <template #cell-mensaje="{ row }">
                    <div class="py-1">
                        <p class="text-xs font-bold text-on-surface dark:text-white leading-relaxed group-hover:text-primary transition-colors max-w-sm overflow-hidden text-ellipsis whitespace-nowrap">
                            {{ truncateMessage(row.message) }}
                        </p>
                    </div>
                </template>

                <template #cell-modulo="{ row }">
                    <div v-if="row.copropiedad" class="flex flex-col py-1">
                        <span class="text-[10px] font-black text-primary uppercase leading-none tracking-tight">{{ row.copropiedad.nombre }}</span>
                        <span class="text-[8px] font-bold text-on-surface-variant/30 uppercase mt-1 tracking-widest">{{ row.user?.name || 'DAEMON' }}</span>
                    </div>
                    <div v-else class="py-1">
                        <span class="text-[9px] font-black text-on-surface-variant/20 dark:text-white/10 uppercase tracking-[0.2em]">SISTEMA_CORE</span>
                    </div>
                </template>

                <template #cell-accion="{ row }">
                    <div class="flex justify-center py-1">
                        <Tooltip text="Ver Stack Trace">
                            <button @click="viewDetail(row)" class="w-10 h-10 rounded-xl bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/40 hover:bg-secondary dark:hover:bg-secondary hover:text-white dark:hover:text-white transition-all shadow-sm active:scale-95">
                                <span class="material-symbols-rounded text-lg font-variation-settings-fill">terminal</span>
                            </button>
                        </Tooltip>
                    </div>
                </template>
            </Table>


        </Card>

        <!-- Modal de Diagnóstico Profundo -->
        <Modal :show="showModal" @close="showModal = false" max-width="3xl">
            <div class="p-10 space-y-10 bg-[#0b0e14] text-white">
                <header class="flex items-start justify-between border-b border-white/5 pb-8">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-[1.8rem] flex items-center justify-center text-white shadow-2xl rotate-3" :class="activeLog ? getLevelConfig(activeLog.level_name).bg : ''">
                             <span class="material-symbols-rounded text-3xl" :class="activeLog ? getLevelConfig(activeLog.level_name).color : ''">{{ activeLog ? getLevelConfig(activeLog.level_name).icon : 'terminal' }}</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-black tracking-tighter uppercase leading-none italic">Diagnóstico <span class="text-secondary font-black">Profundo</span></h3>
                            <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mt-2 italic">Análisis estructural de evento de infraestructura</p>
                        </div>
                    </div>
                    <button @click="showModal = false" class="text-white/20 hover:text-white transition-colors">
                        <span class="material-symbols-rounded text-3xl">close</span>
                    </button>
                </header>

                <div v-if="activeLog" class="space-y-10">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                         <div class="bg-white/5 p-6 rounded-2xl border border-white/5">
                            <span class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2">Timestamp</span>
                            <span class="text-xs font-black text-blue-400 font-mono">{{ formatDate(activeLog.created_at) }}</span>
                         </div>
                         <div class="bg-white/5 p-6 rounded-2xl border border-white/5">
                            <span class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2">Environment</span>
                            <Badge variant="primary" class="!px-4 !py-1 !font-black !text-[10px] uppercase tracking-widest">{{ activeLog.env || 'LOCAL' }}</Badge>
                         </div>
                         <div class="bg-white/5 p-6 rounded-2xl border border-white/5">
                            <span class="text-[9px] font-black text-white/30 uppercase tracking-[0.2em] block mb-2">Log ID</span>
                            <span class="text-xs font-black text-white/80 font-mono">#{{ activeLog.id }}</span>
                         </div>
                    </div>

                    <div class="space-y-4">
                        <h5 class="text-[11px] font-black text-secondary uppercase tracking-[0.3em]">Cuerpo del Evento</h5>
                        <div class="bg-white/[0.03] p-8 rounded-[2.5rem] border border-white/5">
                             <p class="text-sm font-bold text-white leading-relaxed italic border-l-4 border-secondary pl-6">
                                {{ activeLog.message }}
                             </p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h5 class="text-[11px] font-black text-secondary uppercase tracking-[0.3em]">Stack Trace / Contexto JSON</h5>
                        <div class="bg-[#05070a] rounded-[2.5rem] p-8 border border-white/5 shadow-inner relative group overflow-hidden">
                            <div class="absolute top-4 right-6 text-[8px] font-black text-white/10 uppercase tracking-widest">application/json</div>
                            <pre class="text-emerald-400/80 font-mono text-[11px] leading-relaxed overflow-x-auto whitespace-pre-wrap max-h-60 custom-scrollbar">
{{ JSON.stringify(activeLog.context || {}, null, 4) }}
                            </pre>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4 gap-4">
                    <button @click="showModal = false" class="px-12 py-4 bg-secondary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-[1.5rem] shadow-2xl shadow-secondary/30 hover:scale-105 transition-all outline-none">CERRAR DIAGNÓSTICO</button>
                </div>
            </div>
        </Modal>

        <!-- Modal de Confirmación de Purga -->
        <Modal :show="showPurgeModal" @close="showPurgeModal = false" max-width="md">
            <div class="p-10 bg-[#0b0e14] text-white">
                <div class="w-20 h-20 bg-primary/20 rounded-[2rem] flex items-center justify-center mx-auto mb-8 animate-pulse">
                    <span class="material-symbols-rounded text-4xl text-primary">auto_delete</span>
                </div>
                <h3 class="text-2xl font-black text-center uppercase tracking-tighter italic">Limpieza de <span class="text-primary italic">Registros</span></h3>
                <p class="text-[11px] text-white/40 text-center uppercase tracking-[0.2em] mt-4 leading-relaxed">
                    Estás a punto de eliminar todos los logs con más de <span class="text-white font-black">30 días de antigüedad</span>. Esta acción es irreversible.
                </p>
                
                <div class="flex flex-col gap-4 mt-10">
                    <button @click="purgeLogs" :disabled="isPurging" class="w-full py-4 bg-primary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all disabled:opacity-50">
                        {{ isPurging ? 'EJECUTANDO LIMPIEZA...' : 'CONFIRMAR PURGA' }}
                    </button>
                    <button @click="showPurgeModal = false" class="w-full py-4 bg-white/5 text-white/40 font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl hover:text-white transition-all">
                        CANCELAR OPERACIÓN
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
.group:hover .font-variation-settings-fill {
    font-variation-settings: 'FILL' 1;
}
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(16, 185, 129, 0.2);
    border-radius: 10px;
}
</style>
