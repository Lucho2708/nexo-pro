<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
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

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(value);
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-CO', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
};

const handlePayment = () => {
    if (props.total_saldo <= 0) {
        toast.add('No tienes saldos pendientes para pagar.', 'success');
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
        toast.add('Instrucciones para pago manual: Consigna a la cuenta de la copropiedad.', 'info');
    }
};
</script>

<template>
    <Head title="Mis Pagos — NEXO-PRO" />

    <PaymentModule 
        v-if="unidades.length > 0"
        ref="payModule"
        :unidad-id="unidades[0]?.id"
        :amount="total_saldo"
        :email="$page.props.auth.user.email"
        :full-name="$page.props.auth.user.name"
    />

    <PaymentMethodModal
        :show="showMethodModal"
        :gateways="features.gateways"
        :amount="total_saldo"
        @close="showMethodModal = false"
        @select="processPayment"
    />

    <div class="space-y-8 animate-fade-in">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h2 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none">Mi Estado de Cuenta</h2>
                <p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-widest mt-2">Consulta tus saldos y pagos realizados</p>
            </div>
            <div class="flex items-center gap-3 bg-surface-container-high/50 p-2 rounded-2xl border border-outline-variant/10">
                <div class="px-4 py-2 text-right">
                    <p class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/50">Saldo Total</p>
                    <p class="text-xl font-black text-primary tracking-tighter">{{ formatCurrency(total_saldo) }}</p>
                </div>
                <Button v-if="features.payments_enabled && total_saldo > 0" variant="primary" size="sm" icon="payments" @click="handlePayment">Pagar</Button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- Left Column: Units Breakdown -->
            <div class="lg:col-span-4 space-y-6">
                <h3 class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] px-2">Unidades Vinculadas</h3>
                
                <div v-for="unidad in unidades" :key="unidad.id" class="group">
                    <Card class="!p-0 overflow-hidden hover:premium-elevated transition-all border-l-4" :class="unidad.saldo_actual > 0 ? 'border-l-error' : 'border-l-success'">
                        <div class="p-5 flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                                    <span class="material-symbols-outlined">apartment</span>
                                </div>
                                <div>
                                    <p class="text-xs font-black text-on-surface tracking-tight">{{ unidad.torre }} - {{ unidad.nombre }}</p>
                                    <p class="text-[10px] text-on-surface-variant/60 font-bold">{{ unidad.copropiedad?.nombre }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-primary">{{ formatCurrency(unidad.saldo_actual) }}</p>
                                <Badge :variant="unidad.saldo_actual > 0 ? 'danger' : 'success'" class="!text-[8px] !px-1.5 mt-0.5">
                                    {{ unidad.saldo_actual > 0 ? 'Mora' : 'Al día' }}
                                </Badge>
                            </div>
                        </div>
                    </Card>
                </div>
            </div>

            <!-- Right Column: Full History -->
            <div class="lg:col-span-8 space-y-6">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em]">Historial Completo de Transacciones</h3>
                    <div class="flex gap-2">
                         <Button variant="ghost" size="sm" icon="filter_list" class="!px-2"></Button>
                         <Button variant="ghost" size="sm" icon="download" class="!px-2"></Button>
                    </div>
                </div>

                <Card class="!p-0 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-surface-container-high/30 border-b border-outline-variant/10">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Fecha</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Concepto</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Unidad</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Referencia</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60 text-right">Monto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-outline-variant/5">
                                <tr v-for="tx in transacciones" :key="tx.id" class="hover:bg-primary/5 transition-colors group">
                                    <td class="px-6 py-5">
                                        <p class="text-xs font-bold text-on-surface">{{ formatDate(tx.fecha) }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <span class="material-symbols-outlined text-sm" :class="tx.tipo === 'abono' ? 'text-success' : 'text-error'">
                                                {{ tx.tipo === 'abono' ? 'arrow_downward' : 'arrow_upward' }}
                                            </span>
                                            <p class="text-xs font-black text-on-surface tracking-tight">{{ tx.concepto?.nombre || 'Administración' }}</p>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-[10px] font-bold text-on-surface-variant">{{ tx.unidad?.torre }} {{ tx.unidad?.nombre }}</p>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-[10px] font-mono text-on-surface-variant/50">{{ tx.referencia || 'N/A' }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <p class="text-sm font-black text-primary tracking-tighter">{{ formatCurrency(tx.monto) }}</p>
                                    </td>
                                </tr>
                                <tr v-if="transacciones.length === 0">
                                    <td colspan="5" class="px-6 py-20 text-center">
                                        <div class="flex flex-col items-center gap-4 opacity-30">
                                            <span class="material-symbols-outlined text-5xl">receipt_long</span>
                                            <p class="text-xs font-black uppercase tracking-[0.2em]">No se registran pagos en el sistema</p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </Card>
            </div>
        </div>
    </div>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
