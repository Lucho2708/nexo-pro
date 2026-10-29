<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
import PaymentModule from '@/Components/Payments/PaymentModule.vue';
import PaymentMethodModal from '@/Components/Payments/PaymentMethodModal.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: OwnerLayout });

const props = defineProps<{
    unidades: Array<any>;
    total_saldo: number;
    transacciones: Array<any>;
    features: {
        payments_enabled: boolean;
        gateways: Record<string, any>;
    };
}>();

const toast = useToast();
const payModule = ref<any>(null);
const showMethodModal = ref(false);
const activePayUnit = ref<any>(props.unidades?.[0] || null);
const selectedTx = ref<any>(null);

const filterType = ref<'all' | 'abono' | 'cargo'>('all');
const searchQuery = ref('');

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(value || 0);
};

const formatDate = (dateString: string) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('es-CO', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
};

const filteredTransactions = computed(() => {
    if (!props.transacciones) return [];
    return props.transacciones.filter(tx => {
        const matchesType = filterType.value === 'all' || tx.tipo === filterType.value;
        const matchesSearch = !searchQuery.value.trim() || 
            tx.concepto?.nombre?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            tx.referencia?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            tx.unidad?.nombre?.includes(searchQuery.value);
        return matchesType && matchesSearch;
    });
});

const handlePayment = (unidad?: any) => {
    if (unidad) {
        activePayUnit.value = unidad;
    } else {
        activePayUnit.value = props.unidades?.find(u => (u.saldo_actual || 0) > 0) || props.unidades?.[0];
    }

    const amountToPay = activePayUnit.value ? (activePayUnit.value.saldo_actual || props.total_saldo) : props.total_saldo;
    if (amountToPay <= 0) {
        toast.add('Esta unidad se encuentra al día y no tiene saldos pendientes.', 'success');
        return;
    }
    showMethodModal.value = true;
};

const processPayment = (gatewayKey: string) => {
    showMethodModal.value = false;
    if (gatewayKey === 'wompi' && payModule.value) {
        payModule.value.pay();
    } else if (gatewayKey === 'aval') {
        const avalUrl = props.features.gateways.aval?.url;
        if (avalUrl) window.open(avalUrl, '_blank');
        else toast.add('La URL de Aval Pay Center no está configurada.', 'error');
    } else {
        toast.add('Instrucciones para pago manual: Consigna a la cuenta corriente del conjunto.', 'info');
    }
};

const printReceipt = () => {
    window.print();
};
</script>

