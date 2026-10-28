<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Checkbox from '@/Components/UI/Checkbox.vue';
import Badge from '@/Components/UI/Badge.vue';
import UnitGeneratorModal from './Partials/UnitGeneratorModal.vue';
import UnitImportAuditModal from './Partials/UnitImportAuditModal.vue';
import { useToast } from '@/Composables/useToast';
import axios from 'axios';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    copropiedad: any;
    settings: any;
    componentes_catalogo: any[];
    is_super_admin: boolean;
}>();

const activeTab = ref(props.copropiedad.unit_types_locked ? 'nucleo' : 'hoja_vida');
const showGeneratorModal = ref(false);
const toast = useToast();

const form = useForm({
    area_construida_total: props.copropiedad.area_construida_total || '',
    settings: {
        payments_enabled: props.settings.payments_enabled ?? false,
        wompi_public_key: props.settings.wompi_public_key ?? '',
        wompi_private_key: props.settings.wompi_private_key ?? '',
        wompi_integrity_key: props.settings.wompi_integrity_key ?? '',
        wompi_webhook_secret: props.settings.wompi_webhook_secret ?? '',
        pqrs_enabled: props.settings.pqrs_enabled ?? true,
        reservas_enabled: props.settings.reservas_enabled ?? true,
        interes_mora: props.settings.interes_mora ?? 1.5,
        gateways: props.settings.gateways ?? {
            wompi: { enabled: false, label: 'Wompi (PSE, Tarjetas, Nequi)', type: 'gateway' },
            aval: { enabled: false, label: 'Aval Pay Center', type: 'redirect', url: '' },
            manual: { enabled: true, label: 'Efectivo / Consignación', type: 'instruction' }
        }
    }
});

const submit = () => {
    form.patch(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add('Núcleo de configuración actualizado', 'success');
        }
    });
};

// --- Lógica de Hoja de Vida y Catálogo Arquitectónico ---
const defaultCatalog = [
    { id: 'default-1', nombre: 'Habitación' },
    { id: 'default-2', nombre: 'Baño' },
    { id: 'default-3', nombre: 'Cocina' },
    { id: 'default-4', nombre: 'Sala' },
    { id: 'default-5', nombre: 'Comedor' },
    { id: 'default-6', nombre: 'Balcón' },
    { id: 'default-7', nombre: 'Terraza' },
    { id: 'default-8', nombre: 'Estudio' },
    { id: 'default-9', nombre: 'Zona de Ropas' },
    { id: 'default-10', nombre: 'Patio' },
    { id: 'default-11', nombre: 'Parqueadero' },
    { id: 'default-12', nombre: 'Depósito' }
];

const catalogoComponentes = computed(() => {
    if (props.componentes_catalogo && props.componentes_catalogo.length > 0) {
        return props.componentes_catalogo;
    }
    return defaultCatalog;
});

const customComponenteNombre = ref('');
const isAddingCustom = ref(false);

const addCustomComponent = () => {
    const cleanName = customComponenteNombre.value.trim();
    if (!cleanName) return;

    useForm({
        nombre: cleanName
    }).post(route('admin.componentes.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add(`Componente '${cleanName}' agregado al catálogo`, 'success');
            customComponenteNombre.value = '';
            isAddingCustom.value = false;
        }
    });
};

const tipoUnidadForm = useForm({
    nombre: '',
    area_m2: '',
    componentes: [] as any[]
});

const toggleComponente = (comp: any) => {
    const index = tipoUnidadForm.componentes.findIndex(c => c.id === comp.id);
    if (index === -1) {
        tipoUnidadForm.componentes.push({ id: comp.id, nombre: comp.nombre, cantidad: 1 });
    } else {
        tipoUnidadForm.componentes.splice(index, 1);
    }
};

const updateCantidad = (compId: string, delta: number) => {
    const comp = tipoUnidadForm.componentes.find(c => c.id === compId);
    if (comp) {
        comp.cantidad = Math.max(1, comp.cantidad + delta);
    }
};

