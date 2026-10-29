<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import { useToast } from '@/Composables/useToast';
import { ref, computed } from 'vue';

defineOptions({ layout: OwnerLayout });

const props = defineProps<{
    pqrs: Array<any>;
    unidades: Array<any>;
}>();

const toast = useToast();
const showCreateModal = ref(false);
const activeStatusFilter = ref<string>('all');
const activeTypeFilter = ref<string>('all');
const searchQuery = ref('');
const expandedIds = ref<Record<string, boolean>>({});

const form = useForm({
    unidad_id: props.unidades?.[0]?.id || '',
    tipo: 'peticion',
    asunto: '',
    mensaje: '',
    prioridad: 'media',
    adjuntos: [] as File[],
});

const submitPqrs = () => {
    form.post(route('pqrs.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
            toast.add('¡Tu solicitud ha sido radicada exitosamente ante la administración!', 'success');
        },
        onError: () => {
            toast.add('Verifica los campos obligatorios del formulario.', 'error');
        }
    });
};

const toggleExpand = (id: string | number) => {
    expandedIds.value[id] = !expandedIds.value[id];
};

const getStatusVariant = (status: string): any => {
    switch (status) {
        case 'abierto': return 'primary';
        case 'en_proceso': return 'warning';
        case 'cerrado': return 'neutral';
        case 'reabierto': return 'danger';
        default: return 'neutral';
    }
};

const getPriorityVariant = (priority: string): any => {
    switch (priority) {
        case 'alta': return 'danger';
        case 'media': return 'warning';
        case 'baja': return 'neutral';
        default: return 'neutral';
    }
};

const getTipoLabel = (tipo: string) => {
    if (!tipo) return 'SOLICITUD';
    return tipo.replace('_', ' ').toUpperCase();
};

const getTipoIcon = (tipo: string) => {
    switch (tipo) {
        case 'queja': return 'report_problem';
        case 'reclamo': return 'warning';
        case 'sugerencia': return 'lightbulb';
        case 'reporte_danos': return 'build';
        case 'felicitaciones': return 'sentiment_very_satisfied';
        default: return 'assignment';
    }
};

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('es-CO', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
};

const filteredPqrs = computed(() => {
    if (!props.pqrs) return [];
    return props.pqrs.filter(item => {
        const matchesStatus = activeStatusFilter.value === 'all' || item.estado === activeStatusFilter.value;
        const matchesType = activeTypeFilter.value === 'all' || item.tipo === activeTypeFilter.value;
        const matchesSearch = !searchQuery.value.trim() || 
            item.asunto?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            String(item.id).includes(searchQuery.value) ||
            item.mensaje?.toLowerCase().includes(searchQuery.value.toLowerCase());
        return matchesStatus && matchesType && matchesSearch;
    });
});

const reopenTicket = (id: string | number) => {
    router.patch(route('pqrs.update', id), {}, {
        onSuccess: () => {
            toast.add('El radicado ha sido reabierto para revisión.', 'info');
        }
    });
};

const downloadPdf = (id: string | number) => {
    if (typeof window !== 'undefined') {
        window.open(route('pqrs.download', id), '_blank');
    }
};
</script>

