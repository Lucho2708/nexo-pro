<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Dropdown from '@/Components/UI/Dropdown.vue';
import Progress from '@/Components/UI/Progress.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';
import Table from '@/Components/UI/Table.vue';
import { ref, watch, onMounted } from 'vue';
import PaymentModal from '@/Components/Cartera/PaymentModal.vue';
import ImportModal from '@/Components/Cartera/ImportModal.vue';
import Modal from '@/Components/UI/Modal.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    unidades: {
        data: Array<any>;
        current_page: number;
        last_page: number;
        total: number;
        per_page: number;
        [key: string]: any;
    };
    conceptos: Array<any>;
    stats: any;
    filters: any;
    torres: Array<string>;
}>();

const isLoaded = ref(false);

onMounted(() => {
    setTimeout(() => {
        isLoaded.value = true;
    }, 400);
});

const tableColumns = [
    { key: 'unidad', label: 'ACTIVO / LOTE', sortable: true, sortField: 'nombre' },
    { key: 'propietario', label: 'RESPONSABLE LEGAL', sortable: true, sortField: 'propietario_nombre' },
    { key: 'coeficiente', label: 'COEF.', sortable: true, sortField: 'coeficiente' },
    { key: 'estado', label: 'INDICE DE MORA', sortable: true, sortField: 'saldo_actual' },
    { key: 'saldo', label: 'DEUDA CONSOLIDADA', sortable: true, sortField: 'saldo_actual' },
    { key: 'acciones', label: '', sortable: false },
];

const toast = useToast();
const showPaymentModal = ref(false);
const showImportModal = ref(false);
const showBillingModal = ref(false);
const billingAmount = ref(150000);
const selectedUnidad = ref(null);

const openPaymentModal = (unidad = null) => {
    selectedUnidad.value = unidad;
    showPaymentModal.value = true;
};

const triggerBilling = () => {
    router.post(route('cartera.billing.trigger'), {
        monto: billingAmount.value
    }, {
        onSuccess: () => {
            showBillingModal.value = false;
            toast.add('Ciclo de facturación lanzado con éxito', 'success');
        }
    });
};

const getStatusVariant = (saldo: number) => {
    if (saldo <= 0) return 'success';
    if (saldo < 500000) return 'warning';
    return 'error';
};

