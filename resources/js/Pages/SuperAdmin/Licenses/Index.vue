<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Select from '@/Components/UI/Select.vue';
import DatePicker from '@/Components/UI/DatePicker.vue';
import ConfirmModal from '@/Components/UI/ConfirmModal.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';
import Input from '@/Components/UI/Input.vue';

// Definir layout persistente
defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    copropiedades: Array<any>;
}>();

const searchTerm = ref('');
const filterStatus = ref('all');

const filteredCopropiedades = computed(() => {
    return props.copropiedades.filter(c => {
        const matchesSearch = c.nombre.toLowerCase().includes(searchTerm.value.toLowerCase()) || 
                             c.ciudad.toLowerCase().includes(searchTerm.value.toLowerCase());
        const matchesStatus = filterStatus.value === 'all' || c.license_status === filterStatus.value;
        return matchesSearch && matchesStatus;
    });
});

const confirmState = ref({
    show: false,
    title: '',
    message: '',
    confirmLabel: 'Confirmar',
    variant: 'primary' as 'primary' | 'error' | 'warning',
    action: null as (() => void) | null
});

const updateLicense = (copropiedad: any, data: any) => {
    router.post(route('superadmin.licenses.update', copropiedad.id), data, { 
        preserveScroll: true,
    });
};

const impersonateAdmin = (copropiedad: any) => {
    const admin = copropiedad.administradores?.[0];
    if (admin) {
        confirmState.value = {
            show: true,
            title: 'Soporte Técnico',
            message: `¿Estás seguro de que deseas iniciar sesión como ${admin.name} para la copropiedad ${copropiedad.nombre}? Esta acción quedará registrada en la auditoría.`,
            confirmLabel: 'Iniciar Soporte',
            variant: 'primary',
            action: () => router.post(route('superadmin.impersonate', admin.id))
        };
    }
};

const executeConfirm = () => {
    if (confirmState.value.action) {
        confirmState.value.action();
        confirmState.value.show = false;
    }
};

const getStatusConfig = (status: string) => {
    switch (status) {
        case 'active': return { label: 'ACTIVA', variant: 'success', icon: 'check_circle' };
        case 'suspended': return { label: 'SUSPENDIDA', variant: 'error', icon: 'pause_circle' };
        case 'expired': return { label: 'VENCIDA', variant: 'warning', icon: 'history' };
        default: return { label: 'ESTADO', variant: 'neutral', icon: 'help' };
    }
};

const getPlanConfig = (plan: string) => {
    switch (plan) {
        case 'enterprise': return { label: 'ENTERPRISE', color: 'text-indigo-600', bg: 'bg-indigo-50' };
        case 'pro': return { label: 'PRO', color: 'text-amber-600', bg: 'bg-amber-50' };
        default: return { label: 'BASIC', color: 'text-emerald-600', bg: 'bg-emerald-50' };
    }
};
</script>

