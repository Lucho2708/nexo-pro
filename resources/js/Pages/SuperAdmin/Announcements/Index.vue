<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Modal from '@/Components/UI/Modal.vue';
import ConfirmModal from '@/Components/UI/ConfirmModal.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';

// Definir layout persistente
defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    announcements: Array<any>;
}>();

const activeTab = ref('active'); // active, scheduled, expired, all

const showCreateModal = ref(false);
const isEditing = ref(false);
const confirmDelete = ref({ show: false, id: null as string | null });

const form = useForm({
    id: '',
    title: '',
    message: '',
    type: 'info',
    target_role: 'all',
    starts_at: '',
    expires_at: '',
    is_active: true,
});

// Clasificación de Anuncios por Tiempo
const filteredAnnouncements = computed(() => {
    const now = new Date();
    return props.announcements.filter(ann => {
        const start = ann.starts_at ? new Date(ann.starts_at) : null;
        const end = ann.expires_at ? new Date(ann.expires_at) : null;
        
        const isActiveAtMoment = (!start || start <= now) && (!end || end >= now) && ann.is_active;
        const isScheduled = start && start > now && ann.is_active;
        const isExpired = (end && end < now) || !ann.is_active;

        if (activeTab.value === 'active') return isActiveAtMoment;
        if (activeTab.value === 'scheduled') return isScheduled;
        if (activeTab.value === 'expired') return isExpired;
        return true;
    });
});

const submit = () => {
    if (isEditing.value) {
        form.patch(route('superadmin.announcements.update', form.id), {
            onSuccess: () => {
                showCreateModal.value = false;
                form.reset();
            }
        });
    } else {
        form.post(route('superadmin.announcements.store'), {
            onSuccess: () => {
                showCreateModal.value = false;
                form.reset();
            }
        });
    }
};

const editAnnouncement = (ann: any) => {
    isEditing.value = true;
    form.id = ann.id;
    form.title = ann.title;
    form.message = ann.message;
    form.type = ann.type;
    form.target_role = ann.target_role;
    form.starts_at = ann.starts_at ? ann.starts_at.substring(0, 16) : '';
    form.expires_at = ann.expires_at ? ann.expires_at.substring(0, 16) : '';
    form.is_active = ann.is_active;
    showCreateModal.value = true;
};

const openCreate = () => {
    isEditing.value = false;
    form.reset();
    showCreateModal.value = true;
};

const deleteAnnouncement = (id: string) => {
    confirmDelete.value = { show: true, id };
};

const executeDelete = () => {
    if (confirmDelete.value.id) {
        router.delete(route('superadmin.announcements.destroy', confirmDelete.value.id), {
            onSuccess: () => {
                confirmDelete.value.show = false;
            }
        });
    }
};

const getStatusInfo = (ann: any) => {
    const now = new Date();
    const start = ann.starts_at ? new Date(ann.starts_at) : null;
    const end = ann.expires_at ? new Date(ann.expires_at) : null;

    if (!ann.is_active) return { label: 'DESACTIVADO', color: 'text-gray-400', dot: 'bg-gray-400' };
    if (end && end < now) return { label: 'EXPIRADO', color: 'text-rose-500', dot: 'bg-rose-500' };
    if (start && start > now) return { label: 'PROGRAMADO', color: 'text-amber-500', dot: 'bg-amber-500' };
    return { label: 'ACTIVO', color: 'text-emerald-500', dot: 'bg-emerald-500' };
};

const getTypeConfig = (type: string) => {
    const configs: Record<string, any> = {
        info:    { icon: 'info', color: 'text-blue-500', bg: 'bg-blue-500/10', border: 'border-blue-500/20' },
        warning: { icon: 'warning', color: 'text-amber-500', bg: 'bg-amber-500/10', border: 'border-amber-500/20' },
        danger:  { icon: 'report', color: 'text-rose-500', bg: 'bg-rose-500/10', border: 'border-rose-500/20' },
    };
    return configs[type] || configs.info;
};

const getTimeRemaining = (expiresAt: string) => {
    if (!expiresAt) return 'SIN EXPIRACIÓN';
    const diff = new Date(expiresAt).getTime() - new Date().getTime();
    if (diff < 0) return 'FINALIZADO';
    const days = Math.floor(diff / (1000 * 60 * 60 * 24));
    const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    if (days > 0) return `${days}D ${hours}H RESTANTES`;
    return `${hours} HORAS RESTANTES`;
};
</script>

