<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Accordion from '@/Components/UI/Accordion.vue';
import { useToast } from '@/Composables/useToast';
import { ref } from 'vue';

defineOptions({ layout: OwnerLayout });

const props = defineProps<{
    pqrs: Array<any>;
    unidades: Array<any>;
}>();

const toast = useToast();
const showCreateModal = ref(false);

const form = useForm({
    unidad_id: props.unidades[0]?.id || '',
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
            toast.add('PQRS radicada exitosamente', 'success');
        },
    });
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

const getTipoLabel = (tipo: string) => tipo.replace('_', ' ').toUpperCase();
</script>

<template>
    <Head title="Mis PQRS — NEXO-PRO" />

    <div class="min-h-screen">
        <div class="space-y-8 pb-20">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black text-primary tracking-tighter uppercase">Mis Solicitudes</h2>
                    <p class="text-on-surface-variant/60 text-sm font-medium mt-1">Radica y haz seguimiento a tus peticiones, quejas y reclamos.</p>
                </div>
                <Button variant="primary" size="lg" icon="add_comment" @click="showCreateModal = true">Nueva PQRS</Button>
            </div>

            <!-- Listado de PQRS con Accordion -->
            <div v-if="pqrs.length > 0" class="space-y-4">
                <Card 
                    v-for="item in pqrs" 
                    :key="item.id"
                    class="!p-0 overflow-hidden"
                >
                    <Accordion :items="[{
                        title: `#${String(item.id).padStart(5, '0')} - ${item.asunto}`,
                        content: ''
                    }]">
                        <template #title-0>
                            <div class="flex flex-1 items-center justify-between pr-4">
                                <div class="flex flex-col text-left">
                                    <span class="text-xs font-black text-primary uppercase tracking-tighter">#{{ String(item.id).padStart(5, '0') }} · {{ item.asunto }}</span>
                                    <span class="text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest">{{ new Date(item.created_at).toLocaleDateString() }}</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <Badge :variant="getStatusVariant(item.estado)">{{ item.estado }}</Badge>
                                </div>
                            </div>
                        </template>

                        <template #content-0>
                            <div class="p-6 space-y-6 bg-surface-container-low/30 rounded-2xl mt-2 border border-outline-variant/10">
                                <!-- Detalle del Mensaje -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <div class="space-y-2">
                                        <p class="text-[10px] font-black text-primary uppercase tracking-widest">Detalle de la solicitud:</p>
                                        <p class="text-sm font-medium text-on-surface leading-relaxed">{{ item.mensaje }}</p>
                                        <div class="flex gap-2 mt-4">
                                            <Badge variant="neutral" class="!text-[8px]">{{ getTipoLabel(item.tipo) }}</Badge>
                                            <Badge variant="outline" class="!text-[8px]">TORRE {{ item.unidad.torre }} - {{ item.unidad.nombre }}</Badge>
                                        </div>
                                    </div>

                                    <!-- Respuesta de Administración -->
                                    <div class="space-y-2 border-l border-outline-variant/10 pl-8">
                                        <p class="text-[10px] font-black text-secondary uppercase tracking-widest">Respuesta de Administración:</p>
                                        <div v-if="item.respuesta" class="space-y-3">
                                            <p class="text-sm font-medium text-on-surface leading-relaxed italic">"{{ item.respuesta }}"</p>
                                            <p class="text-[9px] text-on-surface-variant/40 font-bold uppercase">Respondido el {{ new Date(item.fecha_respuesta).toLocaleDateString() }}</p>
                                        </div>
                                        <div v-else class="flex flex-col items-center py-4 opacity-30">
                                            <span class="material-symbols-outlined text-3xl">pending_actions</span>
                                            <p class="text-[10px] font-black uppercase tracking-widest mt-2">En espera de respuesta</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Acciones -->
                                <div class="pt-4 border-t border-outline-variant/10 flex justify-end gap-3">
                                    <Button variant="outline" size="sm" icon="picture_as_pdf" @click="window.open(route('pqrs.download', item.id))">Descargar PDF</Button>
                                    <Button v-if="item.estado === 'cerrado'" variant="ghost" size="sm" icon="replay" class="!text-danger" @click="router.patch(route('pqrs.update', item.id))">Reabrir caso</Button>
                                </div>
                            </div>
                        </template>
                    </Accordion>
                </Card>
            </div>

            <!-- Estado Vacío -->
            <div v-else class="py-32 text-center opacity-30 flex flex-col items-center gap-6">
                <div class="w-24 h-24 rounded-full bg-surface-container-high flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl">forum</span>
                </div>
                <div class="space-y-2">
                    <p class="text-xl font-black uppercase tracking-tighter text-primary">Sin radicados</p>
                    <p class="text-xs font-medium uppercase tracking-widest">No has registrado ninguna solicitud todavía</p>
                </div>
                <Button variant="outline" class="mt-4" icon="add_circle" @click="showCreateModal = true">Crear mi primera PQRS</Button>
            </div>
        </div>

        <!-- Modal para Crear PQRS -->
        <Modal 
            v-if="showCreateModal" 
            @close="showCreateModal = false"
            title="Radicar Nueva PQRS"
        >
            <form @submit.prevent="submitPqrs" class="space-y-6">
                <Select 
                    v-model="form.unidad_id"
                    label="¿Sobre qué unidad reportas?"
                    :options="unidades.map(u => ({ value: u.id, label: `Torre ${u.torre} - ${u.nombre}` }))"
                    icon="apartment"
                    required
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Select 
                        v-model="form.tipo"
                        label="Tipo de Solicitud"
                        :options="[
                            { value: 'peticion', label: 'PETICIÓN' },
                            { value: 'queja', label: 'QUEJA' },
                            { value: 'reclamo', label: 'RECLAMO' },
                            { value: 'sugerencia', label: 'SUGERENCIA' },
                            { value: 'reporte_danos', label: 'REPORTE DAÑOS' }
                        ]"
                        icon="category"
                    />
                    <Select 
                        v-model="form.prioridad"
                        label="Prioridad"
                        :options="[
                            { value: 'baja', label: 'BAJA' },
                            { value: 'media', label: 'MEDIA' },
                            { value: 'alta', label: 'ALTA' }
                        ]"
                        icon="priority_high"
                    />
                </div>

                <Input 
                    v-model="form.asunto"
                    label="Asunto"
                    placeholder="Breve título de tu solicitud..."
                    icon="title"
                    required
                />

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest ml-1">Mensaje Detallado</label>
                    <textarea 
                        v-model="form.mensaje" 
                        rows="5" 
                        class="w-full bg-surface-container-low border-2 border-outline-variant/10 rounded-[1.5rem] p-6 text-sm font-medium text-on-surface focus:ring-4 focus:ring-primary/10 outline-none transition-all"
                        placeholder="Explica detalladamente tu situación..."
                        required
                    ></textarea>
                </div>

                <div class="flex flex-col gap-3 pt-4">
                    <Button 
                        type="submit" 
                        variant="primary" 
                        size="lg" 
                        class="w-full"
                        :disabled="form.processing"
                        icon="send"
                    >
                        {{ form.processing ? 'Radicando...' : 'Radicar Solicitud' }}
                    </Button>
                    <Button variant="ghost" class="w-full" @click="showCreateModal = false">Cancelar</Button>
                </div>
            </form>
        </Modal>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 24;
}
</style>
