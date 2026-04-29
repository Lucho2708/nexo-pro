<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Accordion from '@/Components/UI/Accordion.vue';
import Carousel from '@/Components/UI/Carousel.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';
import PaymentModule from '@/Components/Payments/PaymentModule.vue';
import PaymentMethodModal from '@/Components/Payments/PaymentMethodModal.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: OwnerLayout });

const props = defineProps<{
    unidades: Array<any>;
    total_saldo: number;
    transacciones: Array<any>;
    asambleas: Array<any>;
    user: any;
    features: {
        payments_enabled: boolean;
        gateways: Record<string, any>;
    };
}>();

const toast = useToast();

const isLoaded = ref(false);
const payModule = ref<any>(null);
const showMethodModal = ref(false);

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

onMounted(() => {
    isLoaded.value = true;
});

const announcements = [
    'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&q=80',
    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&q=80'
];

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
        month: 'short',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Mi Cuenta — NEXO-PRO" />
    
    <!-- Payment Logic -->
    <PaymentModule 
        v-if="unidades.length > 0"
        ref="payModule"
        :unidad-id="unidades.find(u => u.saldo_actual > 0)?.id || unidades[0]?.id"
        :amount="total_saldo"
        :email="user.email"
        :full-name="user.name"
    />

    <PaymentMethodModal
        :show="showMethodModal"
        :gateways="features.gateways"
        :amount="total_saldo"
        @close="showMethodModal = false"
        @select="processPayment"
    />

    <!-- Page Header -->
    <div class="flex items-center justify-between mb-8 hidden md:flex animate-fade-in">
        <div>
            <h2 class="text-3xl font-black text-primary tracking-tighter uppercase">Panel de Propietario</h2>
            <p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-widest mt-1">Resumen consolidado de tus inversiones</p>
        </div>
    </div>

    <!-- Skeletons State -->
    <div v-if="!isLoaded" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
        <div class="md:col-span-7 flex flex-col gap-6 w-full">
            <div class="h-28 bg-surface-container-high/50 animate-pulse rounded-[2rem] w-full"></div>
            <div class="h-80 bg-surface-container-high/30 animate-pulse rounded-2xl w-full"></div>
        </div>
        <div class="md:col-span-5 flex flex-col gap-6 w-full">
            <div class="h-48 bg-surface-container-high/30 animate-pulse rounded-2xl w-full"></div>
            <div class="h-96 bg-surface-container-high/30 animate-pulse rounded-2xl w-full"></div>
            <div class="h-32 bg-surface-container-high/50 animate-pulse rounded-[2.5rem] w-full"></div>
        </div>
    </div>

    <!-- Actual Content -->
    <div v-else class="grid grid-cols-1 md:grid-cols-12 gap-6 items-start animate-fade-in-up">
        
        <!-- Left Column: Balance & Actions -->
        <div class="md:col-span-7 flex flex-col gap-6 w-full">
            
            <!-- Premium Coupon Banner -->
            <div class="banner-gradient text-white p-6 rounded-[2rem] flex items-center gap-6 shadow-2xl shadow-primary/20 relative overflow-hidden group">
                <!-- Decorative elements -->
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-white/10 blur-2xl rounded-full group-hover:scale-150 transition-transform duration-1000 ease-out"></div>
                <div class="absolute right-20 -top-10 w-24 h-24 bg-secondary/30 blur-xl rounded-full group-hover:translate-x-4 transition-transform duration-1000"></div>
                
                <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white shrink-0 border border-white/20 shadow-inner">
                    <span class="material-symbols-outlined text-3xl" style="font-variation-settings: 'FILL' 1;">confirmation_number</span>
                </div>
                <div class="flex-1 z-10">
                    <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/70 mb-1">Facturación Vigente</p>
                    <p class="text-lg font-black uppercase tracking-tight text-white drop-shadow-md">Administración {{ new Date().toLocaleString('es-CO', { month: 'long' }) }}</p>
                </div>
                <Button variant="ghost" size="sm" icon="arrow_forward" class="z-10 !bg-white/10 !text-white hover:!bg-white/20 !border-white/20 !rounded-xl backdrop-blur-md transition-all group-hover:translate-x-1"></Button>
            </div>

            <!-- Balance Card -->
            <Card class="relative">
                <template #header>
                    <div class="w-full flex justify-between items-start">
                        <div>
                            <h2 class="text-on-surface-variant/50 text-[10px] font-black uppercase tracking-[0.2em] mb-2">Saldo Total Consolidado</h2>
                            <p class="text-5xl sm:text-6xl font-black text-primary leading-none tracking-tighter">
                                {{ formatCurrency(total_saldo) }}
                                <span class="text-sm sm:text-base font-bold opacity-30 ml-1">COP</span>
                            </p>
                        </div>
                        <Badge :variant="total_saldo > 0 ? 'danger' : 'success'" class="!px-4 !py-1.5 shadow-sm mt-1">
                            {{ total_saldo > 0 ? 'En Mora' : 'Al Día' }}
                        </Badge>
                    </div>
                </template>

                <div class="pt-4">
                    <!-- Multi-Unit breakdown with Accordion -->
                    <div v-if="unidades.length > 1" class="mb-4">
                        <Accordion :items="[{
                            title: 'Desglose por unidad',
                            content: ''
                        }]">
                            <template #content-0>
                                <div class="space-y-2 pt-3">
                                    <div v-for="unidad in unidades" :key="unidad.id" class="flex justify-between items-center pb-3 border-b border-outline-variant/10 last:border-0 last:pb-0">
                                        <span class="flex items-center gap-3 text-xs font-bold text-on-surface">
                                            <span class="material-symbols-outlined text-lg text-primary/40">apartment</span>
                                            {{ unidad.torre }} {{ unidad.nombre }}
                                        </span>
                                        <span class="text-sm font-black text-primary tracking-tight">{{ formatCurrency(unidad.saldo_actual) }}</span>
                                    </div>
                                </div>
                            </template>
                        </Accordion>
                    </div>
                </div>

                <template #footer>
                    <div class="flex flex-col sm:flex-row gap-4 w-full">
                        <Button 
                            v-if="features.payments_enabled"
                            variant="primary"
                            size="lg"
                            icon="payments"
                            class="flex-1 shadow-lg shadow-primary/20 hover:shadow-primary/30"
                            @click="handlePayment"
                        >
                            Pagar ahora
                        </Button>
                        <div v-else class="flex-1 bg-surface-container-high/40 p-4 rounded-xl border border-outline-variant/20 flex items-center justify-center">
                            <p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em] text-center">
                                Pagos en línea no habilitados
                            </p>
                        </div>
                        
                        <Tooltip text="Descargar certificado de paz y salvo (solo si el saldo es $0)">
                            <Button variant="outline" size="lg" icon="download" class="sm:flex-none bg-surface" @click="toast.add('Verificando estado de cuenta...', 'primary')">
                                <span class="hidden sm:inline">Paz y Salvo</span>
                            </Button>
                        </Tooltip>
                    </div>
                </template>
            </Card>
        </div>

        <!-- Right Column: History & Support -->
        <div class="md:col-span-5 flex flex-col gap-6 w-full">
            
            <!-- Active Assemblies Section -->
            <div v-if="asambleas && asambleas.length > 0" class="flex flex-col gap-3">
                <p class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] pl-2 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                    Eventos en Vivo
                </p>
                <div v-for="asamblea in asambleas" :key="asamblea.id" class="bg-[#00173c] text-white p-6 rounded-[2.5rem] shadow-xl relative overflow-hidden group border border-white/5">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                    
                    <div class="flex items-start justify-between mb-4 relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/10">
                            <span class="material-symbols-rounded text-emerald-400">gavel</span>
                        </div>
                        <Badge variant="success" class="!bg-emerald-500 !text-white !border-0 shadow-lg shadow-emerald-500/20">
                            {{ asamblea.status === 'in_progress' ? 'EN VIVO' : 'PROGRAMADA' }}
                        </Badge>
                    </div>

                    <div class="relative z-10">
                        <h3 class="text-lg font-black uppercase tracking-tighter leading-tight mb-2">{{ asamblea.titulo }}</h3>
                        <div class="flex items-center gap-4 mb-6 opacity-60">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-rounded text-xs">calendar_today</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider">{{ formatDate(asamblea.fecha) }}</span>
                            </div>
                        </div>

                        <Link :href="route('asambleas.show', asamblea.id)" target="_blank">
                            <Button variant="primary" size="lg" icon="login" class="w-full !bg-emerald-500 hover:!bg-emerald-600 !text-white !border-0 shadow-lg shadow-emerald-500/30">
                                Ingresar a la Asamblea
                            </Button>
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Community Announcements Carousel -->
            <div class="space-y-3">
                <p class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] pl-2 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                    Anuncios de la Comunidad
                </p>
                <Carousel :images="announcements" class="rounded-2xl overflow-hidden shadow-md" />
            </div>

            <!-- Payment History Card -->
            <Card icon="history" title="Historial de Pagos" subtitle="Últimos movimientos registrados" content-class="!p-0 !pb-0" class="overflow-hidden">
                <template #header>
                    <Button variant="ghost" size="sm" class="!text-secondary hover:bg-secondary/10">Ver todo</Button>
                </template>

                <div class="flex flex-col w-full">
                    <div 
                        v-for="tx in transacciones" 
                        :key="tx.id"
                        class="px-6 py-4 flex items-center gap-4 hover:bg-surface-container-highest/30 transition-colors group cursor-pointer border-b border-outline-variant/5 last:border-0"
                    >
                        <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0 group-hover:bg-secondary group-hover:text-on-secondary transition-all shadow-sm border border-outline-variant/10">
                            <span class="material-symbols-outlined text-secondary group-hover:text-on-secondary text-xl" style="font-variation-settings: 'FILL' 1;">
                                {{ tx.tipo === 'abono' ? 'check_circle' : 'receipt' }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0 flex flex-col justify-center">
                            <div class="flex items-center gap-2 mb-0.5">
                                <p class="text-sm font-black text-on-surface tracking-tight leading-snug truncate">{{ tx.concepto?.nombre || 'Cuota de Administración' }}</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <p class="text-[10px] font-bold text-on-surface-variant/50 uppercase tracking-widest">{{ formatDate(tx.fecha) }}</p>
                                <Badge v-if="unidades.length > 1" variant="neutral" class="!text-[8px] !px-1.5 opacity-70 group-hover:opacity-100 transition-opacity">
                                    {{ tx.unidad?.torre }} {{ tx.unidad?.nombre }}
                                </Badge>
                            </div>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-base font-black text-primary tracking-tighter leading-none mb-1">{{ formatCurrency(tx.monto) }}</p>
                            <p class="text-[9px] text-emerald-600 font-black uppercase tracking-widest">Exitoso</p>
                        </div>
                    </div>

                    <div v-if="transacciones.length === 0" class="py-16 text-center text-on-surface-variant/30 flex flex-col items-center gap-3">
                        <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center mb-2">
                            <span class="material-symbols-outlined text-4xl">history</span>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-[0.2em]">Sin transacciones recientes</p>
                    </div>
                </div>

                <template #footer>
                    <Button variant="ghost" size="sm" class="w-full !text-primary hover:bg-primary/5" icon="description">Estado de cuenta completo</Button>
                </template>
            </Card>

            <!-- Help & Support -->
            <div class="bg-primary-container text-on-primary-container rounded-[2.5rem] p-7 flex items-center gap-5 shadow-xl relative overflow-hidden group border border-primary/10 mt-auto">
                <div class="absolute -left-6 -top-6 w-32 h-32 bg-primary/10 rounded-full group-hover:scale-[1.8] transition-transform duration-700 ease-out"></div>
                <div class="absolute right-0 bottom-0 w-24 h-24 bg-secondary/10 rounded-tl-[100%] group-hover:scale-110 transition-transform duration-700"></div>
                
                <div class="w-14 h-14 bg-primary text-on-primary rounded-2xl flex items-center justify-center shrink-0 z-10 shadow-lg shadow-primary/30 group-hover:rotate-12 transition-transform duration-300">
                    <span class="material-symbols-outlined text-2xl">support_agent</span>
                </div>
                <div class="flex-1 z-10">
                    <h4 class="text-base font-black uppercase tracking-tighter mb-1 leading-none">¿Dudas o Quejas?</h4>
                    <p class="text-[11px] font-medium opacity-80 leading-snug mb-3">Radica un ticket y recibe respuesta en &lt; 24h hábiles.</p>
                    <Link :href="route('pqrs.index')">
                        <Button variant="primary" size="sm" icon="forum" class="!rounded-xl !bg-primary !text-on-primary shadow-md hover:shadow-lg">Ir a PQRS</Button>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 24;
}

/* Custom animations for premium feel */
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fadeIn 0.4s ease-out forwards;
}

.animate-fade-in-up {
    animation: fadeInUp 0.5s ease-out forwards;
}

/* Animated Gradient Banner */
.banner-gradient {
    background: linear-gradient(-45deg, var(--color-primary, #0B4A75), var(--color-secondary, #D97706), var(--color-primary, #0B4A75));
    background-size: 200% 200%;
    animation: gradientShift 10s ease infinite;
}

/* Fallback for CSS vars if missing */
@supports not (background: linear-gradient(-45deg, var(--color-primary), var(--color-secondary), var(--color-primary))) {
    .banner-gradient {
        background: linear-gradient(-45deg, #0f172a, #334155, #0f172a);
    }
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>