<template>
    <Head title="Mis PQRS — NEXO-PRO" />

    <!-- Main Container -->
    <div class="max-w-5xl mx-auto w-full pb-20 space-y-6 md:space-y-8">
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-1">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-on-surface tracking-tight leading-tight">
                    Peticiones, Quejas y Reclamos (PQRS)
                </h2>
                <p class="text-xs text-on-surface-variant/60 font-medium mt-0.5">
                    Canal formal de comunicación y radicación con la administración
                </p>
            </div>
            <Button 
                variant="primary" 
                size="lg" 
                icon="add_comment" 
                class="text-xs font-black uppercase tracking-wider !rounded-xl !py-3 shadow-md self-start sm:self-auto"
                @click="showCreateModal = true"
            >
                Radicar Solicitud
            </Button>
        </div>

        <!-- Filter and Search Bar -->
        <div class="space-y-3 bg-surface-container-lowest dark:bg-surface-container-low p-4 sm:p-5 rounded-[2rem] border border-outline-variant/20 shadow-xs">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-3">
                <!-- Status Pills -->
                <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 md:pb-0">
                    <button 
                        v-for="st in [
                            { key: 'all', label: 'Todas' },
                            { key: 'abierto', label: 'Abiertas' },
                            { key: 'en_proceso', label: 'En Trámite' },
                            { key: 'cerrado', label: 'Cerradas' }
                        ]"
                        :key="st.key"
                        type="button"
                        @click="activeStatusFilter = st.key"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all shrink-0 cursor-pointer"
                        :class="activeStatusFilter === st.key ? 'bg-primary text-white shadow-xs' : 'bg-surface-container text-on-surface-variant hover:text-on-surface'"
                    >
                        {{ st.label }}
                    </button>
                </div>

                <!-- Search Input -->
                <div class="relative w-full md:w-72">
                    <span class="material-symbols-rounded absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant/40 text-base">search</span>
                    <input 
                        v-model="searchQuery"
                        type="text"
                        placeholder="Buscar radicado o asunto..."
                        class="w-full bg-surface-container border border-outline-variant/20 rounded-xl pl-9 pr-3 py-2 text-xs font-medium text-on-surface placeholder:text-on-surface-variant/40 focus:outline-none focus:ring-2 focus:ring-primary/20"
                    />
                </div>
            </div>

            <!-- Categories horizontal chips -->
            <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pt-2 border-t border-outline-variant/10 text-xs">
                <span class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/50 mr-2 shrink-0">Categoría:</span>
                <button
                    v-for="cat in [
                        { key: 'all', label: 'Todas' },
                        { key: 'peticion', label: 'Peticiones' },
                        { key: 'queja', label: 'Quejas' },
                        { key: 'reclamo', label: 'Reclamos' },
                        { key: 'reporte_danos', label: 'Reporte Daños' },
                        { key: 'sugerencia', label: 'Sugerencias' }
                    ]"
                    :key="cat.key"
                    type="button"
                    @click="activeTypeFilter = cat.key"
                    class="px-2.5 py-1 rounded-lg font-bold transition-all shrink-0 cursor-pointer"
                    :class="activeTypeFilter === cat.key ? 'bg-primary/10 text-primary border border-primary/20' : 'text-on-surface-variant/70 hover:text-on-surface'"
                >
                    {{ cat.label }}
                </button>
            </div>
        </div>

        <!-- PQRS Cards List -->
        <div v-if="filteredPqrs.length > 0" class="space-y-4">
            <div 
                v-for="item in filteredPqrs" 
                :key="item.id"
                class="rounded-[2rem] bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 shadow-xs overflow-hidden transition-all hover:shadow-md"
            >
                <!-- Card Header (Always visible) -->
                <div 
                    @click="toggleExpand(item.id)"
                    class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 cursor-pointer hover:bg-surface-container-high/30 transition-colors"
                >
                    <div class="flex items-start gap-4 min-w-0">
                        <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                            <span class="material-symbols-rounded text-2xl">
                                {{ getTipoIcon(item.tipo) }}
                            </span>
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="text-xs font-black font-mono text-primary">#{{ String(item.id).padStart(5, '0') }}</span>
                                <Badge variant="neutral" class="!text-[8px] !uppercase">
                                    {{ getTipoLabel(item.tipo) }}
                                </Badge>
                                <Badge :variant="getPriorityVariant(item.prioridad)" class="!text-[8px] !uppercase">
                                    Prioridad {{ item.prioridad }}
                                </Badge>
                                <Badge v-if="item.unidad" variant="neutral" class="!text-[8px]">
                                    {{ item.unidad.torre }} - {{ item.unidad.nombre }}
                                </Badge>
                            </div>
                            <h3 class="text-sm sm:text-base font-black text-on-surface tracking-tight leading-snug truncate">
                                {{ item.asunto }}
                            </h3>
                            <p class="text-[10px] text-on-surface-variant/60 font-medium mt-0.5">
                                Radicado el {{ formatDate(item.created_at) }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 pt-2 sm:pt-0 border-t sm:border-0 border-outline-variant/10">
                        <Badge :variant="getStatusVariant(item.estado)" class="!text-[9px] uppercase tracking-wider">
                            {{ item.estado }}
                        </Badge>
                        <span class="material-symbols-rounded text-on-surface-variant/40 transition-transform duration-200"
                            :class="{ 'rotate-180': expandedIds[item.id] }"
                        >
                            keyboard_arrow_down
                        </span>
                    </div>
                </div>

                <!-- Expanded Conversation Panel -->
                <div v-if="expandedIds[item.id]" class="px-5 pb-6 sm:px-6 pt-2 border-t border-outline-variant/10 space-y-5 bg-surface-container-low/20">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-3">
                        
                        <!-- Left: Resident's Request -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-sm text-primary">chat</span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-primary">Detalle de tu Solicitud</span>
                            </div>
                            <div class="p-4 rounded-2xl bg-surface-container border border-outline-variant/15 text-xs text-on-surface leading-relaxed whitespace-pre-line">
                                {{ item.mensaje }}
                            </div>
                        </div>

                        <!-- Right: Administration Response -->
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-rounded text-sm text-secondary">admin_panel_settings</span>
                                <span class="text-[10px] font-black uppercase tracking-wider text-secondary">Respuesta de la Administración</span>
                            </div>
                            <div v-if="item.respuesta" class="p-4 rounded-2xl bg-secondary/5 border border-secondary/20 space-y-2">
                                <p class="text-xs text-on-surface leading-relaxed italic">"{{ item.respuesta }}"</p>
                                <p class="text-[9px] text-on-surface-variant/60 font-bold uppercase">
                                    Respondido formalmente el {{ formatDate(item.fecha_respuesta) }}
                                </p>
                            </div>
                            <div v-else class="p-6 rounded-2xl bg-surface-container border border-outline-variant/10 flex flex-col items-center justify-center text-center opacity-60">
                                <span class="material-symbols-rounded text-3xl">hourglass_empty</span>
                                <p class="text-xs font-bold text-on-surface mt-1">En trámite de respuesta</p>
                                <p class="text-[10px] text-on-surface-variant/70">La administración responderá dentro de los términos normativos.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="pt-4 border-t border-outline-variant/10 flex items-center justify-between flex-wrap gap-3">
                        <span class="text-[10px] text-on-surface-variant/50 font-bold">
                            Radicado oficial NEXO-PRO con validez probatoria Ley 675
                        </span>
                        <div class="flex items-center gap-2">
                            <Button 
                                variant="outline" 
                                size="sm" 
                                icon="picture_as_pdf" 
                                class="text-xs font-bold"
                                @click="downloadPdf(item.id)"
                            >
                                Descargar Copia PDF
                            </Button>
                            <Button 
                                v-if="item.estado === 'cerrado'" 
                                variant="ghost" 
                                size="sm" 
                                icon="replay" 
                                class="!text-error text-xs font-bold" 
                                @click="reopenTicket(item.id)"
                            >
                                Reabrir Caso
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="p-16 text-center rounded-[2.5rem] bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 flex flex-col items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                <span class="material-symbols-rounded text-3xl">inbox</span>
            </div>
            <div class="space-y-1">
                <h4 class="text-base font-black text-on-surface">No hay solicitudes radicadas</h4>
                <p class="text-xs text-on-surface-variant/70 max-w-sm">
                    No encontramos radicados con los filtros aplicados. Puedes crear una nueva solicitud en cualquier momento.
                </p>
            </div>
            <Button variant="primary" size="sm" icon="add_comment" @click="showCreateModal = true">
                Crear Mi Primera Solicitud
            </Button>
        </div>

        <!-- Modal para Crear PQRS -->
        <Modal 
            v-if="showCreateModal" 
            @close="showCreateModal = false"
            title="Radicar Nueva Solicitud (PQRS)"
        >
            <form @submit.prevent="submitPqrs" class="space-y-5 text-left">
                <!-- Unit Selector -->
                <Select 
                    v-model="form.unidad_id"
                    label="Inmueble sobre el que reportas"
                    :options="unidades.map(u => ({ 
                        value: u.id, 
                        label: (u.torre ? (/^torre\b/i.test(u.torre.trim()) ? u.torre.trim() : 'Torre ' + u.torre.trim()) + ' - ' : '') + u.nombre 
                    }))"
                    icon="apartment"
                    required
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <Select 
                        v-model="form.tipo"
                        label="Tipo de Trámite"
                        :options="[
                            { value: 'peticion', label: 'Petición Formal' },
                            { value: 'queja', label: 'Queja por Convivencia' },
                            { value: 'reclamo', label: 'Reclamo por Servicio' },
                            { value: 'reporte_danos', label: 'Reporte de Daños Físicos' },
                            { value: 'sugerencia', label: 'Sugerencia de Mejora' },
                            { value: 'felicitaciones', label: 'Felicitaciones' }
                        ]"
                        icon="category"
                    />

                    <Select 
                        v-model="form.prioridad"
                        label="Nivel de Prioridad"
                        :options="[
                            { value: 'baja', label: 'Baja (Informativo)' },
                            { value: 'media', label: 'Media (Ordinario)' },
                            { value: 'alta', label: 'Alta (Urgente / Daño en curso)' }
                        ]"
                        icon="priority_high"
                    />
                </div>

                <Input 
                    v-model="form.asunto"
                    label="Título o Asunto Resumido"
                    placeholder="Ej. Filtración en cubierta / Solicitud de copia de reglamento"
                    icon="title"
                    required
                    :error="form.errors.asunto"
                />

                <div class="space-y-1.5">
                    <div class="flex justify-between items-center px-1">
                        <label class="text-[10px] font-black text-on-surface-variant/70 uppercase tracking-widest">
                            Descripción Detallada
                        </label>
                        <span class="text-[9px] font-bold text-on-surface-variant/50">
                            {{ form.mensaje.length }} caracteres
                        </span>
                    </div>
                    <textarea 
                        v-model="form.mensaje" 
                        rows="5" 
                        class="w-full bg-surface-container border border-outline-variant/30 rounded-2xl p-4 text-xs font-medium text-on-surface focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-on-surface-variant/40"
                        :class="form.errors.mensaje ? 'border-error text-error' : ''"
                        placeholder="Describe con claridad los hechos, fechas, ubicación y tu solicitud concreta..."
                        required
                    ></textarea>
                    <p v-if="form.errors.mensaje" class="text-xs text-error font-bold ml-1">{{ form.errors.mensaje }}</p>
                </div>

                <div class="pt-4 border-t border-outline-variant/10 flex flex-col sm:flex-row justify-end gap-3">
                    <Button variant="ghost" class="w-full sm:w-auto" @click="showCreateModal = false">Cancelar</Button>
                    <Button 
                        type="submit" 
                        variant="primary" 
                        size="md" 
                        class="w-full sm:w-auto font-bold"
                        :disabled="form.processing"
                        icon="send"
                    >
                        {{ form.processing ? 'Radicando en sistema...' : 'Radicar Solicitud' }}
                    </Button>
                </div>
            </form>
        </Modal>
    </div>
</template>
