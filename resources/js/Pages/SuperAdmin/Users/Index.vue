<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Input from '@/Components/UI/Input.vue';
import Table from '@/Components/UI/Table.vue';
import Pagination from '@/Components/UI/Pagination.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';
import ConfirmModal from '@/Components/UI/ConfirmModal.vue';
import StatCard from '@/Components/Dashboard/StatCard.vue';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    users: {
        data: Array<any>;
        current_page: number;
        last_page: number;
        total: number;
        links: Array<any>;
        [key: string]: any;
    };
    filters: any;
    stats: {
        total: number;
        active: number;
        admins: number;
        sec_enabled: number;
    };
}>();

const tableColumns = [
    { key: 'user', label: 'Identidad del Usuario', sortable: false },
    { key: 'seguridad', label: 'Seguridad', sortable: false },
    { key: 'role', label: 'Rol / Cargo', sortable: false },
    { key: 'property', label: 'Contexto Propiedad', sortable: false },
    { key: 'activity', label: 'Última Actividad', sortable: false },
    { key: 'actions', label: 'Comandos', sortable: false },
];

const confirmState = ref({
    show: false,
    title: '',
    message: '',
    confirmLabel: 'Confirmar',
    variant: 'primary' as 'primary' | 'error' | 'warning',
    action: null as (() => void) | null
});

const search = ref(props.filters.search || '');

watch(search, (value) => {
    router.get(route('superadmin.users.index'), { search: value }, {
        preserveState: true,
        replace: true,
    });
});

const getRoleConfig = (role: string) => {
    switch (role) {
        case 'super_admin': return { label: 'GLOBAL ADMIN', variant: 'gradient', icon: 'shield_person' };
        case 'admin': return { label: 'ADMINISTRADOR', variant: 'primary', icon: 'admin_panel_settings' };
        case 'owner': return { label: 'PROPIETARIO', variant: 'neutral', icon: 'home' };
        default: return { label: 'RESIDENTE', variant: 'neutral', icon: 'person' };
    }
};

const impersonate = (user: any) => {
    confirmState.value = {
        show: true,
        title: 'Abrir Sesión de Soporte',
        message: `Estás a punto de entrar en la cuenta de ${user.name}. Esta sesión es estrictamente para soporte y será auditada en los logs del sistema.`,
        confirmLabel: 'Iniciar Soporte',
        variant: 'primary',
        action: () => router.post(route('superadmin.impersonate', user.id))
    };
};

const toggleStatus = (user: any) => {
    confirmState.value = {
        show: true,
        title: user.is_active ? 'Suspender Acceso' : 'Activar Acceso',
        message: `¿Deseas ${user.is_active ? 'suspender' : 'reactivar'} el acceso a la plataforma para ${user.name}?`,
        confirmLabel: user.is_active ? 'Suspender' : 'Activar ahora',
        variant: user.is_active ? 'error' : 'primary',
        action: () => router.patch(route('superadmin.users.toggle-status', user.id))
    };
};

const reset2fa = (user: any) => {
    confirmState.value = {
        show: true,
        title: 'Resetear Segundo Factor',
        message: `¿Estás seguro de que deseas forzar el reseteo del 2FA para ${user.name}? Perderá su vinculación con la App autenticadora.`,
        confirmLabel: 'Resetear 2FA',
        variant: 'warning',
        action: () => router.post(route('superadmin.users.reset-2fa', user.id))
    };
};

const executeConfirm = () => {
    if (confirmState.value.action) {
        confirmState.value.action();
        confirmState.value.show = false;
    }
};
</script>