<template>
    <Head title="Propiedades — NEXO-PRO" />

    <div class="space-y-10 pb-20 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Header Estratégico -->
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8 px-1">
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-[1.25rem] bg-brand-gradient shadow-xl shadow-primary/20 flex items-center justify-center text-white">
                        <span class="material-symbols-rounded text-2xl">business_center</span>
                    </div>
                    <div>
                        <h2 class="text-3xl font-black text-primary tracking-tighter uppercase leading-none">Hub de Copropiedades</h2>
                        <p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-[0.2em] mt-2">Visión global y gestión de activos</p>
                    </div>
                </div>
            </div>
            
            <!-- Barra de Herramientas -->
            <div class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative w-full sm:w-72">
                    <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40">search</span>
                    <input 
                        v-model="searchTerm"
                        placeholder="Buscar por nombre o ciudad..."
                        class="w-full pl-12 pr-4 py-3 bg-surface-container-low border border-outline-variant/10 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-primary/20 outline-none transition-all placeholder:text-on-surface-variant/20"
                    />
                </div>
                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <Select 
                        v-model="filterStatus"
                        :options="[
                            { value: 'all', label: 'TODOS LOS ESTADOS' },
                            { value: 'active', label: 'ACTIVAS' },
                            { value: 'suspended', label: 'SUSPENDIDAS' },
                            { value: 'expired', label: 'VENCIDAS' }
                        ]"
                        class="!w-full sm:!w-44 !py-3"
                    />
                    <Button variant="primary" icon="add_business" :href="route('register')" class="!py-3 shadow-brand/25 shadow-xl">Nueva</Button>
                </div>
            </div>
        </div>

        <!-- Grid de Copropiedades -->
        <div v-if="filteredCopropiedades.length > 0" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <div v-for="c in filteredCopropiedades" :key="c.id" 
                class="group bg-surface-container-low border border-outline-variant/5 rounded-[2.5rem] overflow-hidden transition-all duration-500 hover:shadow-2xl hover:shadow-primary/10 hover:-translate-y-2 relative"
                :class="{'opacity-75 grayscale-[0.5]': c.license_status === 'suspended'}"
            >
                <!-- Status Badge (Absolute) -->
                <div class="absolute top-6 right-6 z-10">
                    <Badge :variant="getStatusConfig(c.license_status).variant" class="!px-3 !py-1 !text-[9px] font-black tracking-widest uppercase shadow-sm">
                        {{ getStatusConfig(c.license_status).label }}
                    </Badge>
                </div>

                <!-- Card Header (Visual Identity) -->
                <div class="h-32 bg-surface-container-high relative flex items-end px-8 pb-4 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-surface-container-low/90 to-transparent"></div>
                    <!-- Pattern Decorative -->
                    <div class="absolute right-0 top-0 w-32 h-32 opacity-[0.03] group-hover:scale-125 transition-transform duration-1000">
                        <span class="material-symbols-rounded text-9xl">apartment</span>
                    </div>
                    
                    <div class="relative z-10 flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-white shadow-sm border border-outline-variant/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                            <span class="material-symbols-rounded text-2xl">location_city</span>
                        </div>
                        <div class="max-w-[200px]">
                            <h3 class="text-base font-black text-primary tracking-tighter uppercase leading-tight truncate">{{ c.nombre }}</h3>
                            <p class="text-[9px] font-bold text-on-surface-variant/60 uppercase tracking-widest">{{ c.ciudad }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card Body (Data) -->
                <div class="p-8 space-y-8">
                    <!-- KPIs Principales -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-surface-container/50 p-4 rounded-3xl border border-outline-variant/10 flex flex-col items-center justify-center text-center group/kpi hover:bg-primary/[0.03] transition-colors">
                            <p class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em] mb-1">Unidades</p>
                            <span class="text-xl font-black text-primary leading-none">{{ c.unidades_count }}</span>
                        </div>
                        <div class="bg-surface-container/50 p-4 rounded-3xl border border-outline-variant/10 flex flex-col items-center justify-center text-center hover:bg-primary/[0.03] transition-colors">
                            <p class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em] mb-1">Residentes</p>
                            <span class="text-xl font-black text-primary leading-none">{{ c.users_count }}</span>
                        </div>
                    </div>

                    <!-- Configuración de Licencia (Integrada) -->
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <p class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest">Configuración Técnica</p>
                            <div v-if="c.days_left !== null" class="flex items-center gap-1.5">
                                <span class="material-symbols-rounded text-xs" :class="c.days_left < 15 ? 'text-error animate-pulse' : 'text-emerald-500'">timer</span>
                                <span class="text-[9px] font-black uppercase tracking-tighter" :class="c.days_left < 15 ? 'text-error' : 'text-on-surface-variant'">{{ c.days_left }} días</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[8px] font-black text-on-surface-variant/30 uppercase ml-1">Plan contratado</span>
                                <Select
                                    :model-value="c.plan || 'basic'"
                                    @update:model-value="(val) => updateLicense(c, {plan: val})"
                                    :options="[
                                        { value: 'basic', label: 'BASIC' },
                                        { value: 'pro', label: 'PRO' },
                                        { value: 'enterprise', label: 'ENTERPRISE' }
                                    ]"
                                    class="!py-2.5 !rounded-2xl"
                                />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <span class="text-[8px] font-black text-on-surface-variant/30 uppercase ml-1">Vencimiento</span>
                                <DatePicker 
                                    :model-value="c.license_expires_at"
                                    @update:model-value="(val) => updateLicense(c, {license_expires_at: val})"
                                    class="!py-2.5 !rounded-2xl"
                                />
                            </div>
                        </div>

                        <!-- Matriz de Módulos (Visual DNA) -->
                        <div class="flex items-center justify-center gap-2 p-2 bg-surface-container rounded-[1.25rem] border border-outline-variant/10">
                            <Tooltip :text="c.settings.pqrs_enabled ? 'Desactivar PQRS' : 'Activar PQRS'">
                                <button 
                                    @click="updateLicense(c, {pqrs_enabled: !c.settings.pqrs_enabled})" 
                                    class="flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all group/mod"
                                    :class="c.settings.pqrs_enabled ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant/20 hover:text-on-surface-variant/40'"
                                >
                                    <span class="material-symbols-rounded text-lg">forum</span>
                                </button>
                            </Tooltip>
                            <Tooltip :text="c.settings.reservas_enabled ? 'Desactivar Reservas' : 'Activar Reservas'">
                                <button 
                                    @click="updateLicense(c, {reservas_enabled: !c.settings.reservas_enabled})" 
                                    class="flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all"
                                    :class="c.settings.reservas_enabled ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant/20 hover:text-on-surface-variant/40'"
                                >
                                    <span class="material-symbols-rounded text-lg">calendar_today</span>
                                </button>
                            </Tooltip>
                            <Tooltip :text="c.settings.payments_enabled ? 'Desactivar Pagos' : 'Activar Pagos'">
                                <button 
                                    @click="updateLicense(c, {payments_enabled: !c.settings.payments_enabled})" 
                                    class="flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all"
                                    :class="c.settings.payments_enabled ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant/20 hover:text-on-surface-variant/40'"
                                >
                                    <span class="material-symbols-rounded text-lg">payments</span>
                                </button>
                            </Tooltip>
                            <Tooltip :text="c.settings.asamblea_virtual_active ? 'Desactivar Asamblea' : 'Activar Asamblea'">
                                <button 
                                    @click="updateLicense(c, {asamblea_virtual_active: !c.settings.asamblea_virtual_active})" 
                                    class="flex-1 py-1.5 rounded-xl flex items-center justify-center transition-all"
                                    :class="c.settings.asamblea_virtual_active ? 'bg-white shadow-sm text-primary' : 'text-on-surface-variant/20 hover:text-on-surface-variant/40'"
                                >
                                    <span class="material-symbols-rounded text-lg">videocam</span>
                                </button>
                            </Tooltip>
                        </div>
                    </div>

                    <!-- Footer Actions -->
                    <div class="flex items-center gap-3 pt-4 border-t border-outline-variant/10">
                        <Button 
                            variant="primary" 
                            size="sm" 
                            class="flex-1 !py-3 rounded-2xl shadow-brand/10 shadow-lg group/btn overflow-hidden relative"
                            @click="router.get(route('superadmin.properties.manage', c.id))"
                        >
                            <span class="relative z-10">Gestionar Propiedad</span>
                            <div class="absolute inset-x-0 bottom-0 h-0 group-hover/btn:h-full bg-white/5 transition-all duration-300"></div>
                        </Button>
                        <div class="flex items-center gap-2">
                             <Tooltip text="Soporte Técnico (Impersonar)">
                                <button 
                                    @click="impersonateAdmin(c)"
                                    class="w-11 h-11 rounded-2xl bg-surface-container-high border border-outline-variant/10 flex items-center justify-center text-on-surface-variant hover:bg-primary hover:text-white transition-all duration-300"
                                >
                                    <span class="material-symbols-rounded text-lg">support_agent</span>
                                </button>
                            </Tooltip>
                            <Tooltip text="Usuarios">
                                <button 
                                    @click="router.get(route('superadmin.users.index'))"
                                    class="w-11 h-11 rounded-2xl bg-surface-container-high border border-outline-variant/10 flex items-center justify-center text-on-surface-variant hover:bg-secondary hover:text-white transition-all duration-300"
                                >
                                    <span class="material-symbols-rounded text-lg">group</span>
                                </button>
                            </Tooltip>
                        </div>
                    </div>
                </div>
                
                <!-- Bottom Decorative Bar -->
                <div class="h-1.5 w-full bg-primary/5 absolute bottom-0 left-0">
                    <div 
                        class="h-full bg-primary transition-all duration-1000"
                        :style="`width: ${Math.min(100, (c.health_score || 0))}%`"
                    ></div>
                </div>
            </div>
        </div>

        <!-- Vista Vacía -->
        <div v-else class="flex flex-col items-center justify-center py-32 space-y-6">
            <div class="w-24 h-24 rounded-full bg-surface-container flex items-center justify-center text-on-surface-variant/20">
                <span class="material-symbols-rounded text-5xl">search_off</span>
            </div>
            <div class="text-center">
                <h3 class="text-xl font-black text-primary uppercase tracking-tighter">Sin resultados</h3>
                <p class="text-xs font-bold text-on-surface-variant/40 uppercase tracking-widest mt-2">No encontramos copropiedades que coincidan con tu búsqueda</p>
            </div>
            <Button variant="outline" @click="searchTerm = ''; filterStatus = 'all'">Limpiar Filtros</Button>
        </div>

        <!-- Confirm Modal for Impersonation -->
        <ConfirmModal 
            :show="confirmState.show"
            :title="confirmState.title"
            :message="confirmState.message"
            :confirm-label="confirmState.confirmLabel"
            :variant="confirmState.variant"
            @confirm="executeConfirm"
            @cancel="confirmState.show = false"
        />
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 500, 'FILL' 0;
}
.group:hover .material-symbols-rounded {
    font-variation-settings: 'FILL' 1;
}
.bg-brand-gradient {
    background: linear-gradient(135deg, var(--color-primary) 0%, #3b82f6 100%);
}
</style>
