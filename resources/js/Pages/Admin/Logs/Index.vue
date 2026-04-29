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

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    logs: any;
    filters: Record<string, string>;
    metrics: Record<string, number>;
}>();

const search = ref(props.filters.search || '');
const levelName = ref(props.filters.level_name || '');
const copropiedadId = ref(props.filters.copropiedad_id || '');
const isRefreshing = ref(false);

const debounce = (fn: Function, delay: number) => {
    let timeoutId: ReturnType<typeof setTimeout>;
    return (...args: any[]) => {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => fn(...args), delay);
    };
};

const handleSearch = (manual = false) => {
    if (manual) isRefreshing.value = true;
    router.get(route('admin.logs'), {
        search: search.value,
        level_name: levelName.value,
    }, { 
        preserveState: true, 
        replace: true,
        onFinish: () => {
            if (manual) setTimeout(() => isRefreshing.value = false, 600);
        }
    });
};

const debouncedSearch = debounce(() => handleSearch(), 400);
watch([search, levelName, copropiedadId], debouncedSearch);

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
    router.post(route('admin.logs.purge'), {}, {
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
                <div class="flex items-center gap-2 bg-surface-container-low dark:bg-white/5 p-1.5 rounded-2xl border border-outline-variant/10 dark:border-white/5 shadow-sm mr-2">
                    <Button variant="ghost" icon="refresh" @click="handleSearch(true)" class="!w-10 !h-10 !p-0 !rounded-xl transition-all dark:!text-white/60" :class="{'rotate-180 opacity-50': isRefreshing}"></Button>
                </div>
                <div class="hidden lg:flex items-center gap-3 mr-4">
                     <div v-for="(count, level) in metrics" :key="level" class="px-4 py-2 rounded-xl bg-surface-container-low dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center gap-3 transition-all hover:bg-surface-container">
                        <span class="material-symbols-rounded text-sm" :class="getLevelConfig(level).color">{{ getLevelConfig(level).icon }}</span>
                        <span class="text-[10px] font-black text-on-surface dark:text-white">{{ count }}</span>
                    </div>
                </div>
                <Button variant="primary" icon="history_toggle_off" @click="showPurgeModal = true" class="!rounded-xl !h-10 shadow-lg shadow-primary/10 !text-[9px] font-black uppercase px-6">Configurar Retención</Button>
            </div>
        </div>

        <!-- Tactical Diagnostic Filters -->
        <div class="bg-surface-container-low/50 dark:bg-white/[0.02] backdrop-blur-xl border border-outline-variant/10 dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
                <div class="md:col-span-8 space-y-2">
                    <label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2">Análisis de Eventos (Mensaje, Clase, Traza)</label>
                    <div class="relative group">
                        <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg">search</span>
                        <input 
                            v-model="search"
                            placeholder="Ejecutar escaneo de registros..."
                            class="w-full h-14 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 dark:focus:ring-primary/10 outline-none transition-all placeholder:text-on-surface-variant/20 shadow-sm"
                        />
                    </div>
                </div>

                <div class="md:col-span-4 space-y-2">
                    <label class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] ml-2">Gravedad del Evento</label>
                    <Select 
                        v-model="levelName"
                        :options="[
                            { value: '', label: 'TODOS LOS NIVELES' },
                            { value: 'INFO', label: 'INFORMATIVO' },
                            { value: 'WARNING', label: 'ADVERTENCIAS' },
                            { value: 'ERROR', label: 'FALLOS CRÍTICOS' }
                        ]"
                        class="!h-14 !rounded-2xl !bg-white dark:!bg-white/[0.03] !text-[10px] !font-black dark:!text-white/80 !shadow-sm !border-outline-variant/10 dark:!border-white/5"
                    />
                </div>
            </div>
        </div>

        <!-- Lista de Logs Refinada -->
        <div class="bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[3rem] overflow-hidden shadow-2xl relative">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-surface-container dark:bg-white/[0.01] border-b border-outline-variant/10 dark:border-white/5">
                            <th class="px-8 py-5 text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]">Severidad</th>
                            <th class="px-8 py-5 text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]">Timestamp UTC</th>
                            <th class="px-8 py-5 text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]">Mensaje / Resumen de Diagnóstico</th>
                            <th class="px-8 py-5 text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]">Módulo / Tenant</th>
                            <th class="px-8 py-5 text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/5 dark:divide-white/[0.02]">
                        <tr v-for="log in logs.data" :key="log.id" class="group hover:bg-primary/[0.008] dark:hover:bg-primary/[0.03] transition-all">
                            <td class="px-8 py-6 whitespace-nowrap">
                                <div class="flex items-center gap-3 px-3 py-1.5 rounded-xl border transition-all" :class="[getLevelConfig(log.level_name).bg, getLevelConfig(log.level_name).border]">
                                    <span class="material-symbols-rounded text-sm" :class="getLevelConfig(log.level_name).color">{{ getLevelConfig(log.level_name).icon }}</span>
                                    <span class="text-[9px] font-black uppercase tracking-widest text-on-surface dark:text-white">{{ log.level_name }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 whitespace-nowrap">
                                <p class="text-[10px] font-black text-on-surface dark:text-white/80 italic">{{ formatDate(log.created_at) }}</p>
                                <p class="text-[8px] font-bold text-on-surface-variant/30 uppercase mt-1 tracking-widest">Servidor Local</p>
                            </td>
                            <td class="px-8 py-6 max-w-sm">
                                <p class="text-xs font-bold text-on-surface dark:text-white leading-relaxed group-hover:text-primary transition-colors">
                                    {{ truncateMessage(log.message) }}
                                </p>
                            </td>
                            <td class="px-8 py-6">
                                <div v-if="log.copropiedad" class="flex flex-col">
                                    <span class="text-[10px] font-black text-primary uppercase leading-none tracking-tight">{{ log.copropiedad.nombre }}</span>
                                    <span class="text-[8px] font-bold text-on-surface-variant/30 uppercase mt-1 tracking-widest">{{ log.user?.name || 'DAEMON' }}</span>
                                </div>
                                <div v-else>
                                    <span class="text-[9px] font-black text-on-surface-variant/20 dark:text-white/10 uppercase tracking-[0.2em]">SISTEMA_CORE</span>
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <Tooltip text="Ver Stack Trace">
                                    <button @click="viewDetail(log)" class="w-10 h-10 rounded-xl bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/40 hover:bg-primary dark:hover:bg-primary hover:text-white dark:hover:text-white transition-all shadow-sm active:scale-95 mx-auto">
                                        <span class="material-symbols-rounded text-lg font-variation-settings-fill">terminal</span>
                                    </button>
                                </Tooltip>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr v-if="logs.data.length === 0">
                            <td colspan="5" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center opacity-30">
                                    <span class="material-symbols-rounded text-6xl mb-4">analytics</span>
                                    <p class="text-xs font-black uppercase tracking-[0.3em]">Cero anomalías detectadas en este sector</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-10 py-12 bg-surface-container/5 dark:bg-white/[0.01] border-t border-outline-variant/5 dark:border-white/5 flex justify-center">
                <Pagination :links="logs.links" />
            </div>
        </div>

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
