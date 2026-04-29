<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Modal from '@/Components/UI/Modal.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    tickets: Array<any>;
    categories: Array<{ value: string; label: string }>;
}>();

const toast = useToast();
const showCreateModal = ref(false);
const isLoaded = ref(false);

onMounted(() => {
    setTimeout(() => isLoaded.value = true, 400);
});

const form = useForm({
    subject: '',
    description: '',
    category: 'other',
    priority: 'low',
});

const priorityOptions = [
    { value: 'low', label: 'BAJA (Consulta / Duda)' },
    { value: 'medium', label: 'MEDIA (Error parcial)' },
    { value: 'high', label: 'ALTA (Función bloqueada)' },
    { value: 'critical', label: 'CRÍTICA (Caída de sistema)' },
];

const submitTicket = () => {
    form.post(route('support.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
            toast.add('Misión crítica reportada. Ingeniería en camino.', 'success');
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

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('es-CO', {
        day: '2-digit', month: 'short', year: 'numeric'
    }).toUpperCase();
};
</script>

<template>
    <Head title="Centro de Soporte Técnico — NEXO-PRO" />

    <div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Header Industrial Premium -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-8 bg-primary rounded-full shadow-[0_0_15px_rgba(var(--primary),0.4)]"></div>
                    <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/30 uppercase tracking-[0.5em] italic leading-none">Dispatcher de Incidencias Técnicas</p>
                </div>
                <h2 class="text-5xl md:text-6xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">
                    Soporte <span class="text-primary italic">Nexo-Pro</span>
                </h2>
                <p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic">Reporte de fallos del sistema, errores funcionales y escalabilidad técnica</p>
            </div>
            <Button variant="primary" icon="add_reaction" class="!rounded-[2rem] !h-20 !px-12 text-xs font-black uppercase italic shadow-2xl shadow-primary/20 hover:scale-105 active:scale-95 transition-all" @click="showCreateModal = true">Nuevo reporte de sistema</Button>
        </div>

        <!-- Quick Stats Monitors -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <Card class="!p-10 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] relative overflow-hidden group">
                 <div class="absolute top-0 right-0 w-40 h-40 bg-primary/5 -mr-16 -mt-16 rounded-full blur-3xl group-hover:bg-primary/10 transition-all duration-1000"></div>
                 <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-8 italic leading-none">Tickets en Proceso</p>
                 <div class="flex items-end gap-4">
                    <h3 class="text-6xl font-black text-primary tracking-tighter leading-none italic">{{ tickets.filter(t => t.status === 'in_progress').length }}</h3>
                    <span class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest pb-2 italic">Análisis Activo</span>
                 </div>
            </Card>
            <Card class="!p-10 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]">
                 <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-8 italic leading-none">Resueltos (30 días)</p>
                 <div class="flex items-end gap-4">
                    <h3 class="text-6xl font-black text-emerald-500 tracking-tighter leading-none italic">{{ tickets.filter(t => t.status === 'resolved').length }}</h3>
                    <span class="text-[9px] font-black text-emerald-500/40 uppercase tracking-widest pb-2 italic">Sistema Estable</span>
                 </div>
            </Card>
            <Card class="md:col-span-2 lg:col-span-1 !p-10 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] flex flex-col justify-center bg-gradient-to-br from-emerald-500/[0.03] to-transparent">
                 <div class="flex items-center gap-6">
                    <div class="w-16 h-16 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 border border-emerald-500/20 shadow-lg shadow-emerald-500/10">
                        <span class="material-symbols-rounded text-3xl">verified</span>
                    </div>
                    <div>
                        <p class="text-sm font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-none">Ingeniería Online</p>
                        <p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-2">SLA MONITOR: ACTIVO</p>
                    </div>
                 </div>
            </Card>
        </div>

        <!-- System Incident Feed -->
        <div class="space-y-8">
            <div v-for="ticket in tickets" :key="ticket.id" class="group bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 p-10 rounded-[3.5rem] shadow-xl hover:shadow-primary/5 transition-all duration-700">
                <div class="flex flex-col lg:flex-row gap-10 lg:items-center">
                    <div class="shrink-0 flex items-center justify-center">
                         <div class="w-20 h-20 rounded-[2rem] flex items-center justify-center border-2 shadow-inner transition-transform group-hover:scale-105 duration-500" :class="getPriorityColor(ticket.priority)">
                            <span class="material-symbols-rounded text-4xl leading-none">{{ ticket.priority === 'critical' ? 'priority_high' : 'terminal' }}</span>
                         </div>
                    </div>
                    
                    <div class="flex-1 space-y-4">
                        <div class="flex flex-wrap items-center gap-4">
                            <h4 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">{{ ticket.subject }}</h4>
                            <Badge :variant="getStatusVariant(ticket.status)" class="!px-5 !py-1 !text-[9px] font-black uppercase italic tracking-widest !rounded-xl border-2">
                                {{ ticket.status.replace('_', ' ') }}
                            </Badge>
                        </div>
                        <p class="text-sm border-l-4 border-primary/20 pl-6 font-medium text-on-surface-variant dark:text-white/50 leading-relaxed italic pr-12">{{ ticket.description }}</p>
                        <div class="flex flex-wrap items-center gap-8 pt-4 border-t border-outline-variant/5 dark:border-white/[0.02]">
                             <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-lg text-primary/40">category</span>
                                <span class="text-[10px] font-black text-on-surface dark:text-white/70 uppercase tracking-widest">{{ categories.find(c => c.value === ticket.category)?.label }}</span>
                             </div>
                             <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-lg text-primary/40">calendar_month</span>
                                <span class="text-[10px] font-black text-on-surface dark:text-white/60 uppercase tracking-widest italic tabular-nums">{{ formatDate(ticket.created_at) }}</span>
                             </div>
                             <div class="flex items-center gap-3">
                                <span class="material-symbols-rounded text-lg text-primary/40">fingerprint</span>
                                <span class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/30 uppercase tracking-widest tabular-nums font-mono">RAD_#{{ ticket.id.slice(0,8).toUpperCase() }}</span>
                             </div>
                        </div>
                    </div>

                    <div v-if="ticket.resolution_notes" class="lg:w-1/3 bg-primary/[0.02] dark:bg-white/[0.01] p-8 rounded-[2.5rem] border border-primary/10 relative overflow-hidden backdrop-blur-sm">
                        <div class="absolute top-0 right-0 p-3 opacity-10">
                            <span class="material-symbols-rounded text-4xl">vpn_key</span>
                        </div>
                        <p class="text-[9px] font-black text-primary uppercase tracking-[0.4em] mb-4 leading-none italic">Análisis Final:</p>
                        <p class="text-xs font-semibold text-on-surface-variant dark:text-white/60 leading-relaxed italic pr-4">{{ ticket.resolution_notes }}</p>
                    </div>
                </div>
            </div>

            <!-- Operational Stability State -->
            <div v-if="tickets.length === 0" class="flex flex-col items-center justify-center py-40 border-2 border-dashed border-outline-variant/10 dark:border-white/10 rounded-[4rem] group hover:border-primary/20 transition-all duration-700">
                <div class="w-24 h-24 bg-emerald-500/5 rounded-full flex items-center justify-center mb-8 group-hover:scale-110 transition-transform duration-700 shadow-2xl">
                    <span class="material-symbols-rounded text-6xl text-emerald-500/40">verified</span>
                </div>
                <p class="text-xs font-black text-on-surface-variant/30 uppercase tracking-[0.4em] italic">Sistema Operativo al 100%</p>
            </div>
        </div>

        <!-- Tactical Reporting Modal - 3 Tier Design -->
        <Modal :show="showCreateModal" @close="showCreateModal = false" :max-width="'4xl'" title="Protocolo de Reporte de Incidencias">
            <form @submit.prevent="submitTicket" class="space-y-12">
                <div class="space-y-10">
                    
                    <!-- PC/Tablet Row 1: Subject (Always on top but more readable) -->
                    <div class="space-y-2">
                        <Input 
                            v-model="form.subject"
                            label="Asunto de la Incidencias"
                            placeholder="Ej: Fallo crítico en el motor de reservas..."
                            required
                            icon="emergency"
                            class="px-1"
                        />
                    </div>

                    <!-- PC/Tablet Row 2: Category & Priority (Side-by-side) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-10 px-1">
                        <Select 
                            v-model="form.category"
                            label="Módulo Afectado"
                            :options="categories"
                            icon="settings_input_component"
                        />
                        <Select 
                            v-model="form.priority"
                            label="Criticidad Percibida"
                            :options="priorityOptions"
                            icon="bolt"
                        />
                    </div>

                    <!-- PC/Tablet Row 3: Description (Expansive) -->
                    <div class="space-y-4 px-1">
                        <div class="flex items-center gap-3 ml-2">
                             <div class="w-1 h-4 bg-primary rounded-full"></div>
                             <label class="text-[10px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-[0.3em] italic leading-none">Descripción Técnica y Evidencia del Fallo</label>
                        </div>
                        <textarea 
                            v-model="form.description" 
                            required 
                            rows="8" 
                            class="w-full bg-surface-container-low dark:bg-white/[0.03] border-2 border-outline-variant/10 dark:border-white/5 rounded-[3rem] p-10 text-[13px] font-medium text-on-surface dark:text-white/80 focus:ring-8 focus:ring-primary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic shadow-inner no-scrollbar resize-none" 
                            placeholder="Describa los pasos exactos para reproducir el incidente técnico..."
                        ></textarea>
                    </div>
                </div>

                <!-- Footer Acciones -->
                <div class="flex flex-col sm:flex-row gap-6 border-t border-outline-variant/10 dark:border-white/5 pt-12">
                    <Button type="button" variant="ghost" class="order-2 sm:order-1 sm:w-1/3 !h-20 !rounded-3xl !text-[10px] font-black uppercase tracking-[0.3em] italic" @click="showCreateModal = false">
                        Abortar Reporte
                    </Button>
                    <Button type="submit" variant="primary" size="lg" class="order-1 sm:order-2 sm:w-2/3 !h-20 !rounded-3xl !text-xs font-black uppercase italic shadow-2xl shadow-primary/20" :disabled="form.processing" icon="rocket_launch">
                        {{ form.processing ? 'SINCRONIZANDO...' : 'Lanzar al Equipo de Ingeniería' }}
                    </Button>
                </div>
            </form>
        </Modal>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
.no-scrollbar::-webkit-scrollbar { display: none; }
</style>