const saveTipoUnidad = () => {
    if (!tipoUnidadForm.nombre.trim()) {
        toast.add('Por favor ingresa el nombre del modelo (Ej: Apartamento Tipo A).', 'error');
        return;
    }
    if (!tipoUnidadForm.area_m2 || Number(tipoUnidadForm.area_m2) <= 0) {
        toast.add('Por favor ingresa un área privada válida mayor a 0 m².', 'error');
        return;
    }
    if (tipoUnidadForm.componentes.length === 0) {
        toast.add('Debes seleccionar al menos un componente (ej: Habitación, Baño).', 'error');
        return;
    }

    tipoUnidadForm.post(route('admin.unit-types.store'), {
        onSuccess: () => {
            toast.add('Modelo de unidad registrado con éxito', 'success');
            tipoUnidadForm.reset();
        }
    });
};

const lockConfiguration = () => {
    showGeneratorModal.value = true;
};

const unlockConfiguration = () => {
    useForm({}).post(route('admin.unit-types.unlock'), {
        onSuccess: () => toast.add('Edición habilitada para Super Admin', 'warning')
    });
};

// --- Lógica de Onboarding con Plantilla Inteligente y Auditoría ---
const showImportModal = ref(false);
const selectedImportFile = ref<File | null>(null);
const previewImportResult = ref<any>(null);
const isAuditingFile = ref(false);
const isDraggingOver = ref(false);
const fileInputRef = ref<HTMLInputElement | null>(null);

const downloadTemplate = () => {
    if (!props.copropiedad.tipos_unidad || props.copropiedad.tipos_unidad.length === 0) {
        toast.add('Primero debes registrar al menos un modelo para generar la plantilla con opciones.', 'warning');
        return;
    }
    window.location.href = route('admin.settings.download_template');
};

const triggerFileInput = () => {
    if (fileInputRef.value) {
        fileInputRef.value.value = '';
        fileInputRef.value.click();
    }
};

const handleFileSelection = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        processImportFile(target.files[0]);
    }
};

const handleFileDrop = (event: DragEvent) => {
    isDraggingOver.value = false;
    if (event.dataTransfer?.files && event.dataTransfer.files[0]) {
        processImportFile(event.dataTransfer.files[0]);
    }
};

