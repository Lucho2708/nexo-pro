<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
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

const page = usePage<any>();

const props = defineProps<{
    copropiedad: any;
    settings: any;
    componentes_catalogo: any[];
    is_super_admin: boolean;
    metrics?: {
        total_unidades: number;
        total_modelos: number;
        area_construida_total: number;
        area_privada_total: number;
        unit_types_locked: boolean;
        payments_enabled: boolean;
    };
}>();

const activeTab = ref(props.copropiedad.unit_types_locked ? 'nucleo' : 'hoja_vida');
const showGeneratorModal = ref(false);
const toast = useToast();

// Visibility toggles for sensitive gateway credentials
const showPrivateKey = ref(false);
const showWebhookSecret = ref(false);
const copiedWebhook = ref(false);

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
            toast.add('Núcleo de configuración actualizado exitosamente', 'success');
        },
        onError: () => {
            toast.add('Hubo un error al actualizar los parámetros', 'error');
        }
    });
};

// Copy Webhook URL to clipboard with feedback
const copyWebhookUrl = () => {
    const domain = page.props.auth?.user?.current_copropiedad?.domain || (typeof window !== 'undefined' ? window.location.host : 'nexo-pro.com.co');
    const url = `https://${domain}/payments/webhook`;

    if (navigator?.clipboard?.writeText) {
        navigator.clipboard.writeText(url).then(() => {
            copiedWebhook.value = true;
            toast.add('URL de Webhook copiada al portapapeles', 'success');
            setTimeout(() => { copiedWebhook.value = false; }, 2500);
        }).catch(() => {
            toast.add('URL: ' + url, 'info');
        });
    } else {
        toast.add('URL: ' + url, 'info');
    }
};

// Tactical Computed Metrics
const computedMetrics = computed(() => {
    return {
        total_unidades: props.metrics?.total_unidades ?? (props.copropiedad.unidades?.length || 0),
        total_modelos: props.metrics?.total_modelos ?? (props.copropiedad.tipos_unidad?.length || 0),
        area_construida: Number(props.metrics?.area_construida_total ?? props.copropiedad.area_construida_total ?? 0),
        area_privada: Number(props.metrics?.area_privada_total ?? 0),
        locked: props.metrics?.unit_types_locked ?? Boolean(props.copropiedad.unit_types_locked),
        payments: props.metrics?.payments_enabled ?? Boolean(form.settings.payments_enabled),
    };
});

// Quick set mora rate helper
const setMoraRate = (rate: number) => {
    form.settings.interes_mora = rate;
};

// Keyboard shortcut (Ctrl+S / Cmd+S)
const handleKeydown = (e: KeyboardEvent) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        submit();
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});

// --- Architectural Components and Models ---
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
        preserveScroll: true,
        onSuccess: () => {
            toast.add('Modelo de unidad registrado con éxito', 'success');
            tipoUnidadForm.reset();
            tipoUnidadForm.componentes = [];
        }
    });
};

const deleteTipoUnidad = (tipo: any) => {
    if (props.copropiedad.unit_types_locked && !props.is_super_admin) {
        toast.add('Los modelos están blindados. Solicite desbloqueo a Super Admin.', 'warning');
        return;
    }

    if (confirm(`¿Estás seguro de eliminar el modelo '${tipo.nombre}'? Esta acción desacoplará sus componentes.`)) {
        useForm({}).delete(route('admin.unit-types.destroy', tipo.id), {
            preserveScroll: true,
            onSuccess: () => {
                toast.add(`Modelo '${tipo.nombre}' eliminado correctamente`, 'success');
            },
            onError: (err: any) => {
                const message = err.message || 'No se pudo eliminar el modelo. Verifique que no tenga unidades asignadas.';
                toast.add(message, 'error');
            }
        });
    }
};

const lockConfiguration = () => {
    showGeneratorModal.value = true;
};

const unlockConfiguration = () => {
    useForm({}).post(route('admin.unit-types.unlock'), {
        onSuccess: () => toast.add('Edición habilitada para Super Administrador', 'warning')
    });
};

