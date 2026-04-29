<script setup lang="ts">
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Select from '@/Components/UI/Select.vue';
import Modal from '@/Components/UI/Modal.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    copropiedad: any;
    availableAdmins: Array<any>;
    currentAdmins: Array<any>;
}>();

const form = useForm({
    old_admin_id: '',
    new_admin_id: '',
});

const showModal = ref(false);
const modalTitle = ref('');
const modalMessage = ref('');

const startTransfer = (admin: any) => {
    form.old_admin_id = admin.id;
    modalTitle.value = 'Transferencia de Mando';
    modalMessage.value = `¿Estás seguro de que deseas transferir la administración de "${props.copropiedad.nombre}" a un nuevo administrador? El administrador actual perderá el acceso a este conjunto, pero conservará el acceso a sus otros proyectos.`;
    showModal.value = true;
};

const executeTransfer = () => {
    if (!form.new_admin_id || form.processing) return;
    
    form.post(route('superadmin.properties.transfer', props.copropiedad.id), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        }
    });
};

const cancelTransfer = () => {
    showModal.value = false;
    form.reset();
};
</script>

<template>
    <Head :title="`Gestionar ${copropiedad.nombre} — NEXO-PRO`" />

    <div class="max-w-4xl mx-auto space-y-10 pb-20">
        <!-- Breadcrumbs & Header -->
        <div class="flex flex-col gap-4">
            <Link :href="route('superadmin.licenses.index')" class="text-[10px] font-black text-primary/40 flex items-center gap-2 hover:text-primary transition-colors group">
                <span class="material-symbols-outlined text-sm group-hover:-translate-x-1 transition-transform">arrow_back</span>
                VOLVER A LICENCIAS
            </Link>
            
            <div class="flex items-center justify-between gap-6">
                <div>
                    <h2 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none">{{ copropiedad.nombre }}</h2>
                    <p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-[0.2em] mt-3">Gestión de mando y continuidad de datos</p>
                </div>
                <Badge variant="success" class="h-fit">CONJUNTO ACTIVO</Badge>
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Columna Izquierda: Información Actual -->
            <div class="md:col-span-2 space-y-8">
                <!-- Administradores Actuales -->
                <Card title="Administración Actual" subtitle="Personas con acceso de mando actual" icon="shield_person">
                    <div class="divide-y divide-surface-container">
                        <div v-if="currentAdmins.length === 0" class="py-10 text-center">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant/20">person_off</span>
                            <p class="text-[10px] font-black text-on-surface-variant/40 uppercase mt-4">No hay administradores asignados</p>
                        </div>
                        <div 
                            v-else
                            v-for="admin in currentAdmins" 
                            :key="admin.id"
                            class="py-4 first:pt-0 last:pb-0 flex items-center justify-between"
                        >
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-primary/5 flex items-center justify-center text-primary border border-primary/10">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-primary uppercase leading-none">{{ admin.name }}</p>
                                    <p class="text-[10px] text-on-surface-variant/40 font-bold uppercase mt-1 tracking-tight">{{ admin.email }}</p>
                                </div>
                            </div>
                            <Button 
                                variant="outline" 
                                size="sm" 
                                icon="swap_horiz" 
                                class="!px-4"
                                @click="startTransfer(admin)"
                            >TRASPASA MANDO</Button>
                        </div>
                    </div>
                </Card>

                <!-- Resumen de Datos -->
                <Card title="Patrimonio Digital" subtitle="Información persistente vinculada al conjunto" icon="database">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter">Unidades</p>
                            <p class="text-2xl font-black text-primary leading-none">{{ copropiedad.unidades_count || 0 }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter">Residentes</p>
                            <p class="text-2xl font-black text-primary leading-none">{{ copropiedad.users_count || 0 }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter">Plan</p>
                            <p class="text-xs font-black text-primary uppercase bg-primary/5 px-2 py-1 rounded inline-block">{{ copropiedad.plan }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-tighter">NIT</p>
                            <p class="text-xs font-black text-primary leading-tight">{{ copropiedad.nit }}</p>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Columna Derecha: Tips de Seguridad / Acciones Rápidas -->
            <div class="space-y-6">
                <div class="bg-primary/5 border border-primary/10 rounded-3xl p-8 space-y-6">
                    <span class="material-symbols-outlined text-primary text-3xl">info</span>
                    <div class="space-y-4">
                        <h4 class="text-sm font-black text-primary uppercase leading-tight">Garantía de Continuidad</h4>
                        <p class="text-[11px] font-bold text-primary/70 leading-relaxed italic">
                            "Al realizar una transferencia, todos los datos históricos (Pagos, PQRS, Actas) permanecen vinculados al NIT del conjunto residencial, asegurando que la nueva administración reciba la copropiedad con su memoria intacta."
                        </p>
                    </div>
                </div>

                <Card class="bg-surface-container-low">
                    <div class="space-y-4 text-center">
                        <span class="material-symbols-outlined text-warning text-3xl">warning</span>
                        <p class="text-[10px] font-black text-on-surface-variant/60 uppercase px-4 leading-relaxed">
                            Las transferencias son irreversibles sin autorización de un Super Administrador.
                        </p>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Modal de Transferencia -->
        <Modal 
            :show="showModal" 
            :title="modalTitle" 
            @close="cancelTransfer"
        >
            <div class="space-y-6">
                <p class="text-sm text-on-surface-variant leading-relaxed">
                    {{ modalMessage }}
                </p>

                <div class="p-6 bg-surface-container rounded-2xl border border-surface-container-high space-y-4">
                    <p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-[0.1em]">Selecciona al Nuevo Administrador</p>
                    <Select 
                        v-model="form.new_admin_id"
                        :options="availableAdmins.map(a => ({ value: a.id, label: `${a.name} (${a.email})` }))"
                        placeholder="BUSCAR ADMINISTRADOR..."
                    />
                </div>

                <div class="flex flex-col gap-3">
                    <Button 
                        variant="primary" 
                        size="lg" 
                        class="w-full !rounded-2xl h-14"
                        :disabled="!form.new_admin_id || form.processing"
                        @click="executeTransfer"
                    >
                        <span v-if="form.processing">PROCESANDO...</span>
                        <span v-else>EJECUTAR TRASPASO SEGURO</span>
                    </Button>
                    <Button 
                        variant="ghost" 
                        class="w-full"
                        @click="cancelTransfer"
                    >
                        CANCELAR
                    </Button>
                </div>
            </div>
        </Modal>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 20, 'wght' 600;
}
</style>