<template>
    <Head title="Mis Pagos y Cartera — NEXO-PRO" />

    <!-- Payment Module Binding -->
    <PaymentModule 
        v-if="unidades && unidades.length > 0"
        ref="payModule"
        :unidad-id="activePayUnit?.id || unidades[0]?.id"
        :amount="activePayUnit ? (activePayUnit.saldo_actual || total_saldo) : total_saldo"
        :email="$page.props.auth.user.email"
        :full-name="$page.props.auth.user.name"
    />

    <PaymentMethodModal
        :show="showMethodModal"
        :gateways="features.gateways"
        :amount="activePayUnit ? (activePayUnit.saldo_actual || total_saldo) : total_saldo"
        @close="showMethodModal = false"
        @select="processPayment"
    />

    <!-- Receipt Preview Modal -->
    <Modal
        v-if="selectedTx"
        :show="!!selectedTx"
        title="Comprobante de Movimiento"
        @close="selectedTx = null"
    >
        <div class="space-y-6 text-left">
            <div class="p-5 rounded-2xl bg-surface-container flex items-center justify-between border border-outline-variant/10">
                <div>
                    <span class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/60">Monto Transacción</span>
                    <p class="text-2xl font-black tracking-tight" :class="selectedTx.tipo === 'abono' ? 'text-emerald-600' : 'text-primary'">
                        {{ formatCurrency(selectedTx.monto) }}
                    </p>
                </div>
                <Badge :variant="selectedTx.tipo === 'abono' ? 'success' : 'neutral'" class="!uppercase !text-[9px]">
                    {{ selectedTx.tipo === 'abono' ? 'Abono Registrado' : 'Cargo de Administración' }}
                </Badge>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex justify-between py-2 border-b border-outline-variant/10">
                    <span class="text-on-surface-variant/70 font-medium">Concepto:</span>
                    <span class="font-bold text-on-surface">{{ selectedTx.concepto?.nombre || 'Cuota Ordinaria' }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-outline-variant/10">
                    <span class="text-on-surface-variant/70 font-medium">Inmueble:</span>
                    <span class="font-bold text-on-surface">{{ selectedTx.unidad?.torre }} {{ selectedTx.unidad?.nombre }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-outline-variant/10">
                    <span class="text-on-surface-variant/70 font-medium">Fecha:</span>
                    <span class="font-bold text-on-surface">{{ formatDate(selectedTx.fecha) }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-outline-variant/10">
                    <span class="text-on-surface-variant/70 font-medium">Referencia de Seguimiento:</span>
                    <span class="font-mono text-on-surface font-bold">{{ selectedTx.referencia || 'NEXO-' + selectedTx.id.slice(0, 8) }}</span>
                </div>
            </div>

            <div class="pt-4 border-t border-outline-variant/10 flex justify-between gap-3">
                <Button variant="outline" size="sm" icon="print" @click="printReceipt">Imprimir</Button>
                <Button variant="primary" size="sm" @click="selectedTx = null">Cerrar</Button>
            </div>
        </div>
    </Modal>

    <!-- Main Container -->
    <div class="space-y-6 md:space-y-8 max-w-[1400px] mx-auto">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-1">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-on-surface tracking-tight leading-tight">
                    Estado de Cuenta y Pagos
                </h2>
                <p class="text-xs text-on-surface-variant/60 font-medium mt-0.5">Control de expensas, recibos digitales y pagos en línea</p>
            </div>

            <div class="flex items-center gap-3 bg-surface-container-lowest dark:bg-surface-container-low p-2.5 rounded-2xl border border-outline-variant/20 shadow-sm">
                <div class="px-3 text-right">
                    <p class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/60">Saldo Consolidado</p>
                    <p class="text-lg sm:text-xl font-black tracking-tight" :class="total_saldo > 0 ? 'text-primary' : 'text-emerald-600'">
                        {{ formatCurrency(total_saldo) }}
                    </p>
                </div>
                <Button 
                    v-if="features.payments_enabled && total_saldo > 0" 
                    variant="primary" 
                    size="sm" 
                    icon="payments"
                    class="text-xs font-black uppercase tracking-wider"
                    @click="handlePayment()"
                >
                    Pagar Saldo
                </Button>
                <div v-else class="px-3 py-1.5 rounded-xl bg-emerald-500/10 text-emerald-600 text-xs font-bold flex items-center gap-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Al Día</span>
                </div>
            </div>
        </div>

        <!-- 2 Column Layout: Units Cards & History -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
            
            <!-- Left Column: Units Breakdown Cards -->
            <div class="lg:col-span-4 space-y-4">
                <div class="flex items-center justify-between px-1">
                    <h3 class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest">Inmuebles a tu nombre ({{ unidades?.length || 0 }})</h3>
                </div>
                
                <div class="space-y-3">
                    <div 
                        v-for="unidad in unidades" 
                        :key="unidad.id" 
                        class="p-5 rounded-2xl bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 shadow-sm transition-all hover:shadow-md border-l-4"
                        :class="(unidad.saldo_actual || 0) > 0 ? 'border-l-error' : 'border-l-emerald-500'"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                    <span class="material-symbols-rounded text-xl">apartment</span>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-on-surface">{{ unidad.torre }} - {{ unidad.nombre }}</p>
                                    <p class="text-[10px] text-on-surface-variant/60 font-medium">{{ unidad.copropiedad?.nombre || 'Mi Conjunto' }}</p>
                                </div>
                            </div>
                            <Badge :variant="(unidad.saldo_actual || 0) > 0 ? 'danger' : 'success'" class="!text-[9px] uppercase tracking-wider">
                                {{ (unidad.saldo_actual || 0) > 0 ? 'Con Deuda' : 'Paz y Salvo' }}
                            </Badge>
                        </div>

                        <div class="mt-4 pt-3 border-t border-outline-variant/10 flex items-center justify-between">
                            <div>
                                <span class="text-[9px] font-bold text-on-surface-variant/50 uppercase tracking-widest">Saldo Pendiente</span>
                                <p class="text-base font-black tracking-tight" :class="(unidad.saldo_actual || 0) > 0 ? 'text-error' : 'text-emerald-600'">
                                    {{ formatCurrency(unidad.saldo_actual) }}
                                </p>
                            </div>
                            <Button 
                                v-if="(unidad.saldo_actual || 0) > 0"
                                variant="primary" 
                                size="sm" 
                                icon="credit_card"
                                class="!py-1.5 !px-3 text-xs font-bold"
                                @click="handlePayment(unidad)"
                            >
                                Pagar
                            </Button>
                            <span v-else class="text-[10px] font-bold text-emerald-600 flex items-center gap-1">
                                <span class="material-symbols-rounded text-sm">verified</span> Certificado
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Full History with Search and Filters -->
            <div class="lg:col-span-8 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-1">
                    <h3 class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest">Historial de Transacciones ({{ filteredTransactions.length }})</h3>
                    
                    <!-- Type Filter Pills -->
                    <div class="flex items-center gap-1.5 bg-surface-container-high/40 p-1 rounded-xl border border-outline-variant/10 self-start sm:self-auto">
                        <button
                            v-for="filter in [
                                { key: 'all', label: 'Todas' },
                                { key: 'abono', label: 'Abonos' },
                                { key: 'cargo', label: 'Cargos' }
                            ]"
                            :key="filter.key"
                            type="button"
                            @click="filterType = filter.key as any"
                            class="px-2.5 py-1 rounded-lg text-xs font-bold transition-all cursor-pointer"
                            :class="filterType === filter.key ? 'bg-primary text-white shadow-xs' : 'text-on-surface-variant hover:text-on-surface'"
                        >
                            {{ filter.label }}
                        </button>
                    </div>
                </div>

                <!-- Search Input -->
                <div class="relative">
                    <span class="material-symbols-rounded absolute left-3.5 top-1/2 -translate-y-1/2 text-on-surface-variant/40 text-lg">search</span>
                    <input 
                        v-model="searchQuery"
                        type="text"
                        placeholder="Buscar por concepto, referencia o inmueble..."
                        class="w-full bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/30 rounded-2xl pl-10 pr-4 py-2.5 text-xs font-medium text-on-surface placeholder:text-on-surface-variant/40 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                    />
                </div>

                <!-- Transactions Feed -->
                <div class="rounded-[2rem] bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 shadow-sm overflow-hidden">
                    <div class="divide-y divide-outline-variant/10">
                        <div 
                            v-for="tx in filteredTransactions" 
                            :key="tx.id"
                            @click="selectedTx = tx"
                            class="p-4 sm:p-5 flex items-center justify-between gap-3 hover:bg-surface-container-high/40 transition-colors cursor-pointer group"
                        >
                            <div class="flex items-center gap-3 min-w-0">
                                <div 
                                    class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 border border-outline-variant/10 group-hover:scale-105 transition-transform"
                                    :class="tx.tipo === 'abono' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-primary/10 text-primary'"
                                >
                                    <span class="material-symbols-rounded text-lg">
                                        {{ tx.tipo === 'abono' ? 'arrow_downward' : 'arrow_upward' }}
                                    </span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs sm:text-sm font-bold text-on-surface group-hover:text-primary transition-colors truncate">
                                        {{ tx.concepto?.nombre || 'Cuota de Administración' }}
                                    </p>
                                    <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                        <span class="text-[10px] text-on-surface-variant/60 font-medium">{{ formatDate(tx.fecha) }}</span>
                                        <span class="text-[10px] text-on-surface-variant/40">•</span>
                                        <span class="text-[10px] font-mono text-on-surface-variant/60">{{ tx.referencia || 'SIN REF' }}</span>
                                        <Badge v-if="tx.unidad" variant="neutral" class="!text-[8px] !px-1.5">
                                            {{ tx.unidad?.torre }} {{ tx.unidad?.nombre }}
                                        </Badge>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right shrink-0">
                                <p class="text-sm sm:text-base font-black tracking-tight" :class="tx.tipo === 'abono' ? 'text-emerald-600 dark:text-emerald-400' : 'text-on-surface'">
                                    {{ tx.tipo === 'abono' ? '+' : '' }}{{ formatCurrency(tx.monto) }}
                                </p>
                                <span class="text-[9px] font-bold uppercase tracking-wider" :class="tx.tipo === 'abono' ? 'text-emerald-600' : 'text-primary'">
                                    {{ tx.tipo === 'abono' ? 'Abonado' : 'Cargado' }}
                                </span>
                            </div>
                        </div>

                        <div v-if="filteredTransactions.length === 0" class="py-16 text-center text-on-surface-variant/50 flex flex-col items-center gap-2">
                            <span class="material-symbols-rounded text-4xl opacity-40">filter_alt_off</span>
                            <p class="text-xs font-bold">No se encontraron transacciones con los criterios seleccionados</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
