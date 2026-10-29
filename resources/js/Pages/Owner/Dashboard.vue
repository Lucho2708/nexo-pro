<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Modal from '@/Components/UI/Modal.vue';
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
    asambleas?: Array<any>;
    announcements?: Array<any>;
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
const selectedAnnouncement = ref<any>(null);

const handlePayment = () => {
    if (props.total_saldo <= 0) {
        toast.add('¡Felicitaciones! No tienes saldos pendientes para pagar.', 'success');
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
        toast.add('Instrucciones para pago manual: Consigna a la cuenta corriente de la copropiedad.', 'info');
    }
};

onMounted(() => {
    isLoaded.value = true;
});

const defaultBanners = [
    'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=1200&q=80',
    'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=1200&q=80'
];

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

const isPazYSalvo = computed(() => (props.total_saldo || 0) <= 0);

const downloadCertificate = () => {
    toast.add('Generando tu Certificado de Paz y Salvo Oficial...', 'info');
    setTimeout(() => {
        window.print();
    }, 500);
};
</script>

<template>
    <Head title="Panel de Propietario — NEXO-PRO" />
    
    <!-- Payment Logic & Gateways -->
    <PaymentModule 
        v-if="unidades && unidades.length > 0"
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

    <!-- Announcement Detail Modal -->
    <Modal
        v-if="selectedAnnouncement"
        :show="!!selectedAnnouncement"
        title="Comunicado Oficial"
        @close="selectedAnnouncement = null"
    >
        <div class="space-y-4">
            <div class="flex items-center gap-2">
                <Badge :variant="selectedAnnouncement.type === 'danger' ? 'danger' : (selectedAnnouncement.type === 'warning' ? 'warning' : 'primary')">
                    {{ selectedAnnouncement.type?.toUpperCase() || 'INFORMACIÓN' }}
                </Badge>
                <span class="text-xs text-on-surface-variant/60 font-medium">{{ formatDate(selectedAnnouncement.created_at) }}</span>
            </div>
            <h3 class="text-xl font-black text-on-surface tracking-tight">{{ selectedAnnouncement.title }}</h3>
            <p class="text-sm text-on-surface/80 whitespace-pre-line leading-relaxed">{{ selectedAnnouncement.message }}</p>
            <div class="pt-4 border-t border-outline-variant/10 flex justify-end">
                <Button variant="primary" size="sm" @click="selectedAnnouncement = null">Entendido</Button>
            </div>
        </div>
    </Modal>

    <!-- Main Container -->
    <div class="max-w-[1400px] mx-auto space-y-6 md:space-y-8">
        
        <!-- Header & Quick Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-1">
            <div>
                <div class="flex items-center gap-2">
                    <span class="text-[10px] font-black uppercase tracking-widest text-primary">Bienvenido, {{ user.name?.split(' ')[0] }}</span>
                </div>
                <h2 class="text-2xl md:text-3xl font-black text-on-surface tracking-tight leading-tight">
                    Resumen de Copropietario
                </h2>
                <p class="text-xs text-on-surface-variant/60 font-medium mt-0.5">Control financiero, amenidades y vida en comunidad</p>
            </div>

            <div class="flex items-center gap-2.5">
                <button 
                    v-if="!isPazYSalvo"
                    @click="handlePayment"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-xs font-black uppercase tracking-wider shadow-md hover:shadow-lg transition-all cursor-pointer active:scale-95"
                >
                    <span class="material-symbols-rounded text-base">payments</span>
                    <span>Pagar Expensas</span>
                </button>
                <button 
                    v-else
                    @click="downloadCertificate"
                    type="button"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 border border-emerald-500/30 text-emerald-600 dark:text-emerald-400 text-xs font-black uppercase tracking-wider transition-all cursor-pointer"
                >
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Paz y Salvo Vigente</span>
                </button>
            </div>
        </div>

        <!-- 4 Quick Ergonomic Touch Shortcuts -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4">
            <Link :href="route('owner.payments')" class="p-4 rounded-2xl bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 hover:border-primary/40 hover:shadow-md transition-all group flex flex-col justify-between min-h-[100px]">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-all">
                        <span class="material-symbols-rounded text-xl">receipt_long</span>
                    </div>
                    <span class="material-symbols-rounded text-on-surface-variant/40 group-hover:text-primary group-hover:translate-x-1 transition-all text-sm">arrow_forward</span>
                </div>
                <div>
                    <p class="text-xs font-black text-on-surface">Estado de Cuenta</p>
                    <p class="text-[10px] text-on-surface-variant/60 font-medium">Recibos y aportes</p>
                </div>
            </Link>

            <Link :href="route('reservas.index')" class="p-4 rounded-2xl bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 hover:border-secondary/40 hover:shadow-md transition-all group flex flex-col justify-between min-h-[100px]">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center group-hover:bg-secondary group-hover:text-white transition-all">
                        <span class="material-symbols-rounded text-xl">event_available</span>
                    </div>
                    <span class="material-symbols-rounded text-on-surface-variant/40 group-hover:text-secondary group-hover:translate-x-1 transition-all text-sm">arrow_forward</span>
                </div>
                <div>
                    <p class="text-xs font-black text-on-surface">Zonas Comunes</p>
                    <p class="text-[10px] text-on-surface-variant/60 font-medium">Reservar espacios</p>
                </div>
            </Link>

            <Link :href="route('pqrs.index')" class="p-4 rounded-2xl bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 hover:border-primary/40 hover:shadow-md transition-all group flex flex-col justify-between min-h-[100px]">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-600 flex items-center justify-center group-hover:bg-blue-600 group-hover:text-white transition-all">
                        <span class="material-symbols-rounded text-xl">chat_bubble</span>
                    </div>
                    <span class="material-symbols-rounded text-on-surface-variant/40 group-hover:text-blue-600 group-hover:translate-x-1 transition-all text-sm">arrow_forward</span>
                </div>
                <div>
                    <p class="text-xs font-black text-on-surface">Radicar PQRS</p>
                    <p class="text-[10px] text-on-surface-variant/60 font-medium">Peticiones y reclamos</p>
                </div>
            </Link>

            <button @click="downloadCertificate" type="button" class="p-4 rounded-2xl bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/20 hover:border-emerald-500/40 hover:shadow-md transition-all group flex flex-col justify-between min-h-[100px] text-left cursor-pointer">
                <div class="flex items-center justify-between">
                    <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 flex items-center justify-center group-hover:bg-emerald-600 group-hover:text-white transition-all">
                        <span class="material-symbols-rounded text-xl">verified</span>
                    </div>
                    <span class="material-symbols-rounded text-on-surface-variant/40 group-hover:text-emerald-600 group-hover:translate-x-1 transition-all text-sm">download</span>
                </div>
                <div>
                    <p class="text-xs font-black text-on-surface">Certificado Oficial</p>
                    <p class="text-[10px] text-on-surface-variant/60 font-medium">Descarga inmediata</p>
                </div>
            </button>
        </div>

        <!-- Skeletons State -->
        <div v-if="!isLoaded" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            <div class="lg:col-span-8 flex flex-col gap-6 w-full">
                <div class="h-64 bg-surface-container-high/40 animate-pulse rounded-[2rem] w-full"></div>
                <div class="h-32 bg-surface-container-high/30 animate-pulse rounded-[2rem] w-full"></div>
            </div>
            <div class="lg:col-span-4 flex flex-col gap-6 w-full">
                <div class="h-44 bg-surface-container-high/40 animate-pulse rounded-[2rem] w-full"></div>
                <div class="h-64 bg-surface-container-high/30 animate-pulse rounded-[2rem] w-full"></div>
            </div>
        </div>

        <!-- Loaded Content Grid -->
        <div v-else class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
            
            <!-- Left Column: Announcements, Specs & History -->
            <div class="lg:col-span-8 flex flex-col gap-6 md:gap-8 order-1">
                
                <!-- Official Announcements Section -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between px-1">
                        <p class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></span>
                            Comunicados de la Administración
                        </p>
                    </div>

                    <!-- If DB Announcements exist -->
                    <div v-if="announcements && announcements.length > 0" class="space-y-3">
                        <div 
                            v-for="item in announcements.slice(0, 2)" 
                            :key="item.id"
                            @click="selectedAnnouncement = item"
                            class="p-4 sm:p-5 rounded-2xl bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/30 hover:border-primary/40 hover:shadow-md transition-all cursor-pointer flex items-start gap-4 group"
                        >
                            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                                <span class="material-symbols-rounded text-xl">campaign</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[9px] font-black uppercase tracking-wider text-primary bg-primary/10 px-2 py-0.5 rounded-md">Oficial</span>
                                    <span class="text-[10px] text-on-surface-variant/60 font-medium">{{ formatDate(item.created_at) }}</span>
                                </div>
                                <h4 class="text-sm font-black text-on-surface group-hover:text-primary transition-colors truncate">{{ item.title }}</h4>
                                <p class="text-xs text-on-surface-variant/80 line-clamp-2 mt-0.5 leading-relaxed">{{ item.message }}</p>
                            </div>
                            <span class="material-symbols-rounded text-on-surface-variant/30 group-hover:text-primary text-base shrink-0 mt-2">arrow_forward</span>
                        </div>
                    </div>

                    <!-- Fallback Photographic Banner -->
                    <div v-else class="rounded-[2rem] overflow-hidden shadow-md border border-outline-variant/20 bg-surface-container">
                        <Carousel :images="defaultBanners" class="w-full aspect-[16/9] md:aspect-[21/9] lg:h-[300px]" />
                    </div>
                </div>

                <!-- Unit Technical Specs with Multi-Property Switcher -->
                <UnitSpecsCard :unidades="unidades" :two-factor-enabled="features.two_factor_enabled" />

                <!-- Payment History Card -->
                <Card icon="history" title="Historial de Pagos" subtitle="Últimos movimientos registrados en el sistema" content-class="!p-0 !pb-0" class="overflow-hidden !rounded-[2rem]">
                    <template #header>
                        <Link :href="route('owner.payments')">
                            <Button variant="ghost" size="sm" class="!text-primary hover:bg-primary/10 text-xs font-bold">Ver todo el extracto</Button>
                        </Link>
                    </template>

                    <div class="flex flex-col w-full">
                        <div 
                            v-for="tx in transacciones" 
                            :key="tx.id"
                            class="px-4 md:px-6 py-4 flex items-center gap-3.5 sm:gap-4 hover:bg-surface-container-high/40 transition-colors border-b border-outline-variant/10 last:border-0"
                        >
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 shadow-sm border border-outline-variant/10"
                                :class="tx.tipo === 'abono' ? 'bg-emerald-500/10 text-emerald-600' : 'bg-primary/10 text-primary'"
                            >
                                <span class="material-symbols-rounded text-lg">
                                    {{ tx.tipo === 'abono' ? 'check_circle' : 'receipt' }}
                                </span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-bold text-on-surface truncate">{{ tx.concepto?.nombre || 'Cuota de Administración' }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] text-on-surface-variant/60 font-medium">{{ formatDate(tx.fecha) }}</span>
                                    <Badge v-if="tx.unidad" variant="neutral" class="!text-[8px] !px-1.5 opacity-80">
                                        {{ tx.unidad?.torre }} {{ tx.unidad?.nombre }}
                                    </Badge>
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm sm:text-base font-black tracking-tight"
                                    :class="tx.tipo === 'abono' ? 'text-emerald-600 dark:text-emerald-400' : 'text-on-surface'"
                                >
                                    {{ tx.tipo === 'abono' ? '+' : '' }}{{ formatCurrency(tx.monto) }}
                                </p>
                                <span class="text-[9px] font-bold uppercase tracking-wider" :class="tx.tipo === 'abono' ? 'text-emerald-600' : 'text-primary'">
                                    {{ tx.tipo === 'abono' ? 'Abono Exitoso' : 'Cobro Ordinario' }}
                                </span>
                            </div>
                        </div>

                        <div v-if="!transacciones || transacciones.length === 0" class="py-12 text-center text-on-surface-variant/50 flex flex-col items-center gap-2">
                            <span class="material-symbols-rounded text-3xl opacity-40">receipt_long</span>
                            <p class="text-xs font-bold">Sin transacciones registradas todavía</p>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Right Column: Balance & Events -->
            <div class="lg:col-span-4 flex flex-col gap-6 md:gap-8 order-2 lg:order-last">
                
                <!-- Financial Balance Card -->
                <div class="rounded-[2.5rem] p-6 md:p-8 bg-surface-container-lowest dark:bg-surface-container-low border border-outline-variant/30 shadow-lg relative overflow-hidden flex flex-col justify-between">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/60">Saldo Consolidado</span>
                                <h3 class="text-3xl sm:text-4xl font-black text-on-surface tracking-tight mt-1">
                                    {{ formatCurrency(total_saldo) }}
                                </h3>
                            </div>
                            <Badge :variant="isPazYSalvo ? 'success' : 'danger'" class="!px-3 !py-1 text-[10px] font-black uppercase tracking-wider">
                                {{ isPazYSalvo ? 'Paz y Salvo' : 'Saldo Pendiente' }}
                            </Badge>
                        </div>

                        <!-- Multi-unit breakdown preview if multiple units exist -->
                        <div v-if="unidades && unidades.length > 1" class="space-y-2 pt-2 border-t border-outline-variant/10">
                            <p class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant/50">Detalle por unidad:</p>
                            <div v-for="u in unidades" :key="u.id" class="flex justify-between items-center text-xs">
                                <span class="font-bold text-on-surface-variant">{{ u.torre }} {{ u.nombre }}</span>
                                <span class="font-black" :class="(u.saldo_actual || 0) > 0 ? 'text-error' : 'text-emerald-600'">
                                    {{ formatCurrency(u.saldo_actual) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-2.5 pt-6 mt-4 border-t border-outline-variant/10">
                        <Button 
                            v-if="features.payments_enabled && !isPazYSalvo"
                            variant="primary"
                            size="lg"
                            icon="payments"
                            class="w-full !py-3.5 shadow-lg shadow-primary/20 !rounded-xl text-xs font-black uppercase tracking-wider"
                            @click="handlePayment"
                        >
                            Pagar Ahora
                        </Button>
                        <Button 
                            v-else-if="features.payments_enabled && isPazYSalvo"
                            variant="outline"
                            size="sm"
                            icon="verified"
                            class="w-full !rounded-xl !py-3 text-xs font-bold text-emerald-600 border-emerald-500/30 hover:bg-emerald-500/10"
                            @click="downloadCertificate"
                        >
                            Descargar Paz y Salvo
                        </Button>
                        <Link :href="route('owner.payments')" class="w-full">
                            <Button variant="ghost" size="sm" icon="description" class="w-full !text-xs font-bold text-on-surface-variant hover:text-primary">
                                Consultar Detalle Completo
                            </Button>
                        </Link>
                    </div>
                </div>

                <!-- Assemblies Banner if active/scheduled -->
                <div v-if="asambleas && asambleas.length > 0" class="flex flex-col gap-3">
                    <p class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest pl-2 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        Eventos y Asambleas
                    </p>
                    <div 
                        v-for="asamblea in asambleas" 
                        :key="asamblea.id" 
                        class="bg-[#00173c] text-white p-6 rounded-[2.5rem] shadow-xl relative overflow-hidden group border border-white/10"
                    >
                        <div class="flex items-start justify-between mb-4 relative z-10">
                            <div class="w-10 h-10 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md border border-white/10">
                                <span class="material-symbols-rounded text-emerald-400 text-xl">gavel</span>
                            </div>
                            <Badge variant="success" class="!bg-emerald-500 !text-white !border-0 !px-2.5 !py-0.5 text-[9px] font-black uppercase tracking-widest">
                                {{ asamblea.status === 'in_progress' ? 'EN VIVO' : 'PROGRAMADA' }}
                            </Badge>
                        </div>
                        <div class="relative z-10">
                            <h3 class="text-sm font-black tracking-tight leading-snug mb-1">{{ asamblea.titulo }}</h3>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-white/60 mb-4">{{ formatDate(asamblea.fecha) }}</p>
                            <Link :href="route('asambleas.show', asamblea.id)" target="_blank">
                                <Button variant="primary" size="sm" icon="login" class="w-full !bg-emerald-500 hover:!bg-emerald-600 !text-white !border-0 shadow-lg shadow-emerald-500/30 !rounded-xl !py-2.5 text-xs font-bold">
                                    Ingresar a la Asamblea
                                </Button>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Safe Community Support Banner -->
                <div class="p-5 rounded-[2rem] bg-surface-container dark:bg-white/5 border border-outline-variant/20 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-xl">support_agent</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-on-surface">¿Necesitas asistencia?</p>
                        <p class="text-[10px] text-on-surface-variant/70">Atención técnica y soporte a la comunidad.</p>
                    </div>
                    <Link :href="route('support.index')">
                        <Button variant="ghost" size="sm" icon="arrow_forward" class="!p-2"></Button>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
