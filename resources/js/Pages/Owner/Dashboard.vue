<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Carousel from '@/Components/UI/Carousel.vue';
import PaymentModule from '@/Components/Payments/PaymentModule.vue';
import PaymentMethodModal from '@/Components/Payments/PaymentMethodModal.vue';
import UnitSpecsCard from '@/Components/Owner/UnitSpecsCard.vue';
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
        two_factor_enabled: boolean;
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
    'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&q=80',
    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&q=80'
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
    <Head title="Panel de Propietario — NEXO-PRO" />
    
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

    <!-- Main Content Container -->
    <div class="max-w-[1400px] mx-auto space-y-6 md:space-y-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between animate-fade-in px-2 md:px-0">
            <div>
                <h2 class="text-2xl md:text-3xl font-black text-primary tracking-tighter uppercase leading-none">Panel de Propietario</h2>
                <p class="text-[10px] md:text-xs text-on-surface-variant/60 font-bold uppercase tracking-widest mt-2 md:mt-1">Resumen consolidado de tus inversiones</p>
            </div>
            <div class="flex items-center gap-4 hidden md:flex">
                <button class="w-10 h-10 rounded-full bg-surface-container-lowest shadow-sm border border-outline-variant/30 flex items-center justify-center text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <div class="h-10 w-10 rounded-full bg-primary text-white flex items-center justify-center font-bold shadow-md uppercase tracking-tighter">
                    {{ user.name.split(' ').map(n => n[0]).join('').substring(0, 2) }}
                </div>
            </div>
        </div>

        <!-- Skeletons State -->
        <div v-if="!isLoaded" class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
            <div class="lg:col-span-8 flex flex-col gap-6 md:gap-8 w-full">
                <div class="aspect-video lg:h-80 bg-surface-container-high/50 animate-pulse rounded-[2rem] w-full"></div>
                <div class="h-32 bg-surface-container-high/30 animate-pulse rounded-[2rem] w-full"></div>
                <div class="h-80 bg-surface-container-high/30 animate-pulse rounded-[2rem] w-full"></div>
            </div>
            <div class="lg:col-span-4 flex flex-col gap-6 md:gap-8 w-full">
                <div class="h-32 bg-surface-container-high/30 animate-pulse rounded-[2rem] w-full"></div>
                <div class="h-80 bg-surface-container-high/30 animate-pulse rounded-[2rem] w-full"></div>
            </div>
        </div>

        <!-- Actual Content -->
        <div v-else class="animate-fade-in-up">
            <!-- Grid 12 Columns -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
                
                <!-- Left Side: Announcements, Specs & History -->
                <div class="lg:col-span-8 flex flex-col gap-6 md:gap-8 order-1">
                    
                    <!-- Hero Announcements -->
                    <div class="space-y-3">
                        <p class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] pl-2 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                            Comunicados del Conjunto
                        </p>
                        <!-- Usamos aspect-video para que la imagen no se recorte tanto y h-auto en móvil -->
                        <div class="rounded-[2rem] md:rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white dark:border-white/5 bg-surface-container">
                            <Carousel :images="announcements" class="w-full aspect-[16/9] md:aspect-[21/9] lg:h-[350px]" />
                        </div>
                    </div>

                    <!-- Ficha Técnica del Inmueble -->
                    <UnitSpecsCard :unidades="unidades" :two-factor-enabled="features.two_factor_enabled" />

                    <!-- Payment History Card -->
                    <Card icon="history" title="Historial de Pagos" subtitle="Últimos movimientos registrados" content-class="!p-0 !pb-0" class="overflow-hidden !rounded-[2rem]">
                        <template #header>
                            <Button variant="ghost" size="sm" class="!text-secondary hover:bg-secondary/10">Ver todo</Button>
                        </template>

                        <div class="flex flex-col w-full">
                            <div 
                                v-for="tx in transacciones" 
                                :key="tx.id"
                                class="px-4 md:px-6 py-4 flex items-center gap-4 hover:bg-surface-container-highest/30 transition-colors group cursor-pointer border-b border-outline-variant/5 last:border-0"
                            >
                                <div class="w-10 h-10 rounded-xl bg-surface-container-high flex items-center justify-center shrink-0 group-hover:bg-secondary group-hover:text-white transition-all shadow-sm border border-outline-variant/10">
                                    <span class="material-symbols-outlined text-secondary group-hover:text-white text-xl" style="font-variation-settings: 'FILL' 1;">
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

                            <div v-if="transacciones.length === 0" class="py-12 text-center text-on-surface-variant/30 flex flex-col items-center gap-3">
                                <p class="text-[10px] font-black uppercase tracking-[0.2em]">Sin transacciones recientes</p>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Right Side: Financial & Events -->
                <!-- Order-2 asegura que en móvil vaya después de la izquierda si se desea, o puedes ponerlo order-last -->
                <div class="lg:col-span-4 flex flex-col gap-6 md:gap-8 order-2 lg:order-last">
                    
                    <!-- Premium Coupon Banner -->
                    <div class="banner-gradient text-white p-6 md:p-8 rounded-[2rem] flex flex-col gap-4 shadow-2xl shadow-primary/20 relative overflow-hidden group min-h-[140px] justify-center">
                        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 blur-2xl rounded-full group-hover:scale-150 transition-transform duration-1000 ease-out"></div>
                        <div class="flex items-center gap-4 relative z-10">
                            <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-white shrink-0 border border-white/20">
                                <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">confirmation_number</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-white/70 mb-0.5">Facturación Vigente</p>
                                <p class="text-base font-black uppercase tracking-tight text-white italic">Abril 2024</p>
                            </div>
                        </div>
                    </div>

                    <!-- Balance Card -->
                    <Card class="relative !rounded-[2rem] !p-6 md:!p-8">
                        <template #header>
                            <div class="w-full space-y-4 mb-2">
                                <div class="flex justify-between items-start">
                                    <div class="space-y-1">
                                        <h2 class="text-on-surface-variant/50 text-[9px] font-black uppercase tracking-[0.2em]">Saldo Consolidado</h2>
                                        <p class="text-4xl md:text-5xl font-black text-primary leading-none tracking-tighter">
                                            {{ formatCurrency(total_saldo) }}
                                        </p>
                                    </div>
                                    <Badge :variant="total_saldo > 0 ? 'danger' : 'success'" class="!px-3 !py-1 shadow-sm !text-[10px] font-bold uppercase tracking-wider">
                                        {{ total_saldo > 0 ? 'En Mora' : 'Al Día' }}
                                    </Badge>
                                </div>
                            </div>
                        </template>

                        <template #footer>
                            <div class="flex flex-col gap-3 w-full border-t border-outline-variant/5 pt-6">
                                <Button 
                                    v-if="features.payments_enabled"
                                    variant="primary"
                                    size="lg"
                                    icon="payments"
                                    class="w-full !py-4 shadow-xl shadow-primary/20 hover:shadow-primary/30 !rounded-xl"
                                    @click="handlePayment"
                                >
                                    Pagar ahora
                                </Button>
                                <Button variant="ghost" size="sm" icon="description" class="w-full !text-[10px] font-black uppercase tracking-widest text-primary/60 hover:text-primary transition-colors">
                                    Ver estado de cuenta
                                </Button>
                            </div>
                        </template>
                    </Card>

                    <!-- Active Assemblies Section -->
                    <div v-if="asambleas && asambleas.length > 0" class="flex flex-col gap-3 mt-2">
                        <p class="text-[10px] font-black text-on-surface-variant/50 uppercase tracking-[0.2em] pl-2 flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                            Próximos Eventos
                        </p>
                        <div v-for="asamblea in asambleas" :key="asamblea.id" class="bg-[#00173c] text-white p-6 rounded-[2.5rem] shadow-xl relative overflow-hidden group border border-white/5">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                            <div class="flex items-start justify-between mb-4 relative z-10">
                                <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/10">
                                    <span class="material-symbols-rounded text-emerald-400 text-lg">gavel</span>
                                </div>
                                <Badge variant="success" class="!bg-emerald-500 !text-white !border-0 !px-2 !py-0.5 text-[9px] shadow-lg shadow-emerald-500/20 uppercase tracking-widest">
                                    {{ asamblea.status === 'in_progress' ? 'EN VIVO' : 'PROGRAMADA' }}
                                </Badge>
                            </div>
                            <div class="relative z-10">
                                <h3 class="text-sm font-black uppercase tracking-tighter leading-tight mb-1">{{ asamblea.titulo }}</h3>
                                <p class="text-[9px] font-bold uppercase tracking-wider opacity-60 mb-5">{{ formatDate(asamblea.fecha) }}</p>
                                <Link :href="route('asambleas.show', asamblea.id)" target="_blank">
                                    <Button variant="primary" size="sm" icon="login" class="w-full !bg-emerald-500 hover:!bg-emerald-600 !text-white !border-0 shadow-lg shadow-emerald-500/30 !rounded-xl !py-3">
                                        Ingresar
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-outlined, .material-symbols-rounded {
    font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
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

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
</style>
