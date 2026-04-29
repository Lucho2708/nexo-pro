<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import Input from '@/Components/UI/Input.vue';
import { useToast } from '@/Composables/useToast';
import { ref } from 'vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    zonas: Array<any>;
}>();

const toast = useToast();
const showModal = ref(false);
const editingZona = ref<any>(null);

const form = useForm({
    nombre: '',
    descripcion: '',
    capacidad_maxima: 10,
    costo: 0,
});

const openCreateModal = () => {
    editingZona.value = null;
    form.reset();
    showModal.value = true;
};

const openEditModal = (zona: any) => {
    editingZona.value = zona;
    form.nombre = zona.nombre;
    form.descripcion = zona.descripcion;
    form.capacidad_maxima = zona.capacidad_maxima;
    form.costo = zona.costo;
    showModal.value = true;
};

const submitForm = () => {
    if (!form.nombre) {
        toast.add('Identificación de activo obligatoria', 'danger');
        return;
    }

    if (editingZona.value) {
        form.patch(route('admin.zonas.update', editingZona.value.id), {
            onSuccess: () => {
                showModal.value = false;
                toast.add('Software de gestión actualizado', 'success');
            },
        });
    } else {
        form.post(route('admin.zonas.store'), {
            onSuccess: () => {
                showModal.value = false;
                toast.add('Nueva integración de activo completada', 'success');
            },
        });
    }
};