<template>
    <Head title="Central de Difusión — NEXO-PRO" />

    <div class="space-y-8 pb-20 animate-in fade-in duration-700">
        <!-- Header Industrial Pro -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-1">
            <div class="flex items-center gap-6">
                <div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center shadow-2xl shadow-primary/20">
                    <span class="material-symbols-rounded text-2xl text-white">campaign</span>
                </div>
                <div>
                    <h2 class="text-3xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">CENTRAL DE <span class="text-primary italic">DIFUSIÓN</span></h2>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <p class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]">Gestión de Comunicación Masiva Nivel 4</p>
                    </div>
                </div>
            </div>
            
            <Button variant="primary" icon="add" @click="openCreate" class="!rounded-xl shadow-lg shadow-primary/20 !text-[10px] font-black uppercase px-8">Crear Difusión</Button>
        </div>

        <!-- Filtros Tácticos (Tabs) -->
        <div class="flex items-center gap-2 p-1.5 bg-surface-container-low dark:bg-white/[0.03] rounded-2xl border border-outline-variant/10 dark:border-white/5 w-fit">
            <button 
                v-for="tab in ['active', 'scheduled', 'expired', 'all']" 
                :key="tab"
                @click="activeTab = tab"
                class="px-6 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest transition-all"
                :class="activeTab === tab ? 'bg-primary text-white shadow-xl shadow-primary/20' : 'text-on-surface-variant/50 dark:text-white/30 hover:text-primary'"
            >
                {{ tab === 'active' ? 'En el Aire' : tab === 'scheduled' ? 'Próximos' : tab === 'expired' ? 'Historial' : 'Todos' }}
            </button>
        </div>

        <!-- Grid de Anuncios Pro -->
        <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-8">
            <div 
                v-for="ann in filteredAnnouncements" 
                :key="ann.id"
                class="group bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[2.5rem] p-8 shadow-xl hover:shadow-2xl transition-all relative overflow-hidden"
                :class="{'opacity-75 grayscale-[0.5]': !ann.is_active || new Date(ann.expires_at) < new Date()}"
            >
                <!-- Linea de Decoración Superior por Tipo -->
                <div class="absolute top-0 left-0 right-0 h-1.5 transition-all group-hover:h-2" :class="getTypeConfig(ann.type).bg.replace('/10', '')"></div>

                <div class="flex justify-between items-start mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center border transition-all group-hover:scale-110" :class="[getTypeConfig(ann.type).bg, getTypeConfig(ann.type).border]">
                             <span class="material-symbols-rounded text-lg" :class="getTypeConfig(ann.type).color">{{ getTypeConfig(ann.type).icon }}</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-[8px] font-black uppercase tracking-[0.2em]" :class="getStatusInfo(ann).color">{{ getStatusInfo(ann).label }}</span>
                                <span class="w-1 h-1 rounded-full" :class="getStatusInfo(ann).dot"></span>
                            </div>
                            <h3 class="text-lg font-black text-on-surface dark:text-white uppercase tracking-tighter leading-none mt-1 group-hover:text-primary transition-colors">{{ ann.title }}</h3>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <Tooltip text="Editar">
                            <button @click="editAnnouncement(ann)" class="w-8 h-8 rounded-lg bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/20 hover:bg-primary hover:text-white transition-all">
                                <span class="material-symbols-rounded text-base">edit</span>
                            </button>
                        </Tooltip>
                        <Tooltip text="Eliminar">
                            <button @click="deleteAnnouncement(ann.id)" class="w-8 h-8 rounded-lg bg-surface-container dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-center text-on-surface-variant dark:text-white/20 hover:bg-error hover:text-white transition-all">
                                <span class="material-symbols-rounded text-base">delete</span>
                            </button>
                        </Tooltip>
                    </div>
                </div>

                <p class="text-sm font-medium text-on-surface-variant/70 dark:text-white/50 leading-relaxed mb-8 italic">
                    "{{ ann.message }}"
                </p>

                <div class="flex flex-wrap gap-4 items-center justify-between pt-6 border-t border-outline-variant/5 dark:border-white/5">
                    <div class="flex gap-4">
                        <div class="flex flex-col">
                            <span class="text-[7px] font-black text-on-surface-variant/30 uppercase tracking-widest">Publicado para</span>
                            <Badge variant="neutral" class="!px-3 !py-0.5 !text-[8.5px] !font-black !bg-primary/5 !text-primary !border-primary/10 tracking-widest uppercase mt-1">{{ ann.target_role }}</Badge>
                        </div>
                        <div v-if="ann.expires_at" class="flex flex-col border-l border-outline-variant/10 dark:border-white/10 pl-4">
                            <span class="text-[7px] font-black text-on-surface-variant/30 uppercase tracking-widest">TTL — Ciclo de Vida</span>
                            <span class="text-[9px] font-black text-on-surface dark:text-white/60 uppercase mt-1 tracking-tighter italic">{{ getTimeRemaining(ann.expires_at) }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 bg-surface-container-low dark:bg-white/5 px-4 py-2 rounded-2xl border border-outline-variant/10 dark:border-white/5 shadow-inner">
                        <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${ann.user_id}`" class="w-6 h-6 rounded-lg bg-primary/10" />
                        <span class="text-[9px] font-black text-on-surface dark:text-white/40 uppercase tracking-widest">Autoría Core</span>
                    </div>
                </div>

                <!-- Efecto visual de progreso de vida si es activo -->
                <div v-if="getStatusInfo(ann).label === 'ACTIVO' && ann.expires_at" class="absolute bottom-0 left-0 h-0.5 bg-emerald-500/20" style="width: 100%">
                    <div class="h-full bg-emerald-500 transition-all duration-1000" :style="{ width: '65%' }"></div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="filteredAnnouncements.length === 0" class="col-span-full py-32 text-center opacity-30 flex flex-col items-center gap-6">
                <div class="w-24 h-24 rounded-[2.5rem] bg-surface-container dark:bg-white/5 flex items-center justify-center">
                    <span class="material-symbols-rounded text-5xl">notifications_paused</span>
                </div>
                <div>
                    <p class="text-sm font-black uppercase tracking-[0.3em]">Silencio en la Red</p>
                    <p class="text-[10px] font-bold uppercase tracking-widest mt-2">No hay anuncios en la categoría: <span class="text-primary">{{ activeTab.toUpperCase() }}</span></p>
                </div>
            </div>
        </div>

        <!-- Modal de Creación / Edición Forense -->
        <Modal :show="showCreateModal" max-width="xl" @close="showCreateModal = false">
            <div class="p-10 bg-[#0b0e14] text-white">
                <header class="flex items-start justify-between border-b border-white/5 pb-8 mb-10">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-[1.8rem] flex items-center justify-center text-white bg-primary shadow-2xl rotate-3">
                            <span class="material-symbols-rounded text-3xl">{{ isEditing ? 'edit_notifications' : 'add_alert' }}</span>
                        </div>
                        <div>
                            <h3 class="text-3xl font-black tracking-tighter uppercase leading-none italic">{{ isEditing ? 'Ajustar' : 'Nueva' }} <span class="text-primary font-black">Difusión</span></h3>
                            <p class="text-[10px] font-bold text-white/40 uppercase tracking-[0.3em] mt-2 italic">Configuración de parámetros de comunicación masiva</p>
                        </div>
                    </div>
                    <button @click="showCreateModal = false" class="text-white/20 hover:text-white transition-colors">
                        <span class="material-symbols-rounded text-3xl">close</span>
                    </button>
                </header>

                <form @submit.prevent="submit" class="space-y-8">
                    <Input 
                        v-model="form.title" 
                        label="Encabezado del Anuncio" 
                        placeholder="Ej: ACTUALIZACIÓN DE KERNEL 4.0..." 
                        required 
                        class="!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
                    />
                    
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-white/30 uppercase tracking-[0.3em] pl-2">Cuerpo del Mensaje (Markdown soportado)</label>
                        <textarea 
                            v-model="form.message" 
                            rows="4" 
                            placeholder="Escribe el mensaje aquí..."
                            class="w-full bg-white/5 border-2 border-white/10 rounded-3xl p-6 text-sm font-medium focus:ring-4 focus:ring-primary/10 outline-none resize-none text-white transition-all focus:border-primary/50"
                        ></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <Select 
                            v-model="form.type" 
                            label="Nivel de Prioridad" 
                            :options="[
                                { value: 'info', label: 'INFORMATIVO' },
                                { value: 'warning', label: 'ADVERTENCIA' },
                                { value: 'danger', label: 'CRÍTICO' }
                            ]" 
                            icon="bolt"
                            class="!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
                        />
                        <Select 
                            v-model="form.target_role" 
                            label="Segmentación" 
                            :options="[
                                { value: 'all', label: 'TODOS LOS USUARIOS' },
                                { value: 'admin', label: 'SOLO ADMINISTRADORES' },
                                { value: 'owner', label: 'SOLO PROPIETARIOS' }
                            ]" 
                            icon="hub"
                            class="!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
                        />
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <Input 
                            v-model="form.starts_at" 
                            type="datetime-local" 
                            label="Ventana de Inicio" 
                            class="!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
                        />
                        <Input 
                            v-model="form.expires_at" 
                            type="datetime-local" 
                            label="Fecha de Expiración" 
                            class="!bg-white/5 !border-white/10 !text-white !h-14 !rounded-2xl"
                        />
                    </div>

                    <div class="flex items-center gap-4 pt-6">
                        <button type="button" @click="showCreateModal = false" class="flex-1 py-4 bg-white/5 text-white/40 font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl hover:text-white transition-all">
                            ABORTAR
                        </button>
                        <button type="submit" :disabled="form.processing" class="flex-[2] py-4 bg-primary text-white font-black text-[11px] uppercase tracking-[0.3em] rounded-2xl shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-95 transition-all">
                            {{ isEditing ? 'ACTUALIZAR DIFUSIÓN' : 'LANZAR ANUNCIO AHORA' }}
                        </button>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Diálogo de Eliminación -->
        <ConfirmModal 
            :show="confirmDelete.show"
            title="Borrar Difusión"
            message="¿Estás seguro de que deseas eliminar este anuncio? Esta acción cortará la conexión de este mensaje con todos los usuarios inmediatamente."
            confirm-label="ELIMINAR AHORA"
            variant="error"
            :loading="form.processing"
            @confirm="executeDelete"
            @cancel="confirmDelete.show = false"
        />
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
.group:hover .font-variation-settings-fill {
    font-variation-settings: 'FILL' 1;
}
</style>