const getStatusLabel = (saldo: number) => {
    if (saldo <= 0) return 'SISTEMA AL DÍA';
    if (saldo < 500000) return 'RETRASO LEVE';
    return 'RIESGO CRÍTICO';
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
    <Head title="Ingeniería de Cartera — NEXO-PRO" />

    <div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Tactical Header -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 px-1">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-primary rounded-full shadow-[0_0_10px_rgba(var(--primary),0.3)]"></div>
                    <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] italic leading-none">Módulo de Conciliación Financiera</p>
                </div>
                <h2 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">Gestión de <span class="text-primary italic">Cartera</span></h2>
                <p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic">Monitor técnico de recaudos, saldos pendientes y cierres administrativos</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 bg-white dark:bg-white/[0.02] p-2 rounded-[2rem] border border-outline-variant/10 shadow-xl">
                 <Button variant="ghost" size="md" icon="upload_file" class="!rounded-2xl italic font-black uppercase !text-[10px]" @click="showImportModal = true">Importación</Button>
                 <Button variant="outline" size="md" icon="auto_awesome" class="!rounded-2xl italic font-black uppercase !text-[10px]" @click="showBillingModal = true">Cierre de Mes</Button>
                 <Button variant="primary" size="md" icon="add_card" class="!rounded-2xl italic font-black uppercase !text-[10px] shadow-lg shadow-primary/20" @click="openPaymentModal()">Registrar Recaudo</Button>
            </div>
        </div>

        <!-- Financial KPI Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <Card class="!p-8 !rounded-[2.5rem] border border-outline-variant/5 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] group hover:scale-[1.02] transition-all">
                <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-6 italic leading-none">Recaudo Estimado (Mensual)</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-4xl font-black text-primary tracking-tighter leading-none italic">$142.5M</h3>
                    <div class="px-3 py-1.5 rounded-xl text-[9px] font-black flex items-center gap-1 bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                        <span class="material-symbols-rounded text-sm">trending_up</span>
                        +12%
                    </div>
                </div>
                <div class="mt-8 flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    <p class="text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest italic leading-none">Meta: 95% OPERATIVA</p>
                </div>
            </Card>

            <Card class="!p-8 !rounded-[2.5rem] border border-outline-variant/5 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] group hover:scale-[1.02] transition-all">
                <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mb-6 italic leading-none">Cartera en Riesgo</p>
                <div class="flex items-center justify-between">
                    <h3 class="text-4xl font-black text-error tracking-tighter leading-none italic">{{ formatCurrency(stats.cartera_vencida) }}</h3>
                    <div class="w-12 h-12 bg-error/10 rounded-2xl flex items-center justify-center text-error border border-error/20">
                        <span class="material-symbols-rounded text-2xl">warning</span>
                    </div>
                </div>
                <div class="mt-8 flex items-center gap-2">
                    <div class="w-1.5 h-1.5 rounded-full bg-error"></div>
                    <p class="text-[9px] text-on-surface-variant/40 dark:text-white/20 font-black uppercase tracking-widest italic leading-none">{{ stats.unidades_mora }} UNIDADES EN ESTADO CRÍTICO</p>
                </div>
            </Card>

            <Card class="!p-8 !rounded-[2.5rem] border border-outline-variant/5 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] lg:col-span-2">
                 <div class="flex items-center justify-between mb-6">
                     <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] italic leading-none">Indice de Solvencia General</p>
                     <span class="text-2xl font-black text-primary tracking-tighter italic">82.4%</span>
                 </div>
                 <div class="mt-4 py-3">
                     <Progress :percentage="82.4" variant="gradient" height="h-3" class="!rounded-full shadow-inner" />
                 </div>
                 <div class="flex justify-between mt-6">
                    <p class="text-[9px] font-black text-on-surface-variant/30 dark:text-white/10 uppercase tracking-widest italic">Cierre anterior: 78.2%</p>
                    <p class="text-[9px] font-black text-primary uppercase tracking-widest italic flex items-center gap-1">
                        DESEMPEÑO ÓPTIMO <span class="material-symbols-rounded text-xs">bolt</span>
                    </p>
                 </div>
            </Card>
        </div>



        <!-- Main Cartera Table -->
        <Card title="Monitor de Saldos" subtitle="Desglose técnico pro unidad" icon="receipt_long" class="!p-0 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14] overflow-hidden">
            <template #header>
                <div class="p-10 flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Monitor de <span class="text-primary italic">Saldos</span></h3>
                        <p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Registro cronológico de obligaciones por unidad</p>
                    </div>
                </div>
            </template>

            <Table :columns="tableColumns" :data="unidades.data" class="border-t border-outline-variant/5 dark:border-white/5">
                <template #cell-unidad="{ row }">
                    <div class="flex items-center gap-4 py-2">
                        <div class="w-12 h-12 rounded-2xl bg-primary/5 dark:bg-primary/10 flex items-center justify-center border border-primary/20 shadow-inner">
                            <span class="material-symbols-rounded text-2xl text-primary">analytics</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-black text-primary tracking-tighter italic leading-none">TORRE {{ row.torre || 'N/A' }}</span>
                            <span class="text-[11px] font-black text-on-surface dark:text-white uppercase italic tracking-widest mt-1.5">{{ row.nombre }}</span>
                        </div>
                    </div>
                </template>

                <template #cell-propietario="{ row }">
                    <div class="flex flex-col py-1">
                        <span class="text-[12px] font-black text-on-surface dark:text-white uppercase italic tracking-tighter leading-tight mb-1">
                            {{ row.propietario_nombre || 'RESPONSABLE NO VINCULADO' }}
                        </span>
                        <span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em]">
                            {{ row.propietario_identificacion || 'DOCUMENTO PENDIENTE' }}
                        </span>
                    </div>
                </template>

                <template #cell-coeficiente="{ row }">
                    <div class="px-4 py-2 bg-surface-container dark:bg-white/5 rounded-xl border border-outline-variant/5 w-fit">
                        <span class="text-[11px] font-black text-primary tracking-widest italic">{{ row.coeficiente }}%</span>
                    </div>
                </template>

                <template #cell-estado="{ row }">
                    <div class="flex items-center gap-3">
                        <Badge :variant="getStatusVariant(row.saldo_actual)" class="!rounded-xl !px-5 !py-1 !text-[9px] font-black uppercase tracking-widest border-2">
                            {{ getStatusLabel(row.saldo_actual) }}
                        </Badge>
                    </div>
                </template>

                <template #cell-saldo="{ row }">
                    <div class="flex flex-col items-end pr-8">
                        <span :class="['text-xl font-black tracking-tighter italic tabular-nums leading-none', row.saldo_actual > 0 ? 'text-error' : 'text-emerald-500']">
                            {{ formatCurrency(row.saldo_actual) }}
                        </span>
                        <span class="text-[8px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-2">DIFERENCIAL DE CIERRE</span>
                    </div>
                </template>

                <template #cell-acciones="{ row }">
                    <div class="flex justify-end pr-8 gap-2">
                        <Tooltip text="Registrar Pago">
                            <Button variant="ghost" icon="add_card" class="!w-10 !h-10 !p-0 !rounded-xl !text-primary hover:bg-primary/5" @click="openPaymentModal(row)" />
                        </Tooltip>
                        <Tooltip text="Hoja de Vida">
                            <Button variant="ghost" icon="visibility" class="!w-10 !h-10 !p-0 !rounded-xl hover:bg-surface-variant/10" @click="router.get(route('admin.units.show', row.id))" />
                        </Tooltip>
                        <Dropdown 
                            label="" 
                            icon="more_vert" 
                            variant="ghost"
                            class="!p-0 !w-10 !h-10 !rounded-xl border border-outline-variant/10"
                            :items="[
                                { label: 'Descargar PDF', icon: 'picture_as_pdf', action: () => window.open(route('cartera.statement.download', row.id)) },
                                { label: 'Editar Responsable', icon: 'manage_accounts', action: () => {} },
                            ]"
                        />
                    </div>
                </template>
            </Table>
            
            <!-- Technical Summary -->
            <div class="p-10 bg-surface-container-low/30 dark:bg-white/[0.02] flex items-center justify-between border-t border-outline-variant/5 dark:border-white/5">
                <p class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/40 uppercase tracking-[0.3em] italic">REGISTRO TOTAL: {{ unidades.total }} ACTIVOS MONITOREADOS</p>
                <!-- Paginación gestionada internamente por el componente Table -->
            </div>
        </Card>
    </div>

    <!-- Modals (Industrial Themed) -->
    <PaymentModal v-if="showPaymentModal" :unidad="selectedUnidad" :conceptos="conceptos" @close="showPaymentModal = false" />
    <ImportModal v-if="showImportModal" @close="showImportModal = false" />

    <Modal v-if="showBillingModal" @close="showBillingModal = false" :title="'DISPATCHER DE FACTURACIÓN'" class="!max-w-xl">
        <div class="space-y-10 mt-6">
            <div class="bg-primary/5 p-8 rounded-[2.5rem] border border-primary/20 flex gap-5">
                <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-rounded">info</span>
                </div>
                <p class="text-sm font-medium text-on-surface-variant dark:text-white/60 leading-relaxed italic pr-4">
                    Este proceso ejecutará el cierre de ciclo contable, generará obligaciones para todas las unidades y recalculará intereses de mora según el coeficiente configurado.
                </p>
            </div>

            <div class="space-y-4 px-2">
                <Input 
                    v-model="billingAmount"
                    label="VALOR CANON ADMINISTRATIVO"
                    type="number"
                    icon="payments"
                    placeholder="0.00"
                    class="!rounded-2xl !h-16 font-black italic text-lg"
                />
                <p class="text-[9px] font-black text-on-surface-variant/40 uppercase tracking-[0.3em] ml-4">ESTE VALOR SERÁ EL VECTOR BASE DEL COBRO MENSUAL</p>
            </div>

            <div class="flex flex-col gap-4 pt-6 border-t border-outline-variant/10 dark:border-white/5">
                <Button variant="primary" size="lg" class="w-full !h-16 !rounded-2xl !text-[11px] font-black uppercase italic shadow-2xl shadow-primary/20" @click="triggerBilling" icon="cloud_sync">
                    Lanzar Facturación Global
                </Button>
                <Button variant="ghost" class="w-full !text-[9px] font-black uppercase tracking-widest text-on-surface-variant/40" @click="showBillingModal = false">
                    Abortar Proceso
                </Button>
            </div>
        </div>
    </Modal>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