const processImportFile = async (file: File) => {
    selectedImportFile.value = file;
    isAuditingFile.value = true;
    showImportModal.value = true;
    previewImportResult.value = null;

    try {
        const formData = new FormData();
        formData.append('file', file);

        const response = await axios.post(route('admin.settings.preview_import'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        previewImportResult.value = response.data;
    } catch (err: any) {
        const errMsg = err.response?.data?.message || err.message || 'Error al procesar el archivo';
        toast.add(errMsg, 'error');
        showImportModal.value = false;
    } finally {
        isAuditingFile.value = false;
    }
};
</script>

<template>
    <Head title="Configuración — NEXO-PRO" />

    <div class="space-y-6 sm:space-y-10 md:space-y-12 pb-24 sm:pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Interactive Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 sm:gap-8 px-1">
            <div class="space-y-2 sm:space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-1.5 sm:w-2 h-5 sm:h-6 bg-primary rounded-full shadow-[0_0_10px_rgba(var(--primary),0.5)]"></div>
                    <p class="text-[9px] sm:text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] sm:tracking-[0.4em] italic leading-none">Dispatcher de Parámetros Globales</p>
                </div>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">Sala de <span class="text-primary italic">Máquinas</span></h2>
                <p class="text-[10px] sm:text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.15em] sm:tracking-[0.2em] mt-1 sm:mt-2 leading-relaxed italic">Gestión de llaves de integración, módulos y reglas financieras del conjunto</p>
            </div>
            
            <!-- Tab Switcher Premium -->
            <div class="flex bg-on-surface/5 dark:bg-white/5 p-1 sm:p-1.5 rounded-2xl border border-outline-variant/10 dark:border-white/5 backdrop-blur-xl w-full md:w-auto">
                <button 
                    @click="activeTab = 'nucleo'"
                    :class="activeTab === 'nucleo' ? 'bg-primary text-white shadow-lg' : 'text-on-surface-variant/60 dark:text-white/40 hover:text-on-surface dark:hover:text-white'"
                    class="flex-1 md:flex-none justify-center px-4 sm:px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 cursor-pointer"
                >
                    <span class="material-symbols-rounded text-sm">settings_input_component</span>
                    NÚCLEO
                </button>
                <button 
                    @click="activeTab = 'hoja_vida'"
                    :class="activeTab === 'hoja_vida' ? 'bg-primary text-white shadow-lg' : 'text-on-surface-variant/60 dark:text-white/40 hover:text-on-surface dark:hover:text-white'"
                    class="flex-1 md:flex-none justify-center px-4 sm:px-6 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 cursor-pointer"
                >
                    <span class="material-symbols-rounded text-sm">architecture</span>
                    HOJA DE VIDA
                </button>
            </div>
        </div>

        <!-- TAB: NUCLEO (Original Settings) -->
        <div v-if="activeTab === 'nucleo'" class="animate-in fade-in slide-in-from-right-4 duration-500">

        <form @submit.prevent="submit" class="grid grid-cols-1 xl:grid-cols-12 gap-6 sm:gap-10 items-start">
            
            <!-- SECTION 1: PAYMENTS & GATEWAYS -->
            <div class="xl:col-span-8 space-y-6 sm:space-y-10">
                
                <!-- Wompi Integration Card -->
                <Card class="p-5 sm:p-8 md:!p-10 rounded-[2.2rem] sm:rounded-[3rem] md:!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14]">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-10">
                        <div class="flex items-center gap-4 sm:gap-5">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner shrink-0">
                                <span class="material-symbols-rounded text-2xl">payments</span>
                            </div>
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Pasarela <span class="text-emerald-500">Wompi</span></h3>
                                <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] sm:tracking-[0.3em] mt-2 italic">Integración oficial para recaudo automatizado</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                             <div class="px-4 sm:px-6 py-2.5 sm:py-3 bg-emerald-500/10 rounded-2xl flex items-center gap-3 border border-emerald-500/20">
                                 <Checkbox v-model:checked="form.settings.payments_enabled" class="!w-5 !h-5 sm:!w-6 sm:!h-6" />
                                 <span class="text-[9px] sm:text-[10px] font-black text-emerald-500 uppercase tracking-widest">SISTEMA ACTIVO</span>
                             </div>
                        </div>
                    </div>

                    <div v-if="form.settings.payments_enabled" class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 md:gap-8 pt-6 border-t border-outline-variant/5 dark:border-white/5 animate-in slide-in-from-top-4 duration-500">
                        <Input
                            v-model="form.settings.wompi_public_key"
                            label="LLAVE PÚBLICA (PUB_TEST_...)"
                            placeholder="Ingrese su public key oficial"
                            :error="form.errors['settings.wompi_public_key']"
                            class="!rounded-2xl"
                        />
                        <Input
                            v-model="form.settings.wompi_integrity_key"
                            label="LLAVE DE INTEGRIDAD"
                            placeholder="Ingrese su integrity key"
                            :error="form.errors['settings.wompi_integrity_key']"
                            class="!rounded-2xl"
                        />
                        <Input
                            v-model="form.settings.wompi_private_key"
                            label="LLAVE PRIVADA (PRV_...)"
                            type="password"
                            placeholder="Ingrese su private key"
                            :error="form.errors['settings.wompi_private_key']"
                            class="!rounded-2xl"
                        />
                        <Input
                            v-model="form.settings.wompi_webhook_secret"
                            label="WEBHOOK SECRET"
                            type="password"
                            placeholder="Ingrese su webhook secret"
                            :error="form.errors['settings.wompi_webhook_secret']"
                            class="!rounded-2xl"
                        />
                        
                        <div class="col-span-2 p-6 bg-primary/5 rounded-[2rem] border border-primary/20 flex gap-4 text-primary relative overflow-hidden group">
                            <div class="absolute inset-0 bg-primary/5 translate-x-full group-hover:translate-x-0 transition-transform duration-[2s]"></div>
                            <span class="material-symbols-rounded shrink-0 relative z-10">info</span>
                            <div class="relative z-10 space-y-2">
                                <p class="text-[11px] font-black uppercase tracking-widest leading-none">Protocolo de Redirección Webhook</p>
                                <p class="text-[9px] font-medium leading-relaxed italic">
                                    Enlace obligatorio para Dash de Wompi: 
                                    <span class="bg-primary/10 px-2 py-0.5 rounded italic select-all cursor-pointer">https://{{ $page.props.auth.user.current_copropiedad?.domain || 'nexo-pro.io' }}/payments/webhook</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="py-12 text-center opacity-30 border-t border-outline-variant/10 dark:border-white/5 mt-6">
                        <span class="material-symbols-rounded text-6xl">lock_open</span>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] mt-4">Integración de Pagos Desactivada</p>
                    </div>
                </Card>

                <!-- Gateways Monitor -->
                <Card v-if="form.settings.payments_enabled" class="!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] overflow-hidden">
                    <template #header>
                        <div class="p-10 border-b border-outline-variant/5 dark:border-white/5 flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Métodos de <span class="text-primary">Fondeo</span></h3>
                                <p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Gestión de canales de pago visibles para el residente</p>
                            </div>
                        </div>
                    </template>

                    <div class="divide-y divide-outline-variant/5 dark:divide-white/5">
                        <div v-for="(gateway, key) in form.settings.gateways" :key="key" class="p-8 hover:bg-surface-container-low dark:hover:bg-white/5 transition-all flex flex-col md:flex-row md:items-center justify-between gap-6 group">
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center text-on-surface-variant border border-outline-variant/10 group-hover:bg-primary group-hover:text-white transition-all shadow-xl shadow-black/5">
                                    <span class="material-symbols-rounded text-2xl">{{ key === 'wompi' ? 'account_balance' : (key === 'aval' ? 'account_balance_wallet' : 'payments') }}</span>
                                </div>
                                <div>
                                    <h4 class="text-base font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">{{ gateway.label }}</h4>
                                    <p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-2 italic">{{ key === 'manuel' ? 'REGISTRO DE SOPORTE FÍSICO' : 'CANAL DE PAGO DIGITAL' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-8">
                                <div v-if="gateway.enabled && key === 'aval'" class="animate-in slide-in-from-right-4">
                                    <Input
                                        v-model="gateway.url"
                                        label="URL POINT-OF-SALE"
                                        placeholder="https://..."
                                        :error="form.errors[`settings.gateways.${key}.url`]"
                                        class="!rounded-xl !h-12 !w-64"
                                    />
                                </div>
                                <div class="flex items-center gap-4 bg-surface-container-high dark:bg-white/5 px-6 py-4 rounded-2xl border border-outline-variant/10 group-hover:border-primary/20 transition-all">
                                    <span class="text-[9px] font-black uppercase tracking-widest" :class="gateway.enabled ? 'text-primary' : 'text-on-surface-variant/40'">{{ gateway.enabled ? 'HABILITADO' : 'INACTIVO' }}</span>
                                    <Checkbox v-model:checked="gateway.enabled" class="!w-6 !h-6" />
                                </div>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- SECTION 2: MODULES & GLOBAL RULES -->
            <div class="xl:col-span-4 space-y-6 sm:space-y-10">
                
                <!-- Modules Control -->
                <Card class="p-5 sm:p-8 md:!p-10 rounded-[2.2rem] sm:rounded-[3rem] md:!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]">
                    <h3 class="text-lg sm:text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none mb-6 sm:mb-8">Hardware de <span class="text-primary italic">Módulos</span></h3>
                    
                    <div class="space-y-4 sm:space-y-6">
                        <div class="flex items-center justify-between p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all group">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                                    <span class="material-symbols-rounded text-lg sm:text-xl">forum</span>
                                </div>
                                <span class="text-[10px] sm:text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest italic group-hover:text-primary transition-colors">Integración PQRS</span>
                            </div>
                            <Checkbox v-model:checked="form.settings.pqrs_enabled" />
                        </div>

                        <div class="flex items-center justify-between p-4 sm:p-6 rounded-2xl sm:rounded-[2rem] bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all group">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                                    <span class="material-symbols-rounded text-lg sm:text-xl">event_available</span>
                                </div>
                                <span class="text-[10px] sm:text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest italic group-hover:text-primary transition-colors">Sistema Reservas</span>
                            </div>
                            <Checkbox v-model:checked="form.settings.reservas_enabled" />
                        </div>
                    </div>

                    <!-- Market Rules -->
                    <div class="mt-8 sm:mt-12 pt-6 sm:pt-8 border-t border-outline-variant/5 dark:border-white/5 space-y-4 sm:space-y-6">
                        <div class="flex items-end gap-3 sm:gap-4">
                            <div class="flex-1">
                                <Input
                                    v-model="form.settings.interes_mora"
                                    type="number"
                                    step="0.01"
                                    label="COEFICIENTE DE MORA (%)"
                                    suffix="%"
                                    class="!rounded-2xl"
                                />
                            </div>
                            <div class="w-12 h-14 bg-surface-container-high dark:bg-white/5 flex items-center justify-center rounded-2xl border border-outline-variant/10 shrink-0">
                                <span class="material-symbols-rounded text-xl opacity-20">trending_up</span>
                            </div>
                        </div>
                        <p class="text-[8px] sm:text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest leading-relaxed italic px-1">
                            * Este vector porcentual afectará automáticamente los balances financieros al cierre de cada ciclo.
                        </p>
                    </div>
                </Card>

                <!-- Action Hub -->
                <div class="bg-surface-container-highest dark:bg-white/5 p-6 sm:p-8 md:p-10 rounded-[2.2rem] sm:rounded-[3rem] md:rounded-[3.5rem] border border-outline-variant/10 flex flex-col gap-4 sm:gap-6">
                    <h4 class="text-xs font-black text-on-surface dark:text-white uppercase tracking-[0.3em] text-center italic">Despachador Maestro</h4>
                    <Button 
                        type="submit" 
                        variant="primary" 
                        size="lg" 
                        class="w-full !h-14 sm:!h-16 !rounded-2xl !text-[12px] font-black uppercase italic shadow-2xl shadow-primary/20 cursor-pointer"
                        :loading="form.processing"
                        icon="save"
                    >
                        {{ form.processing ? 'SINCRONIZANDO...' : 'Sincronizar Kernels' }}
                    </Button>
                    <p class="text-[8px] font-bold text-on-surface-variant/30 dark:text-white/10 uppercase tracking-widest text-center mt-2 leading-relaxed">
                        LOS CAMBIOS SON APLICADOS DE FORMA PERMANENTE EN EL NÚCLEO DE LA COPROPIEDAD. VERIFIQUE CADA LLAVE DE API.
                    </p>
                </div>
            </div>
        </form>
    </div>

        <!-- TAB: HOJA DE VIDA (Unit Types) -->
        <div v-if="activeTab === 'hoja_vida'" class="animate-in fade-in slide-in-from-left-4 duration-500 space-y-6 sm:space-y-10">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 sm:gap-10">
                
                <!-- Setup Form -->
                <div class="xl:col-span-7 space-y-6 sm:space-y-8">
                    <Card class="p-5 sm:p-8 md:!p-10 rounded-[2.2rem] sm:rounded-[3rem] md:!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:shadow-3xl bg-surface dark:bg-[#0b0e14] relative overflow-hidden">
                        <div v-if="copropiedad.unit_types_locked && !is_super_admin" class="absolute inset-0 z-20 bg-surface/80 dark:bg-black/80 backdrop-blur-md flex flex-col items-center justify-center text-center p-6 sm:p-10">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-on-surface/5 dark:bg-white/5 rounded-full flex items-center justify-center mb-4 sm:mb-6 border border-outline-variant/10 dark:border-white/10">
                                <span class="material-symbols-rounded text-3xl sm:text-4xl text-primary">lock</span>
                            </div>
                            <h4 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic">Configuración Bloqueada</h4>
                            <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/60 dark:text-white/40 uppercase tracking-widest mt-2 sm:mt-4 max-w-xs leading-relaxed">
                                Los modelos de unidad han sido certificados. Cualquier cambio debe ser solicitado al Super Administrador.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 sm:mb-8">
                            <div>
                                <h3 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Definir <span class="text-primary">Modelo</span></h3>
                                <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] sm:tracking-[0.3em] mt-2 italic">Registro de tipos de unidad por medidas y componentes</p>
                            </div>
                            <Badge :variant="copropiedad.unit_types_locked ? 'primary' : 'warning'" class="!rounded-xl !px-3.5 !py-1.5 uppercase font-black text-[9px] tracking-widest italic w-fit">
                                {{ copropiedad.unit_types_locked ? '🔒 CONFIGURACIÓN BLINDADA' : '🔓 EDICIÓN ABIERTA' }}
                            </Badge>
                        </div>

                        <div class="mb-6 sm:mb-8 p-4 sm:p-6 bg-surface-container-low dark:bg-white/[0.02] rounded-2xl sm:rounded-[2rem] border border-outline-variant/10 dark:border-white/5 shadow-sm">
                            <h4 class="text-xs sm:text-sm font-black text-on-surface dark:text-white uppercase tracking-tighter mb-3 sm:mb-4 italic">Datos Base del Conjunto</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <Input 
                                    v-model="form.area_construida_total" 
                                    type="number" 
                                    step="0.01" 
                                    label="ÁREA CONSTRUIDA TOTAL (M2)" 
                                    placeholder="Ej: 15200.50" 
                                    class="!rounded-2xl" 
                                />
                                <div class="flex items-end">
                                    <Button @click="submit" variant="primary" size="lg" :loading="form.processing" class="w-full !rounded-2xl !h-12 cursor-pointer font-black text-xs uppercase">
                                        Guardar Área Total
                                    </Button>
                                </div>
                            </div>
                            <p class="text-[8px] sm:text-[9px] font-bold text-on-surface-variant/50 uppercase tracking-widest mt-2 sm:mt-3">
                                Este valor es la base matemática para el cálculo automático de los coeficientes de copropiedad.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                            <Input v-model="tipoUnidadForm.nombre" label="NOMBRE DEL MODELO" placeholder="Ej: Apartamento Tipo A" class="!rounded-2xl" />
                            <Input v-model="tipoUnidadForm.area_m2" type="number" step="0.01" label="ÁREA PRIVADA (M2)" placeholder="0.00" class="!rounded-2xl" />
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <p class="text-[10px] font-black text-primary uppercase tracking-[0.3em]">Selección de Componentes</p>
                                <button 
                                    @click="isAddingCustom = !isAddingCustom"
                                    type="button"
                                    class="text-[9px] font-bold text-primary hover:underline uppercase tracking-widest flex items-center gap-1 cursor-pointer"
                                >
                                    <span class="material-symbols-rounded text-sm">{{ isAddingCustom ? 'close' : 'add_circle' }}</span>
                                    {{ isAddingCustom ? 'Cerrar' : '+ Personalizar Componente' }}
                                </button>
                            </div>

                            <!-- Custom Component Quick Add -->
                            <div v-if="isAddingCustom" class="p-4 bg-surface-container-high/40 rounded-2xl border border-primary/20 flex gap-3 items-center animate-in fade-in duration-200">
                                <input 
                                    v-model="customComponenteNombre"
                                    type="text" 
                                    placeholder="Ej: Jacuzzi, Altillo, Depósito Doble"
                                    class="flex-1 bg-surface-container px-4 py-2.5 rounded-xl text-xs font-bold text-on-surface border border-outline-variant/20 focus:outline-none focus:border-primary"
                                    @keyup.enter="addCustomComponent"
                                />
                                <Button @click="addCustomComponent" variant="primary" size="sm" class="!rounded-xl cursor-pointer">
                                    Añadir
                                </Button>
                            </div>

                            <!-- Catalogo Pills -->
                            <div class="flex flex-wrap gap-3">
                                <button 
                                    v-for="comp in catalogoComponentes" 
                                    :key="comp.id"
                                    @click="toggleComponente(comp)"
                                    type="button"
                                    :class="tipoUnidadForm.componentes.some(c => c.id === comp.id) ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20 ring-2 ring-primary/30' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant/80 dark:text-white/60 border-outline-variant/10 dark:border-white/5 hover:border-primary/50'"
                                    class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all cursor-pointer select-none active:scale-95"
                                >
                                    {{ comp.nombre }}
                                </button>
                            </div>

                            <!-- Quantity Adjusters -->
                            <div v-if="tipoUnidadForm.componentes.length > 0" class="space-y-3 mt-6">
                                <p class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest">Cantidades por Componente Seleccionado</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div v-for="comp in tipoUnidadForm.componentes" :key="comp.id" class="flex items-center justify-between p-4 bg-surface-container-lowest dark:bg-white/[0.02] rounded-2xl border border-outline-variant/10 dark:border-white/5 shadow-sm animate-in zoom-in-95 duration-300">
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-primary"></span>
                                            <span class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-wider">{{ comp.nombre }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <button @click="updateCantidad(comp.id, -1)" type="button" class="w-8 h-8 rounded-lg bg-surface-container-high hover:bg-primary hover:text-white text-on-surface font-black transition-all flex items-center justify-center cursor-pointer">-</button>
                                            <span class="text-sm font-black text-on-surface dark:text-white w-6 text-center tabular-nums">{{ comp.cantidad }}</span>
                                            <button @click="updateCantidad(comp.id, 1)" type="button" class="w-8 h-8 rounded-lg bg-surface-container-high hover:bg-primary hover:text-white text-on-surface font-black transition-all flex items-center justify-center cursor-pointer">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-12 flex flex-col md:flex-row items-center gap-4">
                            <Button @click="saveTipoUnidad" variant="primary" size="lg" :loading="tipoUnidadForm.processing" icon="send" class="w-full md:w-auto" :disabled="copropiedad.unit_types_locked && !is_super_admin">
                                REGISTRAR MODELO
                            </Button>
                            
                            <!-- Security Actions (Moved here for visibility) -->
                            <div v-if="!copropiedad.unit_types_locked" class="flex-1 w-full">
                                <Button @click="lockConfiguration" variant="primary" outline class="w-full !rounded-2xl !h-14 font-black tracking-widest border-2" icon="precision_manufacturing">
                                    LANZAR MOTOR DE NOMENCLATURA
                                </Button>
                            </div>

                            <div v-if="copropiedad.unit_types_locked && is_super_admin" class="flex-1 w-full">
                                <Button @click="unlockConfiguration" variant="danger" outline class="w-full !rounded-2xl !h-14 font-black tracking-widest border-2" icon="lock_open">
                                    DESBLOQUEAR (SUPER ADMIN)
                                </Button>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Summary & List -->
                <div class="xl:col-span-5 space-y-6 sm:space-y-8">
                    <!-- Modelos Certificados -->
                    <Card class="p-5 sm:p-8 md:!p-10 rounded-[2.2rem] sm:rounded-[3rem] md:!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl bg-surface dark:bg-[#0b0e14]">
                        <h3 class="text-lg sm:text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none mb-6 sm:mb-8">Modelos <span class="text-primary">Certificados</span></h3>
                        
                        <div class="space-y-3 sm:space-y-4">
                            <div v-for="tipo in copropiedad.tipos_unidad" :key="tipo.id" class="p-4 sm:p-6 rounded-2xl sm:rounded-[2.5rem] bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 group hover:border-primary/30 transition-all shadow-sm">
                                <div class="flex items-center justify-between mb-3 sm:mb-4">
                                    <h4 class="text-sm sm:text-base font-black text-on-surface dark:text-white uppercase tracking-tighter italic truncate">{{ tipo.nombre }}</h4>
                                    <Badge variant="primary" class="!rounded-lg shrink-0 text-[9px]">{{ tipo.area_m2 }} M2</Badge>
                                </div>
                                <div class="flex flex-wrap gap-1.5 sm:gap-2">
                                    <span v-for="comp in tipo.componentes" :key="comp.id" class="px-2.5 sm:px-3 py-1 bg-on-surface/5 dark:bg-white/5 rounded-lg text-[8px] font-bold text-on-surface-variant/60 dark:text-white/40 uppercase tracking-widest">
                                        {{ comp.pivot.cantidad }} {{ comp.nombre }}
                                    </span>
                                </div>
                            </div>

                            <div v-if="!copropiedad.tipos_unidad?.length" class="py-8 sm:py-12 text-center opacity-40 dark:opacity-20 italic text-xs sm:text-sm text-on-surface dark:text-white">
                                No hay modelos registrados aún
                            </div>
                        </div>
                    </Card>

                    <!-- STEP 2: CENSO & CARGA INTELIGENTE CON EXCEL -->
                    <Card class="p-5 sm:p-8 md:!p-10 rounded-[2.2rem] sm:rounded-[3rem] md:!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl bg-surface dark:bg-[#0b0e14] relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4 sm:mb-6">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="px-2.5 py-0.5 rounded-lg bg-primary/10 text-primary text-[8px] sm:text-[9px] font-black tracking-widest uppercase">Paso 2</span>
                                    <span class="text-[8px] sm:text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest">Censo de Unidades</span>
                                </div>
                                <h3 class="text-lg sm:text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">
                                    Censo & <span class="text-primary">Carga Masiva</span>
                                </h3>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-rounded text-xl sm:text-2xl">table_view</span>
                            </div>
                        </div>

                        <p class="text-[11px] font-medium text-on-surface-variant/70 dark:text-white/40 leading-relaxed mb-6">
                            Descarga la plantilla con lista desplegable de tus modelos, completa tu censo de apartamentos y cárgalo para certificar la copropiedad.
                        </p>

                        <!-- Estado cuando aún no hay modelos -->
                        <div v-if="!copropiedad.tipos_unidad?.length" class="p-6 rounded-3xl bg-amber-500/10 border border-amber-500/20 text-amber-700 dark:text-amber-300 text-xs font-medium space-y-2">
                            <div class="flex items-center gap-2 font-black uppercase text-[10px] tracking-wider">
                                <span class="material-symbols-rounded text-base">info</span>
                                Catálogo Vacío
                            </div>
                            <p class="text-[11px] leading-relaxed opacity-90">
                                Registra primero al menos un modelo arquitectónico (ej: Apartamento Tipo A) en el formulario para habilitar la plantilla oficial.
                            </p>
                        </div>

                        <!-- Cuando ya existen modelos certificados -->
                        <div v-else class="space-y-4 sm:space-y-6">
                            <!-- Botón de Descarga de Plantilla -->
                            <Button 
                                @click="downloadTemplate" 
                                variant="primary" 
                                size="lg" 
                                class="w-full !rounded-2xl !h-12 sm:!h-14 font-black uppercase tracking-wider shadow-lg shadow-primary/20 flex items-center justify-center gap-2 cursor-pointer text-xs"
                                icon="download"
                            >
                                DESCARGAR PLANTILLA (.XLSX)
                            </Button>
                            <p class="text-[8px] sm:text-[9px] font-bold text-center text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest">
                                * Incluye tus {{ copropiedad.tipos_unidad.length }} modelos en menú desplegable
                            </p>

                            <!-- Zona Drag & Drop -->
                            <div 
                                @dragover.prevent="isDraggingOver = true"
                                @dragleave.prevent="isDraggingOver = false"
                                @drop.prevent="handleFileDrop"
                                @click="triggerFileInput"
                                :class="[
                                    isDraggingOver ? 'border-primary bg-primary/10 scale-[1.01]' : 'border-outline-variant/20 dark:border-white/10 hover:border-primary/50 bg-surface-container-low dark:bg-white/[0.02]',
                                    copropiedad.unit_types_locked && !is_super_admin ? 'opacity-50 pointer-events-none' : 'cursor-pointer'
                                ]"
                                class="p-5 sm:p-8 rounded-2xl sm:rounded-[2.5rem] border-2 border-dashed transition-all flex flex-col items-center justify-center text-center group active:scale-[0.99]"
                            >
                                <input 
                                    ref="fileInputRef" 
                                    type="file" 
                                    accept=".xlsx,.xls,.csv" 
                                    class="hidden" 
                                    @change="handleFileSelection"
                                />
                                
                                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-2xl bg-primary/10 group-hover:bg-primary group-hover:text-white text-primary flex items-center justify-center mb-2.5 sm:mb-3 transition-all">
                                    <span class="material-symbols-rounded text-xl sm:text-2xl">cloud_upload</span>
                                </div>
                                
                                <p class="text-xs font-black text-on-surface dark:text-white uppercase tracking-wider mb-0.5 sm:mb-1">
                                    Arrastra tu censo aquí
                                </p>
                                <p class="text-[8px] sm:text-[9px] font-bold text-on-surface-variant/50 uppercase tracking-widest">
                                    O haz clic para examinar (.xlsx, .xls, .csv)
                                </p>
                            </div>

                            <!-- Alternativa Secuencial -->
                            <div class="pt-3 sm:pt-4 border-t border-outline-variant/10 dark:border-white/5 flex items-center justify-between">
                                <span class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-wider">
                                    ¿Conjunto homogéneo?
                                </span>
                                <button 
                                    @click="lockConfiguration"
                                    type="button" 
                                    class="text-[9px] sm:text-[10px] font-black text-primary hover:underline uppercase tracking-widest flex items-center gap-1 cursor-pointer"
                                    :disabled="copropiedad.unit_types_locked && !is_super_admin"
                                >
                                    <span class="material-symbols-rounded text-sm">precision_manufacturing</span>
                                    Generador Secuencial
                                </button>
                            </div>
                        </div>
                    </Card>
                </div>
            </div>
        </div>
    </div>
    
    <UnitGeneratorModal 
        :show="showGeneratorModal" 
        @close="showGeneratorModal = false"
        @success="() => { showGeneratorModal = false; activeTab = 'nucleo'; toast.success('Unidades generadas exitosamente'); }"
    />

    <UnitImportAuditModal 
        :show="showImportModal"
        :result="previewImportResult"
        :file="selectedImportFile"
        @close="showImportModal = false"
        @success="() => { showImportModal = false; activeTab = 'nucleo'; }"
    />
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