// --- Census Smart Template & Drag and Drop Audit ---
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
    <Head title="Configuración de Copropiedad — NEXO-PRO" />

    <div class="space-y-6 sm:space-y-8 md:space-y-10 pb-28 sm:pb-32 animate-in fade-in duration-500">
        
        <!-- Interactive Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-5 sm:gap-8 px-1">
            <div class="space-y-2">
                <div class="flex items-center gap-2">
                    <div class="w-2 h-5 bg-primary rounded-full shadow-[0_0_12px_rgba(var(--primary),0.6)]"></div>
                    <p class="text-[9px] sm:text-[10px] font-black text-on-surface-variant/50 dark:text-white/40 uppercase tracking-[0.25em] italic leading-none">
                        Dispatcher de Parámetros Globales
                    </p>
                </div>
                <h1 class="text-2xl sm:text-4xl md:text-5xl font-black text-on-surface dark:text-white tracking-tight uppercase italic leading-none">
                    Configuración <span class="text-primary italic">& Núcleo</span>
                </h1>
                <p class="text-[10px] sm:text-[11px] font-bold text-on-surface-variant/60 dark:text-white/40 uppercase tracking-wider mt-1 leading-relaxed">
                    Integraciones de pasarela, catálogo de modelos arquitectónicos y reglas operativas
                </p>
            </div>
            
            <!-- Modern Tab Switcher (Touch friendly >= 48px) -->
            <div class="flex bg-surface-container-high/60 dark:bg-white/5 p-1.5 rounded-2xl border border-outline-variant/15 dark:border-white/10 backdrop-blur-xl w-full md:w-auto shadow-sm">
                <button 
                    @click="activeTab = 'nucleo'"
                    :class="activeTab === 'nucleo' ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-[1.02]' : 'text-on-surface-variant/70 dark:text-white/60 hover:text-on-surface hover:bg-surface-container-highest/50'"
                    class="flex-1 md:flex-none justify-center px-4 sm:px-6 py-3 rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 min-h-[48px] cursor-pointer select-none active:scale-95"
                    role="tab"
                    :aria-selected="activeTab === 'nucleo'"
                >
                    <span class="material-symbols-rounded text-lg">settings_input_component</span>
                    <span>NÚCLEO & REGLAS</span>
                </button>
                <button 
                    @click="activeTab = 'hoja_vida'"
                    :class="activeTab === 'hoja_vida' ? 'bg-primary text-white shadow-lg shadow-primary/20 scale-[1.02]' : 'text-on-surface-variant/70 dark:text-white/60 hover:text-on-surface hover:bg-surface-container-highest/50'"
                    class="flex-1 md:flex-none justify-center px-4 sm:px-6 py-3 rounded-xl text-[10px] sm:text-xs font-black uppercase tracking-wider transition-all flex items-center gap-2 min-h-[48px] cursor-pointer select-none active:scale-95"
                    role="tab"
                    :aria-selected="activeTab === 'hoja_vida'"
                >
                    <span class="material-symbols-rounded text-lg">architecture</span>
                    <span>HOJA DE VIDA & CENSO</span>
                </button>
            </div>
        </div>

        <!-- Tactical Metrics Grid (4 Live KPIs) -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
            <!-- Unidades Registradas -->
            <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-surface dark:bg-[#0b0e14] border border-outline-variant/15 dark:border-white/5 shadow-sm hover:border-primary/30 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-on-surface-variant/60 dark:text-white/40">Unidades</span>
                    <div class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-base">apartment</span>
                    </div>
                </div>
                <div>
                    <p class="text-xl sm:text-2xl md:text-3xl font-black text-on-surface dark:text-white tracking-tight">
                        {{ computedMetrics.total_unidades }}
                    </p>
                    <p class="text-[8px] sm:text-[9px] font-bold text-on-surface-variant/40 dark:text-white/30 uppercase tracking-widest mt-0.5">
                        Registradas en censo
                    </p>
                </div>
            </div>

            <!-- Modelos Arquitectónicos -->
            <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-surface dark:bg-[#0b0e14] border border-outline-variant/15 dark:border-white/5 shadow-sm hover:border-indigo-500/30 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-on-surface-variant/60 dark:text-white/40">Modelos</span>
                    <div class="w-8 h-8 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-base">architecture</span>
                    </div>
                </div>
                <div>
                    <p class="text-xl sm:text-2xl md:text-3xl font-black text-on-surface dark:text-white tracking-tight">
                        {{ computedMetrics.total_modelos }}
                    </p>
                    <p class="text-[8px] sm:text-[9px] font-bold text-on-surface-variant/40 dark:text-white/30 uppercase tracking-widest mt-0.5">
                        Catálogo certificado
                    </p>
                </div>
            </div>

            <!-- Área Privada Auditada -->
            <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-surface dark:bg-[#0b0e14] border border-outline-variant/15 dark:border-white/5 shadow-sm hover:border-amber-500/30 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-on-surface-variant/60 dark:text-white/40">Área Privada</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center shrink-0">
                        <span class="material-symbols-rounded text-base">straighten</span>
                    </div>
                </div>
                <div>
                    <p class="text-lg sm:text-2xl md:text-3xl font-black text-on-surface dark:text-white tracking-tight truncate">
                        {{ computedMetrics.area_privada.toLocaleString('es-CO', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) }} <span class="text-xs sm:text-sm font-bold text-on-surface-variant/50">m²</span>
                    </p>
                    <p class="text-[8px] sm:text-[9px] font-bold text-on-surface-variant/40 dark:text-white/30 uppercase tracking-widest mt-0.5">
                        Suma en unidades
                    </p>
                </div>
            </div>

            <!-- Estado del Núcleo -->
            <div class="p-4 sm:p-5 rounded-2xl sm:rounded-3xl bg-surface dark:bg-[#0b0e14] border border-outline-variant/15 dark:border-white/5 shadow-sm hover:border-emerald-500/30 transition-all flex flex-col justify-between">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-[9px] sm:text-[10px] font-black uppercase tracking-wider text-on-surface-variant/60 dark:text-white/40">Inmutabilidad</span>
                    <div 
                        class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0"
                        :class="computedMetrics.locked ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500'"
                    >
                        <span class="material-symbols-rounded text-base">{{ computedMetrics.locked ? 'lock' : 'lock_open' }}</span>
                    </div>
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span 
                            class="w-2 h-2 rounded-full animate-pulse"
                            :class="computedMetrics.locked ? 'bg-emerald-500' : 'bg-amber-500'"
                        ></span>
                        <p 
                            class="text-sm sm:text-base md:text-lg font-black uppercase tracking-tight"
                            :class="computedMetrics.locked ? 'text-emerald-500' : 'text-amber-500'"
                        >
                            {{ computedMetrics.locked ? 'BLINDADO' : 'ABIERTO' }}
                        </p>
                    </div>
                    <p class="text-[8px] sm:text-[9px] font-bold text-on-surface-variant/40 dark:text-white/30 uppercase tracking-widest mt-0.5">
                        {{ computedMetrics.locked ? 'Certificación activa' : 'Edición permitida' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- TAB 1: NUCLEO (Settings, Wompi, Gateways)  -->
        <!-- ========================================== -->
        <div v-if="activeTab === 'nucleo'" class="animate-in fade-in duration-300">
            <form @submit.prevent="submit" class="grid grid-cols-1 xl:grid-cols-12 gap-6 sm:gap-8 items-start">
                
                <!-- LEFT COLUMN: INTEGRATIONS & GATEWAYS -->
                <div class="xl:col-span-8 space-y-6 sm:space-y-8">
                    
                    <!-- Wompi Integration Card -->
                    <Card class="p-5 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] border border-outline-variant/15 dark:border-white/5 shadow-xl bg-surface dark:bg-[#0b0e14]">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 pb-6 border-b border-outline-variant/10 dark:border-white/5">
                            <div class="flex items-center gap-3.5 sm:gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 shrink-0">
                                    <span class="material-symbols-rounded text-2xl">payments</span>
                                </div>
                                <div>
                                    <h2 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tight leading-none">
                                        Pasarela <span class="text-emerald-500">Wompi</span>
                                    </h2>
                                    <p class="text-[10px] font-bold text-on-surface-variant/50 dark:text-white/30 uppercase tracking-widest mt-1">
                                        Integración oficial para recaudo automatizado (PSE, Nequi, Tarjetas)
                                    </p>
                                </div>
                            </div>
                            
                            <!-- Toggle switch -->
                            <div class="flex items-center gap-3 px-4 py-2.5 rounded-2xl bg-surface-container-high/60 dark:bg-white/5 border border-outline-variant/15 dark:border-white/10 self-start sm:self-auto">
                                <Checkbox v-model:checked="form.settings.payments_enabled" class="!w-5 !h-5" id="payments-enabled-toggle" />
                                <label for="payments-enabled-toggle" class="text-[10px] font-black uppercase tracking-wider cursor-pointer" :class="form.settings.payments_enabled ? 'text-emerald-500' : 'text-on-surface-variant/60'">
                                    {{ form.settings.payments_enabled ? 'SISTEMA ACTIVO' : 'DESACTIVADO' }}
                                </label>
                            </div>
                        </div>

                        <!-- Credentials Form (when enabled) -->
                        <div v-if="form.settings.payments_enabled" class="space-y-5 animate-in fade-in duration-300">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <Input
                                    v-model="form.settings.wompi_public_key"
                                    label="LLAVE PÚBLICA (PUB_TEST_... O PUB_PROD_...)"
                                    placeholder="pub_test_xxxxxxxxxxxxxxxxxxxxxxxx"
                                    :error="form.errors['settings.wompi_public_key']"
                                    class="!rounded-2xl"
                                />
                                <Input
                                    v-model="form.settings.wompi_integrity_key"
                                    label="LLAVE DE INTEGRIDAD"
                                    placeholder="prod_integrity_xxxxxxxxxxxxxxxx"
                                    :error="form.errors['settings.wompi_integrity_key']"
                                    class="!rounded-2xl"
                                />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                <!-- Llave Privada with Show/Hide Eye Toggle -->
                                <div class="relative">
                                    <Input
                                        v-model="form.settings.wompi_private_key"
                                        label="LLAVE PRIVADA (PRV_...)"
                                        :type="showPrivateKey ? 'text' : 'password'"
                                        placeholder="prv_test_xxxxxxxxxxxxxxxx"
                                        :error="form.errors['settings.wompi_private_key']"
                                        class="!rounded-2xl pr-12"
                                    />
                                    <button 
                                        type="button"
                                        @click="showPrivateKey = !showPrivateKey"
                                        class="absolute right-3.5 top-9 w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant/60 hover:text-on-surface hover:bg-surface-container transition-all cursor-pointer"
                                        :title="showPrivateKey ? 'Ocultar llave' : 'Revelar llave'"
                                        aria-label="Alternar visibilidad llave privada"
                                    >
                                        <span class="material-symbols-rounded text-lg">{{ showPrivateKey ? 'visibility_off' : 'visibility' }}</span>
                                    </button>
                                </div>

                                <!-- Webhook Secret with Show/Hide Eye Toggle -->
                                <div class="relative">
                                    <Input
                                        v-model="form.settings.wompi_webhook_secret"
                                        label="WEBHOOK SECRET"
                                        :type="showWebhookSecret ? 'text' : 'password'"
                                        placeholder="whsec_xxxxxxxxxxxxxxxx"
                                        :error="form.errors['settings.wompi_webhook_secret']"
                                        class="!rounded-2xl pr-12"
                                    />
                                    <button 
                                        type="button"
                                        @click="showWebhookSecret = !showWebhookSecret"
                                        class="absolute right-3.5 top-9 w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant/60 hover:text-on-surface hover:bg-surface-container transition-all cursor-pointer"
                                        :title="showWebhookSecret ? 'Ocultar secreto' : 'Revelar secreto'"
                                        aria-label="Alternar visibilidad webhook secret"
                                    >
                                        <span class="material-symbols-rounded text-lg">{{ showWebhookSecret ? 'visibility_off' : 'visibility' }}</span>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- 1-Click Copy Webhook URL Banner -->
                            <div class="p-4 sm:p-5 bg-primary/5 rounded-2xl border border-primary/20 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-primary">
                                <div class="flex items-start gap-3 min-w-0">
                                    <span class="material-symbols-rounded shrink-0 text-xl mt-0.5">link</span>
                                    <div class="min-w-0">
                                        <p class="text-[10px] sm:text-[11px] font-black uppercase tracking-wider">URL de Redirección Webhook</p>
                                        <p class="text-[10px] sm:text-xs font-mono text-on-surface/80 dark:text-white/80 truncate mt-0.5">
                                            https://{{ $page.props.auth.user.current_copropiedad?.domain || 'nexo-pro.com.co' }}/payments/webhook
                                        </p>
                                    </div>
                                </div>
                                <Button 
                                    type="button" 
                                    @click="copyWebhookUrl" 
                                    variant="primary" 
                                    size="sm" 
                                    class="!rounded-xl shrink-0 cursor-pointer min-h-[44px]"
                                    :icon="copiedWebhook ? 'check' : 'content_copy'"
                                >
                                    {{ copiedWebhook ? '¡COPIADO!' : 'COPIAR URL' }}
                                </Button>
                            </div>
                        </div>

                        <!-- Inactive State Notice -->
                        <div v-else class="py-10 text-center opacity-40">
                            <span class="material-symbols-rounded text-5xl text-on-surface-variant/40">lock</span>
                            <p class="text-[10px] font-black uppercase tracking-widest mt-2 text-on-surface-variant">
                                Integración de recaudos Wompi desactivada
                            </p>
                            <p class="text-[9px] text-on-surface-variant/60 mt-1 max-w-sm mx-auto">
                                Active la casilla superior para ingresar las llaves de producción o pruebas.
                            </p>
                        </div>
                    </Card>

                    <!-- Gateways Monitor -->
                    <Card v-if="form.settings.payments_enabled" class="rounded-[2rem] sm:rounded-[2.5rem] border border-outline-variant/15 dark:border-white/5 shadow-xl bg-surface dark:bg-[#0b0e14] overflow-hidden">
                        <div class="p-5 sm:p-6 border-b border-outline-variant/10 dark:border-white/5 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg sm:text-xl font-black text-on-surface dark:text-white uppercase tracking-tight leading-none">
                                    Canales de <span class="text-primary">Recaudo</span>
                                </h3>
                                <p class="text-[9px] font-bold text-on-surface-variant/50 dark:text-white/30 uppercase tracking-widest mt-1">
                                    Métodos de pago habilitados para los residentes
                                </p>
                            </div>
                        </div>

                        <div class="divide-y divide-outline-variant/10 dark:divide-white/5">
                            <div v-for="(gateway, key) in form.settings.gateways" :key="key" class="p-4 sm:p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 hover:bg-surface-container-high/30 transition-all">
                                <div class="flex items-center gap-3.5">
                                    <div class="w-11 h-11 rounded-2xl bg-surface-container-highest dark:bg-white/5 flex items-center justify-center text-primary shrink-0">
                                        <span class="material-symbols-rounded text-xl">
                                            {{ key === 'wompi' ? 'credit_card' : (key === 'aval' ? 'account_balance' : 'receipt_long') }}
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="text-xs sm:text-sm font-black text-on-surface dark:text-white uppercase tracking-tight">{{ gateway.label }}</h4>
                                        <p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-0.5">
                                            {{ key === 'manual' ? 'Consignación / Transferencia directa' : 'Pasarela digital automatizada' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 self-end md:self-auto">
                                    <div v-if="gateway.enabled && key === 'aval'" class="min-w-[200px]">
                                        <Input
                                            v-model="gateway.url"
                                            label="URL PORTAL AVAL"
                                            placeholder="https://..."
                                            :error="form.errors[`settings.gateways.${key}.url`]"
                                            class="!rounded-xl"
                                        />
                                    </div>
                                    <div class="flex items-center gap-3 px-4 py-2 rounded-xl bg-surface-container-high/60 dark:bg-white/5 border border-outline-variant/15">
                                        <span class="text-[9px] font-black uppercase tracking-wider" :class="gateway.enabled ? 'text-primary' : 'text-on-surface-variant/40'">
                                            {{ gateway.enabled ? 'ACTIVO' : 'INACTIVO' }}
                                        </span>
                                        <Checkbox v-model:checked="gateway.enabled" class="!w-5 !h-5" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- RIGHT COLUMN: MODULES, RULES & ACTION -->
                <div class="xl:col-span-4 space-y-6 sm:space-y-8">
                    
                    <!-- Hardware de Módulos (PQRS & Reservas) -->
                    <Card class="p-5 sm:p-6 rounded-[2rem] sm:rounded-[2.5rem] border border-outline-variant/15 dark:border-white/5 shadow-xl bg-surface dark:bg-[#0b0e14]">
                        <h3 class="text-base sm:text-lg font-black text-on-surface dark:text-white uppercase tracking-tight leading-none mb-4">
                            Interruptores de <span class="text-primary">Módulos</span>
                        </h3>
                        
                        <div class="space-y-3">
                            <!-- PQRS Toggle -->
                            <div class="flex items-center justify-between p-3.5 sm:p-4 rounded-2xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 hover:border-primary/30 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-primary/10 flex items-center justify-center text-primary shrink-0">
                                        <span class="material-symbols-rounded text-lg">forum</span>
                                    </div>
                                    <div>
                                        <span class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-tight block">Módulo PQRS</span>
                                        <span class="text-[8px] font-bold text-on-surface-variant/50 uppercase tracking-widest">Atención de radicados</span>
                                    </div>
                                </div>
                                <Checkbox v-model:checked="form.settings.pqrs_enabled" class="!w-5 !h-5" />
                            </div>

                            <!-- Reservas Toggle -->
                            <div class="flex items-center justify-between p-3.5 sm:p-4 rounded-2xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 hover:border-primary/30 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 shrink-0">
                                        <span class="material-symbols-rounded text-lg">event_available</span>
                                    </div>
                                    <div>
                                        <span class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-tight block">Módulo Reservas</span>
                                        <span class="text-[8px] font-bold text-on-surface-variant/50 uppercase tracking-widest">Zonas comunes</span>
                                    </div>
                                </div>
                                <Checkbox v-model:checked="form.settings.reservas_enabled" class="!w-5 !h-5" />
                            </div>
                        </div>

                        <!-- Reglas Financieras (Interés de Mora) -->
                        <div class="mt-6 pt-5 border-t border-outline-variant/10 dark:border-white/5 space-y-4">
                            <div>
                                <label class="text-[9px] font-black text-on-surface-variant/60 dark:text-white/40 uppercase tracking-widest block mb-2">
                                    COEFICIENTE DE MORA MENSUAL (%)
                                </label>
                                <Input
                                    v-model="form.settings.interes_mora"
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    placeholder="1.50"
                                    suffix="%"
                                    class="!rounded-2xl"
                                />
                            </div>

                            <!-- Quick preset pills for interest rate -->
                            <div class="flex items-center gap-2">
                                <span class="text-[8px] font-bold uppercase tracking-widest text-on-surface-variant/50">Sugeridos:</span>
                                <button 
                                    v-for="rate in [1.0, 1.5, 2.0, 2.5]" 
                                    :key="rate"
                                    type="button"
                                    @click="setMoraRate(rate)"
                                    class="px-2.5 py-1 rounded-lg text-[9px] font-black transition-all cursor-pointer"
                                    :class="Number(form.settings.interes_mora) === rate ? 'bg-primary text-white shadow-sm' : 'bg-surface-container-high text-on-surface-variant/70 hover:bg-surface-container-highest'"
                                >
                                    {{ rate.toFixed(1) }}%
                                </button>
                            </div>

                            <p class="text-[8px] font-bold text-on-surface-variant/50 uppercase tracking-widest leading-relaxed italic">
                                * Se aplica automáticamente al corte del ciclo contable sobre saldos vencidos.
                            </p>
                        </div>
                    </Card>

                    <!-- Save Action Card -->
                    <div class="p-6 rounded-[2rem] sm:rounded-[2.5rem] bg-surface-container-highest/60 dark:bg-white/5 border border-outline-variant/15 dark:border-white/10 flex flex-col gap-4">
                        <div class="text-center">
                            <h4 class="text-xs font-black text-on-surface dark:text-white uppercase tracking-widest italic">
                                Sincronización Global
                            </h4>
                            <p class="text-[8px] font-bold text-on-surface-variant/50 uppercase tracking-wider mt-1">
                                Aplica los cambios a pasarelas, módulos y reglas
                            </p>
                        </div>

                        <Button 
                            type="submit" 
                            variant="primary" 
                            size="lg" 
                            class="w-full !h-14 !rounded-2xl font-black uppercase tracking-wider shadow-xl shadow-primary/20 cursor-pointer min-h-[48px]"
                            :loading="form.processing"
                            icon="save"
                        >
                            {{ form.processing ? 'SINCRONIZANDO...' : 'GUARDAR PARÁMETROS' }}
                        </Button>

                        <p class="text-[8px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest text-center">
                            Atajo: <kbd class="px-1.5 py-0.5 rounded bg-surface-container border border-outline-variant/20 font-mono text-[9px]">Ctrl + S</kbd>
                        </p>
                    </div>
                </div>
            </form>
        </div>

        <!-- ============================================== -->
        <!-- TAB 2: HOJA DE VIDA (Architectural Models)     -->
        <!-- ============================================== -->
        <div v-if="activeTab === 'hoja_vida'" class="animate-in fade-in duration-300 space-y-6 sm:space-y-8">
            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 sm:gap-8">
                
                <!-- LEFT: MODEL DEFINITION FORM -->
                <div class="xl:col-span-7 space-y-6">
                    <Card class="p-5 sm:p-8 rounded-[2rem] sm:rounded-[2.5rem] border border-outline-variant/15 dark:border-white/5 shadow-xl bg-surface dark:bg-[#0b0e14] relative overflow-hidden">
                        
                        <!-- Lock Overlay if Certified and not SuperAdmin -->
                        <div v-if="copropiedad.unit_types_locked && !is_super_admin" class="absolute inset-0 z-20 bg-surface/85 dark:bg-black/85 backdrop-blur-sm flex flex-col items-center justify-center text-center p-6 sm:p-8">
                            <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center mb-3 text-emerald-500">
                                <span class="material-symbols-rounded text-3xl">verified_user</span>
                            </div>
                            <h3 class="text-lg sm:text-xl font-black text-on-surface dark:text-white uppercase tracking-tight">
                                Modelos Blindados & Certificados
                            </h3>
                            <p class="text-[10px] font-bold text-on-surface-variant/60 dark:text-white/40 uppercase tracking-wider mt-2 max-w-sm leading-relaxed">
                                El catálogo arquitectónico ya ha sido certificado con las unidades del censo. Si requiere una modificación estructural, contacte a Super Administrador.
                            </p>
                        </div>

                        <!-- Card Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-outline-variant/10">
                            <div>
                                <h2 class="text-xl sm:text-2xl font-black text-on-surface dark:text-white uppercase tracking-tight leading-none">
                                    Definir <span class="text-primary">Modelo de Unidad</span>
                                </h2>
                                <p class="text-[9px] sm:text-[10px] font-bold text-on-surface-variant/50 dark:text-white/30 uppercase tracking-widest mt-1">
                                    Asigne nombre, metraje privado y componentes arquitectónicos
                                </p>
                            </div>
                            <Badge :variant="copropiedad.unit_types_locked ? 'primary' : 'warning'" class="!rounded-xl !px-3 !py-1 text-[9px] font-black uppercase tracking-widest self-start sm:self-auto">
                                {{ copropiedad.unit_types_locked ? '🔒 BLINDADO' : '🔓 EDICIÓN ABIERTA' }}
                            </Badge>
                        </div>

                        <!-- Global Built Area Section -->
                        <div class="mb-6 p-4 sm:p-5 rounded-2xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/15">
                            <h4 class="text-xs font-black text-on-surface dark:text-white uppercase tracking-wider mb-2">
                                Parámetro Global del Conjunto
                            </h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                                <Input 
                                    v-model="form.area_construida_total" 
                                    type="number" 
                                    step="0.01" 
                                    label="ÁREA TOTAL CONSTRUIDA (M²)" 
                                    placeholder="Ej: 14500.00" 
                                    class="!rounded-2xl" 
                                />
                                <div class="flex items-end">
                                    <Button 
                                        @click="submit" 
                                        variant="primary" 
                                        size="lg" 
                                        :loading="form.processing" 
                                        class="w-full !rounded-2xl !h-12 cursor-pointer font-black text-[11px] uppercase min-h-[44px]"
                                    >
                                        ACTUALIZAR ÁREA TOTAL
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <!-- Model Name and Private Area Inputs -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <Input 
                                v-model="tipoUnidadForm.nombre" 
                                label="NOMBRE DEL MODELO (EJ: TIPO A)" 
                                placeholder="Apartamento Tipo A" 
                                class="!rounded-2xl" 
                            />
                            <Input 
                                v-model="tipoUnidadForm.area_m2" 
                                type="number" 
                                step="0.01" 
                                label="ÁREA PRIVADA (M²)" 
                                placeholder="68.50" 
                                class="!rounded-2xl" 
                            />
                        </div>

                        <!-- Components Catalog Selector -->
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-black text-primary uppercase tracking-widest">
                                    Seleccionar Componentes Arquitectónicos
                                </label>
                                <button 
                                    @click="isAddingCustom = !isAddingCustom"
                                    type="button"
                                    class="text-[9px] font-bold text-primary hover:underline uppercase tracking-wider flex items-center gap-1 cursor-pointer min-h-[44px] px-2"
                                >
                                    <span class="material-symbols-rounded text-base">{{ isAddingCustom ? 'close' : 'add_circle' }}</span>
                                    <span>{{ isAddingCustom ? 'Cerrar' : '+ Personalizar Componente' }}</span>
                                </button>
                            </div>

                            <!-- Custom Component Quick Add -->
                            <div v-if="isAddingCustom" class="p-3 sm:p-4 bg-surface-container-high/60 rounded-2xl border border-primary/20 flex gap-2 sm:gap-3 items-center animate-in fade-in duration-200">
                                <input 
                                    v-model="customComponenteNombre"
                                    type="text" 
                                    placeholder="Ej: Jacuzzi, Terraza BBQ, Depósito Doble"
                                    class="flex-1 bg-surface-container px-3.5 py-2.5 rounded-xl text-xs font-bold text-on-surface border border-outline-variant/20 focus:outline-none focus:border-primary min-h-[44px]"
                                    @keyup.enter="addCustomComponent"
                                />
                                <Button @click="addCustomComponent" variant="primary" size="sm" class="!rounded-xl cursor-pointer min-h-[44px]">
                                    Añadir
                                </Button>
                            </div>

                            <!-- Component Chips (Tactile >= 44x44px target) -->
                            <div class="flex flex-wrap gap-2 sm:gap-2.5">
                                <button 
                                    v-for="comp in catalogoComponentes" 
                                    :key="comp.id"
                                    @click="toggleComponente(comp)"
                                    type="button"
                                    :class="tipoUnidadForm.componentes.some(c => c.id === comp.id) ? 'bg-primary text-white border-primary shadow-md shadow-primary/20 ring-2 ring-primary/30' : 'bg-surface-container-low dark:bg-white/5 text-on-surface-variant hover:text-on-surface border-outline-variant/15 dark:border-white/10 hover:border-primary/40'"
                                    class="px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider border transition-all cursor-pointer select-none active:scale-95 min-h-[44px] flex items-center gap-1.5"
                                >
                                    <span v-if="tipoUnidadForm.componentes.some(c => c.id === comp.id)" class="material-symbols-rounded text-sm">check</span>
                                    <span>{{ comp.nombre }}</span>
                                </button>
                            </div>

                            <!-- Quantity Adjusters (Enlarged for Mobile Xiaomi 14T ~44px buttons) -->
                            <div v-if="tipoUnidadForm.componentes.length > 0" class="space-y-3 mt-5 pt-4 border-t border-outline-variant/10">
                                <p class="text-[9px] font-black text-on-surface-variant/60 uppercase tracking-widest">
                                    Cantidades por componente seleccionado:
                                </p>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                                    <div 
                                        v-for="comp in tipoUnidadForm.componentes" 
                                        :key="comp.id" 
                                        class="flex items-center justify-between p-3.5 bg-surface-container-lowest dark:bg-white/[0.02] rounded-2xl border border-outline-variant/15 shadow-sm"
                                    >
                                        <div class="flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full bg-primary shrink-0"></span>
                                            <span class="text-xs font-black text-on-surface dark:text-white uppercase tracking-tight truncate max-w-[120px]">
                                                {{ comp.nombre }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <button 
                                                @click="updateCantidad(comp.id, -1)" 
                                                type="button" 
                                                class="w-10 h-10 rounded-xl bg-surface-container-high hover:bg-primary hover:text-white text-on-surface font-black transition-all flex items-center justify-center cursor-pointer select-none active:scale-95 text-base"
                                                aria-label="Disminuir cantidad"
                                            >
                                                -
                                            </button>
                                            <span class="text-sm font-black text-on-surface dark:text-white w-7 text-center tabular-nums">
                                                {{ comp.cantidad }}
                                            </span>
                                            <button 
                                                @click="updateCantidad(comp.id, 1)" 
                                                type="button" 
                                                class="w-10 h-10 rounded-xl bg-surface-container-high hover:bg-primary hover:text-white text-on-surface font-black transition-all flex items-center justify-center cursor-pointer select-none active:scale-95 text-base"
                                                aria-label="Aumentar cantidad"
                                            >
                                                +
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-8 pt-5 border-t border-outline-variant/10 flex flex-col sm:flex-row items-center gap-3">
                            <Button 
                                @click="saveTipoUnidad" 
                                variant="primary" 
                                size="lg" 
                                :loading="tipoUnidadForm.processing" 
                                icon="send" 
                                class="w-full sm:w-auto font-black tracking-wider uppercase min-h-[48px]" 
                                :disabled="copropiedad.unit_types_locked && !is_super_admin"
                            >
                                REGISTRAR MODELO
                            </Button>
                            
                            <!-- Generator Button -->
                            <div v-if="!copropiedad.unit_types_locked" class="w-full sm:flex-1">
                                <Button 
                                    @click="lockConfiguration" 
                                    variant="primary" 
                                    outline 
                                    class="w-full !rounded-2xl !h-12 font-black tracking-wider text-xs uppercase min-h-[48px]" 
                                    icon="precision_manufacturing"
                                >
                                    GENERADOR SECUENCIAL
                                </Button>
                            </div>

                            <div v-if="copropiedad.unit_types_locked && is_super_admin" class="w-full sm:flex-1">
                                <Button 
                                    @click="unlockConfiguration" 
                                    variant="danger" 
                                    outline 
                                    class="w-full !rounded-2xl !h-12 font-black tracking-wider text-xs uppercase min-h-[48px]" 
                                    icon="lock_open"
                                >
                                    DESBLOQUEAR (SUPER ADMIN)
                                </Button>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- RIGHT: CERTIFIED MODELS & CENSUS EXCEL -->
                <div class="xl:col-span-5 space-y-6">
                    
                    <!-- Certified Models List with DELETE ACTION -->
                    <Card class="p-5 sm:p-6 rounded-[2rem] sm:rounded-[2.5rem] border border-outline-variant/15 dark:border-white/5 shadow-xl bg-surface dark:bg-[#0b0e14]">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-base sm:text-lg font-black text-on-surface dark:text-white uppercase tracking-tight leading-none">
                                Modelos <span class="text-primary">Registrados</span>
                            </h3>
                            <span class="text-[9px] font-bold text-on-surface-variant/50 uppercase tracking-widest">
                                {{ copropiedad.tipos_unidad?.length || 0 }} en catálogo
                            </span>
                        </div>
                        
                        <div class="space-y-3 max-h-[380px] overflow-y-auto pr-1 no-scrollbar">
                            <div 
                                v-for="tipo in copropiedad.tipos_unidad" 
                                :key="tipo.id" 
                                class="p-4 rounded-2xl bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 hover:border-primary/30 transition-all flex flex-col gap-2 group"
                            >
                                <div class="flex items-center justify-between gap-2">
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-black text-on-surface dark:text-white uppercase tracking-tight truncate">
                                            {{ tipo.nombre }}
                                        </h4>
                                        <span class="text-[10px] font-bold text-primary font-mono">
                                            {{ tipo.area_m2 }} m² privados
                                        </span>
                                    </div>
                                    
                                    <!-- Delete Button (Only active when not locked or Super Admin) -->
                                    <button 
                                        v-if="!copropiedad.unit_types_locked || is_super_admin"
                                        @click="deleteTipoUnidad(tipo)"
                                        class="w-9 h-9 rounded-xl flex items-center justify-center text-on-surface-variant/40 hover:text-error hover:bg-error/10 transition-all cursor-pointer min-h-[44px] min-w-[44px]"
                                        title="Eliminar este modelo"
                                        aria-label="Eliminar modelo"
                                    >
                                        <span class="material-symbols-rounded text-lg">delete</span>
                                    </button>
                                </div>

                                <div class="flex flex-wrap gap-1.5 pt-1 border-t border-outline-variant/10">
                                    <span 
                                        v-for="comp in tipo.componentes" 
                                        :key="comp.id" 
                                        class="px-2 py-0.5 bg-surface-container-high dark:bg-white/5 rounded-md text-[8px] font-bold text-on-surface-variant uppercase tracking-wider"
                                    >
                                        {{ comp.pivot.cantidad }} {{ comp.nombre }}
                                    </span>
                                    <span v-if="!tipo.componentes?.length" class="text-[8px] font-bold text-on-surface-variant/40 italic">
                                        Sin componentes
                                    </span>
                                </div>
                            </div>

                            <div v-if="!copropiedad.tipos_unidad?.length" class="py-8 text-center opacity-40 italic text-xs text-on-surface dark:text-white">
                                No hay modelos arquitectónicos registrados aún
                            </div>
                        </div>
                    </Card>

                    <!-- Census & Excel Bulk Import -->
                    <Card class="p-5 sm:p-6 rounded-[2rem] sm:rounded-[2.5rem] border border-outline-variant/15 dark:border-white/5 shadow-xl bg-surface dark:bg-[#0b0e14]">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <span class="px-2 py-0.5 rounded-md bg-primary/10 text-primary text-[8px] font-black tracking-widest uppercase">Paso 2</span>
                                <h3 class="text-base sm:text-lg font-black text-on-surface dark:text-white uppercase tracking-tight mt-1 leading-none">
                                    Censo & <span class="text-primary">Carga Masiva</span>
                                </h3>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                <span class="material-symbols-rounded text-xl">table_view</span>
                            </div>
                        </div>

                        <p class="text-[11px] font-medium text-on-surface-variant/70 leading-relaxed mb-4">
                            Descarga la plantilla oficial con tus modelos en lista desplegable, diligencia las unidades y sube el archivo para auditarlo en tiempo real.
                        </p>

                        <!-- Notice if no models exist -->
                        <div v-if="!copropiedad.tipos_unidad?.length" class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-700 dark:text-amber-300 text-xs font-medium">
                            <p class="font-bold uppercase text-[9px] tracking-wider mb-1">Catálogo Vacío</p>
                            <p class="text-[10px] leading-relaxed">
                                Registre al menos un modelo de unidad para poder descargar la plantilla con lista desplegable.
                            </p>
                        </div>

                        <!-- Active State: Download & Dropzone -->
                        <div v-else class="space-y-4">
                            <Button 
                                @click="downloadTemplate" 
                                variant="primary" 
                                size="lg" 
                                class="w-full !rounded-2xl !h-12 font-black uppercase tracking-wider shadow-md shadow-primary/20 text-xs min-h-[48px]"
                                icon="download"
                            >
                                DESCARGAR PLANTILLA (.XLSX)
                            </Button>

                            <!-- Drag & Drop Dropzone -->
                            <div 
                                @dragover.prevent="isDraggingOver = true"
                                @dragleave.prevent="isDraggingOver = false"
                                @drop.prevent="handleFileDrop"
                                @click="triggerFileInput"
                                :class="[
                                    isDraggingOver ? 'border-primary bg-primary/10 scale-[1.01]' : 'border-outline-variant/20 dark:border-white/10 hover:border-primary/50 bg-surface-container-low dark:bg-white/[0.02]',
                                    copropiedad.unit_types_locked && !is_super_admin ? 'opacity-50 pointer-events-none' : 'cursor-pointer'
                                ]"
                                class="p-6 rounded-2xl border-2 border-dashed transition-all flex flex-col items-center justify-center text-center group active:scale-[0.99]"
                            >
                                <input 
                                    ref="fileInputRef" 
                                    type="file" 
                                    accept=".xlsx,.xls,.csv" 
                                    class="hidden" 
                                    @change="handleFileSelection"
                                />
                                
                                <div class="w-11 h-11 rounded-2xl bg-primary/10 group-hover:bg-primary group-hover:text-white text-primary flex items-center justify-center mb-2 transition-all">
                                    <span class="material-symbols-rounded text-xl">cloud_upload</span>
                                </div>
                                
                                <p class="text-xs font-black text-on-surface dark:text-white uppercase tracking-wider mb-0.5">
                                    Arrastra tu censo aquí
                                </p>
                                <p class="text-[9px] font-bold text-on-surface-variant/50 uppercase tracking-widest">
                                    O haz clic para examinar (.xlsx, .xls, .csv)
                                </p>
                            </div>
                        </div>
                    </Card>
                </div>
            </div>
        </div>

        <!-- Sticky Mobile Save Bar for NUCLEO (Xiaomi 14T ~393px optimized) -->
        <div v-if="activeTab === 'nucleo'" class="sm:hidden fixed bottom-16 inset-x-0 p-3 bg-surface/95 dark:bg-[#0b0e14]/95 backdrop-blur-xl border-t border-outline-variant/15 z-30 shadow-2xl flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="text-[9px] font-black uppercase text-primary tracking-widest truncate">Parámetros Globales</p>
                <p class="text-[8px] font-bold text-on-surface-variant/60 uppercase truncate">Guarda los cambios</p>
            </div>
            <Button 
                @click="submit" 
                variant="primary" 
                size="md" 
                class="!rounded-xl font-black uppercase tracking-wider shadow-lg shadow-primary/20 shrink-0 min-h-[44px]"
                :loading="form.processing"
                icon="save"
            >
                GUARDAR
            </Button>
        </div>
    </div>
    
    <UnitGeneratorModal 
        :show="showGeneratorModal" 
        @close="showGeneratorModal = false"
        @success="() => { showGeneratorModal = false; activeTab = 'nucleo'; toast.add('Unidades generadas exitosamente', 'success'); }"
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