const toggleStatus = (id: number) => {
    router.patch(route('admin.zonas.toggle', id), {}, {
        onSuccess: () => toast.add('Estado de disponibilidad modificado', 'primary')
    });
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
    <Head title="Activos — NEXO-PRO" />

    <div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-6 duration-700">
        
        <!-- Tactical Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-secondary rounded-full"></div>
                    <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] italic leading-none">Gestión de Infraestructura Común</p>
                </div>
                <h2 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">Zonas <span class="text-secondary italic">Comunes</span></h2>
                <p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed">Configuración de parámetros operativos y modelos de reserva</p>
            </div>
            <Button variant="primary" size="lg" icon="add_home_work" class="!h-16 !px-10 !rounded-2xl shadow-xl shadow-primary/20 !text-[11px] font-black uppercase italic" @click="openCreateModal">
                Registrar Activo
            </Button>
        </div>

        <!-- Infrastructure Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <Card 
                v-for="zona in zonas" 
                :key="zona.id"
                class="!p-0 !rounded-[3.5rem] overflow-hidden border border-outline-variant/10 dark:border-white/5 transition-all duration-500 hover:-translate-y-2 hover:shadow-3xl hover:shadow-primary/5 dark:bg-[#0b0e14] group"
            >
                <!-- Visual Preview -->
                <div class="relative h-60 bg-surface-container-high dark:bg-white/[0.03] overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent z-10 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    <img 
                        v-if="zona.imagen_path" 
                        :src="zona.imagen_path" 
                        class="w-full h-full object-cover transition-transform duration-[2s] group-hover:scale-110"
                    />
                    <div v-else class="w-full h-full flex flex-col items-center justify-center opacity-20 text-on-surface-variant gap-4 group-hover:bg-primary/5 transition-colors">
                        <span class="material-symbols-rounded text-6xl">apartment</span>
                        <p class="text-[9px] font-black uppercase tracking-[0.3em]">Cámara no disponible</p>
                    </div>
                    
                    <!-- Tactical Badges Overlay -->
                    <div class="absolute top-6 left-6 z-20">
                         <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-2 rounded-xl border border-white/20">
                             <div class="w-2 h-2 rounded-full animate-pulse" :class="zona.activa ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.8)]' : 'bg-error shadow-[0_0_8px_rgba(239,68,68,0.8)]'"></div>
                             <span class="text-[9px] font-black text-white uppercase tracking-widest leading-none">{{ zona.activa ? 'SISTEMA ONLINE' : 'ZONA RESTRINGIDA' }}</span>
                         </div>
                    </div>

                    <div class="absolute bottom-6 right-6 z-20">
                         <Badge variant="neutral" class="!bg-white !text-black !font-black !text-[11px] !px-5 !py-1 !rounded-xl shadow-2xl tracking-tighter italic">
                             {{ zona.costo > 0 ? formatCurrency(zona.costo) : 'USO GRATUITO' }}
                         </Badge>
                    </div>
                </div>

                <!-- Asset Data -->
                <div class="p-10 space-y-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none group-hover:text-primary transition-colors">{{ zona.nombre }}</h3>
                            <div class="flex items-center gap-2 mt-4">
                                <span class="material-symbols-rounded text-base text-on-surface-variant/40 dark:text-white/20">groups</span>
                                <p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest italic">Capacidad Máx: <span class="text-on-surface dark:text-white">{{ zona.capacidad_maxima }} PAX</span></p>
                            </div>
                        </div>
                    </div>

                    <p class="text-sm font-medium text-on-surface-variant/70 dark:text-white/40 leading-relaxed line-clamp-3 italic min-h-[4.5rem]">
                        {{ zona.descripcion || 'Sin protocolos de uso documentados en la base de datos.' }}
                    </p>

                    <div class="pt-8 border-t border-outline-variant/10 dark:border-white/5 flex gap-4">
                        <Button variant="outline" class="flex-1 !h-14 !rounded-2xl !text-[10px] font-black uppercase italic group-hover:bg-primary/5" icon="tune" @click="openEditModal(zona)">Calibrar</Button>
                        <Button 
                            :variant="zona.activa ? 'ghost' : 'primary'" 
                            class="flex-1 !h-14 !rounded-2xl !text-[10px] font-black uppercase italic"
                            :icon="zona.activa ? 'power_settings_new' : 'bolt'"
                            @click="toggleStatus(zona.id)"
                        >
                            {{ zona.activa ? 'Desconectar' : 'Vincular' }}
                        </Button>
                    </div>
                </div>
            </Card>

            <!-- Deployment Placeholder -->
            <button 
                @click="openCreateModal"
                class="relative h-full min-h-[35rem] border-4 border-dashed border-outline-variant/10 dark:border-white/5 rounded-[3.5rem] flex flex-col items-center justify-center p-12 hover:border-primary/40 hover:bg-primary/5 transition-all group overflow-hidden"
            >
                <div class="absolute inset-0 bg-primary opacity-0 group-hover:opacity-[0.02] transition-opacity duration-700"></div>
                <div class="w-20 h-20 rounded-3xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center text-on-surface-variant/40 group-hover:scale-110 group-hover:bg-primary group-hover:text-white transition-all shadow-2xl relative z-10">
                    <span class="material-symbols-rounded text-4xl">add</span>
                </div>
                <p class="text-[11px] font-black text-on-surface-variant/40 uppercase tracking-[0.4em] mt-8 group-hover:text-primary transition-colors relative z-10 italic">Integrar Nuevo Activo</p>
                <div class="mt-4 flex gap-1 relative z-10">
                    <div v-for="i in 3" :key="i" class="w-1 h-1 rounded-full bg-on-surface-variant/20 dark:bg-white/10 group-hover:bg-primary/40 transition-colors"></div>
                </div>
            </button>
        </div>

        <!-- Technical Configuration Modal -->
        <Modal 
            v-if="showModal" 
            @close="showModal = false"
            class="!max-w-xl"
        >
            <template #title>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary">
                        <span class="material-symbols-rounded text-2xl">{{ editingZona ? 'edit_square' : 'add_circle' }}</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">{{ editingZona ? 'Ajustes Técnicos' : 'Registro de Activo' }}</h3>
                        <p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1">Configuración de parámetros operativos</p>
                    </div>
                </div>
            </template>

            <form @submit.prevent="submitForm" class="space-y-8 mt-10">
                <Input 
                    v-model="form.nombre"
                    label="IDENTIFICACIÓN DEL ACTIVO"
                    placeholder="Ej: SALÓN TÁCTICO, FITNESS CENTER..."
                    icon="apartment"
                    required
                    class="!rounded-2xl"
                />

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <Input 
                        v-model="form.capacidad_maxima"
                        label="CAPACIDAD OPERATIVA (PAX)"
                        type="number"
                        icon="groups"
                        required
                        class="!rounded-2xl"
                    />
                    <Input 
                        v-model="form.costo"
                        label="CANON DE USO (COP)"
                        type="number"
                        icon="payments"
                        required
                        class="!rounded-2xl"
                    />
                </div>

                <div class="space-y-3">
                    <label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-2 italic">Protocolos y Descripción</label>
                    <textarea 
                        v-model="form.descripcion" 
                        rows="4" 
                        class="w-full bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-3xl p-6 text-sm font-medium text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all placeholder:text-on-surface-variant/20 italic"
                        placeholder="Define los vectores de uso y normativas locales..."
                    ></textarea>
                </div>

                <div class="flex flex-col gap-4 pt-6 border-t border-outline-variant/10 dark:border-white/5">
                    <Button 
                        type="submit" 
                        variant="primary" 
                        size="lg" 
                        class="w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20"
                        :disabled="form.processing"
                        :icon="editingZona ? 'save' : 'deployment_unit'"
                    >
                        {{ form.processing ? 'PROCESANDO...' : (editingZona ? 'ACTUALIZAR PARÁMETROS' : 'VINCULAR ACTIVO') }}
                    </Button>
                    <Button variant="ghost" class="w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40" @click="showModal = false">Abortar Operación</Button>
                </div>
            </form>
        </Modal>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
