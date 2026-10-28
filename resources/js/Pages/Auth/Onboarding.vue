<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Checkbox from '@/Components/UI/Checkbox.vue';
import Alert from '@/Components/UI/Alert.vue';
import Card from '@/Components/UI/Card.vue';
import Logo from '@/Components/UI/Logo.vue';
import Modal from '@/Components/UI/Modal.vue';
import LegalContent from '@/Components/Legal/LegalContent.vue';
import { useColombiaGeography } from '@/Composables/useColombiaGeography';

const props = withDefaults(defineProps<{
    available_plans?: any[];
    selected_plan_slug?: string;
    initial_billing_cycle?: 'monthly' | 'annual';
}>(), {
    available_plans: () => [],
    selected_plan_slug: 'condominio_pro',
    initial_billing_cycle: 'monthly',
});

const { departamentosList, getMunicipios } = useColombiaGeography();

const showLegalModal = ref(false);
const legalModalType = ref<'privacy' | 'terms'>('privacy');
const showPlanChangeModal = ref(false);
const showMobileSummary = ref(false);

const openLegalModal = (type: 'privacy' | 'terms') => {
    legalModalType.value = type;
    showLegalModal.value = true;
};

const acceptLegalModal = () => {
    form.terms = true;
    showLegalModal.value = false;
};

// 2-Step Optimized Registration
const step = ref(1);
const totalSteps = 2;

const billingCycle = ref<'monthly' | 'annual'>(props.initial_billing_cycle || 'monthly');