<template>
    <Head title="Gestión de Usuarios — NEXO-PRO" />

    <div class="space-y-10 pb-20 animate-in fade-in duration-700">
        <!-- Header con KPIs de Identidad -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1">
            <div>
                <h2 class="text-4xl font-black text-primary tracking-tighter uppercase leading-none">Gestión de Usuarios</h2>
                <p class="text-[10px] font-bold text-on-surface-variant/40 uppercase tracking-[0.2em] mt-3">Control táctico de identidades y seguridad 2FA</p>
            </div>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 w-full md:w-auto">
                <div class="bg-surface-container-low px-5 py-3 rounded-2xl border border-outline-variant/10 flex flex-col">
                    <span class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-[0.2em]">Total</span>
                    <span class="text-lg font-black text-primary">— {{ stats.total }}</span>
                </div>
                <div class="bg-surface-container-low px-5 py-3 rounded-2xl border border-outline-variant/10 flex flex-col">
                    <span class="text-[8px] font-black text-emerald-600/40 uppercase tracking-[0.2em]">Activos</span>
                    <span class="text-lg font-black text-emerald-600">— {{ stats.active }}</span>
                </div>
                <div class="bg-surface-container-low px-5 py-3 rounded-2xl border border-outline-variant/10 flex flex-col">
                    <span class="text-[8px] font-black text-indigo-600/40 uppercase tracking-[0.2em]">Admins</span>
                    <span class="text-lg font-black text-indigo-600">— {{ stats.admins }}</span>
                </div>
                <div class="bg-surface-container-low px-5 py-3 rounded-2xl border border-outline-variant/10 flex flex-col">
                    <span class="text-[8px] font-black text-amber-600/40 uppercase tracking-[0.2em]">2FA Activo</span>
                    <span class="text-lg font-black text-amber-600">— {{ stats.sec_enabled }}</span>
                </div>
            </div>
        </div>

        <!-- Filtros y Tabla -->
        <div class="space-y-6">
            <div class="bg-surface-container-low p-6 rounded-[2.5rem] border border-outline-variant/10 flex items-center gap-6">
                <div class="flex-1 relative">
                    <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40">search</span>
                    <input 
                        v-model="search"
                        placeholder="Buscar por nombre, email o cargo..."
                        class="w-full pl-12 pr-4 py-3 bg-white/50 border border-outline-variant/10 rounded-2xl text-xs font-bold focus:ring-2 focus:ring-primary/20 outline-none transition-all"
                    />
                </div>
                <Button variant="primary" icon="person_add" class="!py-3 shadow-brand/10 shadow-lg">Nuevo Usuario</Button>
            </div>

            <Card content-class="!p-0 overflow-hidden !rounded-[2.5rem]">
                <Table :columns="tableColumns" :data="users.data">
                    <template #cell-user="{ row }">
                        <div class="flex items-center gap-4 py-1">
                            <div class="relative">
                                <img :src="row.avatar" class="w-12 h-12 rounded-[1.25rem] bg-surface-container shadow-inner object-cover" />
                                <div v-if="row.is_active" class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full bg-white flex items-center justify-center">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <p class="text-xs font-black text-primary uppercase tracking-tighter leading-none">{{ row.name }}</p>
                                <p class="text-[10px] font-medium text-on-surface-variant mt-1">{{ row.email }}</p>
                            </div>
                        </div>
                    </template>

                    <template #cell-seguridad="{ row }">
                        <div class="flex items-center gap-2">
                             <Tooltip :text="row.has_2fa_enabled ? 'Centa blindada con 2FA' : 'Acceso vulnerable (Sin 2FA)'">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-all bg-surface-container-high" 
                                    :class="row.has_2fa_enabled ? 'text-amber-600' : 'text-on-surface-variant/20'">
                                    <span class="material-symbols-rounded text-[20px]">{{ row.has_2fa_enabled ? 'verified_user' : 'no_encryption' }}</span>
                                </div>
                             </Tooltip>
                             <div class="flex flex-col">
                                <span class="text-[9px] font-black tracking-widest uppercase" :class="row.has_2fa_enabled ? 'text-amber-600' : 'text-on-surface-variant/40'">{{ row.has_2fa_enabled ? 'Seguro' : 'Vulnerable' }}</span>
                             </div>
                        </div>
                    </template>

                    <template #cell-role="{ row }">
                        <Badge :variant="getRoleConfig(row.role).variant" class="!px-3 !py-1 !text-[9px] font-black uppercase tracking-widest flex items-center gap-1.5 w-fit">
                            <span class="material-symbols-rounded text-[14px]">{{ getRoleConfig(row.role).icon }}</span>
                            {{ getRoleConfig(row.role).label }}
                        </Badge>
                    </template>

                    <template #cell-property="{ row }">
                         <div v-if="row.current_copropiedad" class="flex flex-col group/prop">
                            <p class="text-[10px] font-black text-primary uppercase tracking-tighter">{{ row.current_copropiedad.nombre }}</p>
                            <p class="text-[9px] text-on-surface-variant/40 font-bold uppercase mt-0.5">{{ row.current_copropiedad.ciudad }}</p>
                        </div>
                        <Badge v-else variant="neutral" class="!text-[8px] !opacity-30">SIN ASOCIACIÓN</Badge>
                    </template>

                    <template #cell-activity="{ row }">
                        <div class="flex flex-col">
                            <p class="text-[10px] font-black text-on-surface tracking-tighter">Último Acceso</p>
                            <p class="text-[9px] font-bold text-on-surface-variant/50 uppercase mt-0.5 tracking-widest">{{ row.last_login_at_human }}</p>
                        </div>
                    </template>

                    <template #cell-actions="{ row }">
                        <div class="flex items-center justify-end gap-2 pr-4">
                             <Tooltip text="Soporte (Login como él)">
                                <Button 
                                    @click="impersonate(row)"
                                    class="!w-10 !h-10 !rounded-2xl !p-0"
                                    variant="secondary"
                                    icon="support_agent"
                                ></Button>
                            </Tooltip>

                            <Tooltip :text="row.is_active ? 'Suspender Cuenta' : 'Activar Cuenta'">
                                <Button 
                                    @click="toggleStatus(row)"
                                    class="!w-10 !h-10 !rounded-2xl !p-0"
                                    :variant="row.is_active ? 'secondary' : 'error'"
                                    :icon="row.is_active ? 'do_not_disturb_on' : 'check_circle'"
                                ></Button>
                            </Tooltip>

                             <Tooltip v-if="row.has_2fa_enabled" text="Forzar Reset de 2FA">
                                <Button 
                                    @click="reset2fa(row)"
                                    class="!w-10 !h-10 !rounded-2xl !p-0"
                                    variant="secondary"
                                    icon="lock_reset"
                                ></Button>
                            </Tooltip>
                        </div>
                    </template>
                </Table>

            </Card>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <ConfirmModal 
        :show="confirmState.show"
        :title="confirmState.title"
        :message="confirmState.message"
        :confirm-label="confirmState.confirmLabel"
        :variant="confirmState.variant"
        @confirm="executeConfirm"
        @cancel="confirmState.show = false"
    />
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 500, 'FILL' 0;
}
.bg-brand-gradient {
    background: linear-gradient(135deg, var(--color-primary) 0%, #3b82f6 100%);
}
</style>