const form = useForm({
    // Step 1: Legal Property Info
    nit: '',
    nombre_copropiedad: '',
    direccion: '',
    departamento: '',
    ciudad: '',
    
    // Subscription Plan
    plan: props.selected_plan_slug || 'condominio_pro',
    billing_cycle: props.initial_billing_cycle || 'monthly',
    
    // Step 2: Administrator Master Account
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const availableMunicipios = computed(() => getMunicipios(form.departamento));

const onDepartamentoChange = (dep: string | number) => {
    form.departamento = String(dep);
    form.ciudad = '';
    form.clearErrors('departamento', 'ciudad');
};

const showPassword = ref(false);
const showPasswordConfirm = ref(false);

const nextStep = () => {
    // Validate Step 1 before moving forward
    form.clearErrors();
    if (!form.nit) form.setError('nit', 'El NIT de la copropiedad es requerido.');
    if (!form.nombre_copropiedad) form.setError('nombre_copropiedad', 'El nombre del conjunto es requerido.');
    if (!form.direccion) form.setError('direccion', 'La dirección física es requerida.');
    if (!form.departamento) form.setError('departamento', 'El departamento es requerido.');
    if (!form.ciudad) form.setError('ciudad', 'La ciudad o municipio es requerido.');

    if (Object.keys(form.errors).length === 0) {
        step.value = 2;
    }
};

const prevStep = () => {
    step.value = 1;
};

const submit = () => {
    form.billing_cycle = billingCycle.value;
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const stepInfo = computed(() => {
    switch (step.value) {
        case 1: return { title: 'Identificación', subtitle: 'Paso 1 de 2: Datos legales del conjunto' };
        case 2: return { title: 'Acceso Maestro', subtitle: 'Paso 2 de 2: Tu cuenta de administrador' };
        default: return { title: '', subtitle: '' };
    }
});

// Fallback plans if not provided via props
const fallbackPlans = [
    {
        slug: 'boutique',
        name: 'BOUTIQUE',
        max_units: 60,
        price_monthly: 189000,
        price_annual: 151200,
        description: 'Solución completa para edificios y conjuntos pequeños.',
        features: [
            'Hasta 60 unidades privadas',
            'Recaudo PSE, Wompi y Nequi',
            'App móvil para residentes',
            'Citofonía virtual y portería',
        ],
    },
    {
        slug: 'condominio_pro',
        name: 'CONDOMINIO PRO',
        max_units: 150,
        price_monthly: 289000,
        price_annual: 231200,
        is_recommended: true,
        description: 'El estándar de oro para administradores profesionales y consejos.',
        features: [
            'Hasta 150 unidades privadas',
            'Asambleas Virtuales Ley 675 en vivo',
            'Votación atómica en tiempo real',
            'Conciliación bancaria automática',
        ],
    },
    {
        slug: 'macro_conjunto',
        name: 'MACRO-CONJUNTO',
        max_units: 350,
        price_monthly: 420000,
        price_annual: 336000,
        description: 'Máxima potencia para grandes copropiedades y urbanizaciones multitorre.',
        features: [
            'Hasta 350 unidades privadas',
            'Múltiples torres y porterías simultáneas',
            'Migración asistida de datos Excel en 24h',
            'Soporte técnico prioritario en asambleas',
        ],
    },
];

const plansList = computed(() => {
    return props.available_plans?.length ? props.available_plans : fallbackPlans;
});

const currentPlan = computed(() => {
    const found = plansList.value.find((p: any) => p.slug === form.plan);
    return found || plansList.value[1] || plansList.value[0];
});

const formatPrice = (price: number | null | undefined) => {
    if (price === null || price === undefined) return 'CUSTOM';
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0,
    }).format(price);
};

const selectPlan = (slug: string) => {
    form.plan = slug;
    showPlanChangeModal.value = false;
};
</script>

<template>
    <Head title="Registro de Copropiedad — NEXO-PRO" />

    <GuestLayout>
        <div class="max-w-6xl mx-auto w-full px-4 sm:px-6 py-8 sm:py-12 md:py-16 min-h-screen flex flex-col justify-center">
            
            <!-- Branding Header -->
            <div class="text-center mb-8 sm:mb-10 space-y-2">
                <div class="flex justify-center mb-4">
                    <Logo width="64px" height="64px" :show-text="false" />
                </div>
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-on-surface dark:text-white tracking-tight uppercase leading-none">
                    Únete a <span class="text-primary italic">NEXO-PRO</span>
                </h1>
                <p class="text-[10px] sm:text-xs text-on-surface-variant/60 font-bold uppercase tracking-[0.2em]">
                    Plataforma Integral de Gestión y Gobierno para Propiedad Horizontal
                </p>
            </div>

            <!-- Mobile Collapsible Order Summary Banner -->
            <div class="lg:hidden mb-6 p-4 rounded-2xl bg-surface-container-high/60 dark:bg-white/5 border border-outline-variant/15 flex flex-col gap-2 shadow-sm">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-xs font-black uppercase tracking-wider text-primary">{{ currentPlan.name }}</span>
                        <span class="text-[9px] font-bold text-on-surface-variant/60">
                            ({{ billingCycle === 'annual' ? 'Anual -20%' : 'Mensual' }})
                        </span>
                    </div>
                    <button 
                        @click="showMobileSummary = !showMobileSummary" 
                        type="button"
                        class="text-[10px] font-black text-primary uppercase tracking-wider flex items-center gap-1 cursor-pointer"
                    >
                        <span>{{ showMobileSummary ? 'Ocultar' : 'Ver Detalle' }}</span>
                        <span class="material-symbols-outlined text-sm">{{ showMobileSummary ? 'expand_less' : 'expand_more' }}</span>
                    </button>
                </div>
                <div v-if="showMobileSummary" class="pt-3 border-t border-outline-variant/10 space-y-2 animate-in fade-in duration-200">
                    <div class="flex items-baseline justify-between">
                        <span class="text-lg font-black text-on-surface dark:text-white">
                            {{ formatPrice(billingCycle === 'annual' ? currentPlan.price_annual : currentPlan.price_monthly) }}
                        </span>
                        <span class="text-[10px] font-bold text-on-surface-variant/50">Por copropiedad / mes</span>
                    </div>
                    <p class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400">
                        Capacidad: {{ currentPlan.max_units ? `Hasta ${currentPlan.max_units} unidades privadas` : 'Ilimitada' }}
                    </p>
                    <button 
                        @click="showPlanChangeModal = true" 
                        type="button" 
                        class="text-[9px] font-black text-primary hover:underline uppercase tracking-widest pt-1"
                    >
                        Cambiar de Plan
                    </button>
                </div>
            </div>

            <!-- Two-Column Checkout Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                <!-- LEFT COLUMN: 2-STEP WIZARD (7 Cols) -->
                <div class="lg:col-span-7 space-y-6">
                    
                    <!-- Modern Stepper Indicator -->
                    <div class="p-4 sm:p-5 rounded-2xl bg-surface dark:bg-[#0b0e14] border border-outline-variant/15 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div 
                                class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black transition-all"
                                :class="step === 1 ? 'bg-primary text-white shadow-md shadow-primary/20 scale-105' : 'bg-emerald-500 text-white'"
                            >
                                <span v-if="step > 1" class="material-symbols-outlined text-base">check</span>
                                <span v-else>1</span>
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-wider text-on-surface dark:text-white">Copropiedad</p>
                                <p class="text-[9px] font-bold text-on-surface-variant/50 uppercase tracking-widest">Datos legales</p>
                            </div>
                        </div>

                        <div class="w-12 h-0.5 bg-outline-variant/20 rounded-full"></div>

                        <div class="flex items-center gap-3">
                            <div 
                                class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-black transition-all"
                                :class="step === 2 ? 'bg-primary text-white shadow-md shadow-primary/20 scale-105' : 'bg-surface-container-high text-on-surface-variant/40'"
                            >
                                2
                            </div>
                            <div>
                                <p class="text-xs font-black uppercase tracking-wider text-on-surface dark:text-white">Administrador</p>
                                <p class="text-[9px] font-bold text-on-surface-variant/50 uppercase tracking-widest">Cuenta de acceso</p>
                            </div>
                        </div>
                    </div>

                    <!-- Error Alert -->
                    <Alert v-if="Object.keys(form.errors).length > 0" variant="error" title="Atención" class="mb-4">
                        Por favor verifica los campos marcados para continuar con la activación.
                    </Alert>

                    <!-- Wizard Card -->
                    <Card class="p-6 sm:p-8 rounded-[2rem] border border-outline-variant/15 bg-surface dark:bg-[#0b0e14] shadow-xl">
                        
                        <!-- STEP 1: PROPERTY LEGAL INFO -->
                        <div v-if="step === 1" class="space-y-5 animate-in fade-in duration-300">
                            <div>
                                <h3 class="text-lg sm:text-xl font-black text-on-surface dark:text-white uppercase tracking-tight">
                                    Datos Legales del Conjunto
                                </h3>
                                <p class="text-[10px] font-bold text-on-surface-variant/50 uppercase tracking-widest mt-1">
                                    Información requerida para emitir cuentas de cobro y certificados
                                </p>
                            </div>

                            <div class="space-y-4 pt-2">
                                <Input 
                                    v-model="form.nit" 
                                    label="NIT de la Copropiedad *" 
                                    placeholder="900.XXX.XXX-X" 
                                    icon="badge" 
                                    required 
                                    :error="form.errors.nit" 
                                    class="!rounded-2xl"
                                />
                                <Input 
                                    v-model="form.nombre_copropiedad" 
                                    label="Nombre del Edificio / Conjunto *" 
                                    placeholder="Ej: Residencial El Sol" 
                                    icon="corporate_fare" 
                                    required 
                                    :error="form.errors.nombre_copropiedad" 
                                    class="!rounded-2xl"
                                />
                                <Input 
                                    v-model="form.direccion" 
                                    label="Dirección Física *" 
                                    placeholder="Calle 123 # 45-67" 
                                    icon="location_on" 
                                    required 
                                    :error="form.errors.direccion" 
                                    class="!rounded-2xl"
                                />

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <Select 
                                        v-model="form.departamento" 
                                        :options="departamentosList"
                                        label="Departamento *" 
                                        placeholder="Seleccionar departamento..." 
                                        icon="map" 
                                        searchable
                                        :error="form.errors.departamento"
                                        @change="onDepartamentoChange"
                                    />
                                    <Select 
                                        v-model="form.ciudad" 
                                        :options="availableMunicipios"
                                        label="Municipio / Ciudad *" 
                                        :placeholder="form.departamento ? 'Seleccionar municipio...' : 'Primero selecciona departamento...'" 
                                        icon="location_city" 
                                        searchable
                                        :disabled="!form.departamento"
                                        :error="form.errors.ciudad" 
                                    />
                                </div>
                            </div>

                            <div class="pt-6 border-t border-outline-variant/10 flex justify-end">
                                <Button 
                                    type="button" 
                                    variant="primary" 
                                    size="lg" 
                                    class="w-full sm:w-auto font-black uppercase tracking-wider shadow-lg shadow-primary/20 min-h-[48px]"
                                    icon="arrow_forward"
                                    @click="nextStep"
                                >
                                    CONTINUAR A DATOS DE ACCESO
                                </Button>
                            </div>
                        </div>

                        <!-- STEP 2: MASTER ADMIN ACCOUNT -->
                        <div v-else-if="step === 2" class="space-y-5 animate-in fade-in duration-300">
                            <div>
                                <h3 class="text-lg sm:text-xl font-black text-on-surface dark:text-white uppercase tracking-tight">
                                    Cuenta Maestra de Administrador
                                </h3>
                                <p class="text-[10px] font-bold text-on-surface-variant/50 uppercase tracking-widest mt-1">
                                    Credenciales con las que ingresarás al panel de control
                                </p>
                            </div>

                            <div class="space-y-4 pt-2">
                                <Input 
                                    v-model="form.name" 
                                    label="Nombre del Administrador *" 
                                    placeholder="Nombre completo" 
                                    icon="person" 
                                    required 
                                    :error="form.errors.name" 
                                    class="!rounded-2xl"
                                />
                                <Input 
                                    v-model="form.email" 
                                    type="email" 
                                    label="Correo Electrónico Corporativo *" 
                                    placeholder="admin@tuconjunto.com" 
                                    icon="alternate_email" 
                                    required 
                                    :error="form.errors.email" 
                                    class="!rounded-2xl"
                                />
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="relative">
                                        <Input 
                                            v-model="form.password" 
                                            :type="showPassword ? 'text' : 'password'" 
                                            label="Contraseña de Acceso *" 
                                            placeholder="••••••••" 
                                            icon="lock" 
                                            required 
                                            :error="form.errors.password" 
                                            class="!rounded-2xl pr-12"
                                        />
                                        <button 
                                            type="button" 
                                            @click="showPassword = !showPassword" 
                                            class="absolute right-3.5 top-9 w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant/60 hover:text-on-surface cursor-pointer"
                                            aria-label="Alternar visibilidad"
                                        >
                                            <span class="material-symbols-outlined text-lg">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <Input 
                                            v-model="form.password_confirmation" 
                                            :type="showPasswordConfirm ? 'text' : 'password'" 
                                            label="Confirmar Contraseña *" 
                                            placeholder="••••••••" 
                                            icon="verified_user" 
                                            required 
                                            class="!rounded-2xl pr-12"
                                        />
                                        <button 
                                            type="button" 
                                            @click="showPasswordConfirm = !showPasswordConfirm" 
                                            class="absolute right-3.5 top-9 w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant/60 hover:text-on-surface cursor-pointer"
                                            aria-label="Alternar visibilidad"
                                        >
                                            <span class="material-symbols-outlined text-lg">{{ showPasswordConfirm ? 'visibility_off' : 'visibility' }}</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="p-3.5 rounded-2xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10">
                                    <Checkbox v-model:checked="form.terms" required :error="form.errors.terms">
                                        <template #description>
                                            <span class="text-[11px] leading-relaxed">
                                                Autorizo a NEXO-PRO para el tratamiento de datos personales conforme a la 
                                                <button type="button" @click.prevent.stop="openLegalModal('privacy')" class="text-primary font-bold hover:underline">Política de Privacidad</button> 
                                                y acepto los 
                                                <button type="button" @click.prevent.stop="openLegalModal('terms')" class="text-primary font-bold hover:underline">Términos del Servicio</button>.
                                            </span>
                                        </template>
                                    </Checkbox>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-outline-variant/10 flex flex-col sm:flex-row justify-between items-center gap-3">
                                <Button 
                                    type="button" 
                                    variant="ghost" 
                                    icon="arrow_back" 
                                    @click="prevStep"
                                    class="w-full sm:w-auto font-black uppercase text-xs"
                                >
                                    VOLVER
                                </Button>
                                <Button 
                                    type="button" 
                                    variant="primary" 
                                    size="lg" 
                                    class="w-full sm:w-auto font-black uppercase tracking-wider shadow-xl shadow-primary/25 min-h-[48px]"
                                    icon="how_to_reg"
                                    :loading="form.processing"
                                    @click="submit"
                                >
                                    ACTIVAR LICENCIA Y FINALIZAR
                                </Button>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- RIGHT COLUMN: ORDER SUMMARY CARD (5 Cols) -->
                <div class="hidden lg:block lg:col-span-5 sticky top-8 space-y-4">
                    <div class="p-6 sm:p-7 rounded-[2rem] bg-slate-950 text-white border border-indigo-500/30 shadow-2xl relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>

                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-400 text-[9px] font-black uppercase tracking-widest border border-indigo-500/30">
                                Licencia Seleccionada
                            </span>
                            <button 
                                @click="showPlanChangeModal = true" 
                                type="button"
                                class="text-[10px] font-black text-indigo-400 hover:text-white uppercase tracking-wider underline cursor-pointer"
                            >
                                Cambiar Plan
                            </button>
                        </div>

                        <h3 class="text-2xl font-black uppercase italic tracking-tight text-white mb-1">
                            {{ currentPlan.name }}
                        </h3>
                        <p class="text-xs text-slate-300 font-medium leading-relaxed mb-6">
                            {{ currentPlan.description }}
                        </p>

                        <!-- Price display -->
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/10 mb-6">
                            <div class="flex items-baseline justify-between mb-1">
                                <span class="text-3xl font-black text-white tabular-nums">
                                    {{ formatPrice(billingCycle === 'annual' ? currentPlan.price_annual : currentPlan.price_monthly) }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                    {{ currentPlan.period_label || 'Por Mes' }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between text-[10px] font-bold">
                                <span class="text-slate-400">Modalidad:</span>
                                <span class="text-emerald-400 font-black uppercase">
                                    {{ billingCycle === 'annual' ? 'Facturación Anual (-20% OFF)' : 'Facturación Mensual' }}
                                </span>
                            </div>
                        </div>

                        <!-- Capacity highlight -->
                        <div class="flex items-center gap-3 p-3.5 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 mb-6">
                            <span class="material-symbols-outlined text-xl">apartment</span>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-wider">Tope de Capacidad</p>
                                <p class="text-xs font-bold text-white">
                                    {{ currentPlan.max_units ? `Hasta ${currentPlan.max_units} unidades privadas` : 'Unidades Ilimitadas' }}
                                </p>
                            </div>
                        </div>

                        <!-- Feature list -->
                        <div class="space-y-2.5 mb-6">
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Incluido en tu suscripción:</p>
                            <div 
                                v-for="(feat, idx) in currentPlan.features" 
                                :key="idx" 
                                class="flex items-center gap-2.5 text-xs text-slate-200"
                            >
                                <span class="material-symbols-outlined text-sm text-indigo-400">check_circle</span>
                                <span class="truncate">{{ feat }}</span>
                            </div>
                        </div>

                        <!-- Notice on setup location -->
                        <div class="p-3 rounded-xl bg-white/[0.03] border border-white/5 text-[9px] text-slate-400 leading-relaxed italic">
                            * Las torres, pisos y nomenclatura se configuran al ingresar con nuestro Generador Asistido o cargando tu censo en Excel.
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Link -->
            <div class="mt-8 text-center">
                <p class="text-[10px] text-on-surface-variant/50 font-bold uppercase tracking-widest">
                    ¿Ya gestionas con nosotros? 
                    <Link :href="route('login')" class="text-primary font-black ml-1 hover:underline underline-offset-4">Inicia Sesión</Link>
                </p>
            </div>
        </div>
    </GuestLayout>

    <!-- Plan Change Modal -->
    <Modal :show="showPlanChangeModal" @close="showPlanChangeModal = false" maxWidth="2xl">
        <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center bg-surface dark:bg-[#0b0e14]">
            <h3 class="text-base font-black text-on-surface dark:text-white uppercase tracking-tight">
                Seleccionar Plan de Suscripción
            </h3>
            <button @click="showPlanChangeModal = false" class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>

        <div class="p-6 space-y-4 bg-surface dark:bg-[#0b0e14]">
            <!-- Billing toggle -->
            <div class="flex justify-center items-center gap-4 py-2">
                <button 
                    @click="billingCycle = 'monthly'" 
                    type="button"
                    class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase transition-all"
                    :class="billingCycle === 'monthly' ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant/60'"
                >
                    Mensual
                </button>
                <button 
                    @click="billingCycle = 'annual'" 
                    type="button"
                    class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase transition-all flex items-center gap-1.5"
                    :class="billingCycle === 'annual' ? 'bg-primary text-white shadow-sm' : 'text-on-surface-variant/60'"
                >
                    <span>Anual</span>
                    <span class="text-[8px] px-1.5 py-0.2 bg-emerald-500 text-white rounded-full font-black">-20%</span>
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div 
                    v-for="p in plansList" 
                    :key="p.slug"
                    @click="selectPlan(p.slug)"
                    class="p-4 rounded-2xl border-2 cursor-pointer transition-all flex flex-col justify-between"
                    :class="form.plan === p.slug ? 'border-primary bg-primary/5 shadow-md' : 'border-outline-variant/15 hover:border-primary/40'"
                >
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs font-black uppercase text-primary">{{ p.name }}</span>
                            <span v-if="form.plan === p.slug" class="material-symbols-outlined text-sm text-primary">check_circle</span>
                        </div>
                        <p class="text-[10px] font-bold text-on-surface-variant/70 mb-2">
                            {{ p.max_units ? `Hasta ${p.max_units} unds` : 'Ilimitado' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-black text-on-surface dark:text-white">
                            {{ formatPrice(billingCycle === 'annual' ? p.price_annual : p.price_monthly) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </Modal>

    <!-- Legal Information Modal -->
    <Modal :show="showLegalModal" @close="showLegalModal = false" maxWidth="4xl">
        <div class="px-6 py-6 md:px-8 md:py-8 border-b border-outline-variant/10 flex justify-between items-center sticky top-0 bg-surface/80 backdrop-blur-xl z-20">
            <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">
                Información Legal
            </h3>
            <button @click="showLegalModal = false" class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
        
        <div class="p-6 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar bg-surface">
            <LegalContent :type="legalModalType" />
        </div>
        
        <div class="px-6 py-4 md:px-8 bg-surface-container-low border-t border-outline-variant/10 flex justify-end gap-3">
            <Button variant="ghost" @click="showLegalModal = false">Cerrar Visualización</Button>
            <Button variant="primary" @click="acceptLegalModal" icon="check_circle">He leído y Acepto</Button>
        </div>
    </Modal>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 24;
}
</style>
