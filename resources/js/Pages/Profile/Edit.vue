<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import OwnerLayout from '@/Layouts/OwnerLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';

const page = usePage<any>();
const user = computed(() => page.props.auth.user);

// Dynamic layout binding
const Layout = computed(() => {
    return user.value.role === 'owner' ? OwnerLayout : AuthenticatedLayout;
});

const props = defineProps<{
    status?: string;
}>();

const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const profileForm = useForm({
    name: user.value.name,
    email: user.value.email,
});

const updateProfile = () => {
    profileForm.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {},
    });
};

const updatePassword = () => {
    passwordForm.put(route('profile.password'), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
    });
};

const getRoleConfig = (role: string) => {
    const configs: Record<string, any> = {
        superadmin: { label: 'SUPER ADMINISTRADOR', color: 'text-primary', bg: 'bg-primary/10', icon: 'verified_user' },
        admin:      { label: 'ADMINISTRADOR', color: 'text-secondary', bg: 'bg-secondary/10', icon: 'shield' },
        owner:      { label: 'PROPIETARIO / RESIDENTE', color: 'text-emerald-500', bg: 'bg-emerald-500/10', icon: 'home' },
    };
    return configs[role] || configs.owner;
};
</script>

<template>
    <Head title="Mi Perfil — NEXO-PRO" />

    <component :is="Layout" :user="user">
        <div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-6 duration-1000">
            
            <!-- Identity Hero Section -->
            <div class="relative bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[3rem] p-10 shadow-2xl overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-primary/5 rounded-full blur-[100px] group-hover:bg-primary/10 transition-all duration-1000"></div>
                
                <div class="flex flex-col md:flex-row items-center gap-12 relative z-10">
                    <!-- Avatar Pro -->
                    <div class="relative">
                        <div class="w-40 h-40 rounded-[2.5rem] bg-surface-container dark:bg-white/5 border-2 border-outline-variant/10 dark:border-white/10 p-2 shadow-2xl rotate-3 transition-transform group-hover:rotate-0 duration-500">
                            <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${user.email}`" class="w-full h-full rounded-[2rem] object-cover" />
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-12 h-12 rounded-2xl bg-emerald-500 border-4 border-white dark:border-[#0b0e14] flex items-center justify-center text-white shadow-xl">
                            <span class="material-symbols-rounded text-xl">check_circle</span>
                        </div>
                    </div>

                    <!-- Datos Core -->
                    <div class="flex-1 text-center md:text-left space-y-4">
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4">
                            <Badge variant="neutral" class="!px-5 !py-1 !text-[10px] !font-black !bg-primary/5 !text-primary !border-primary/10 tracking-[0.2em] uppercase">
                                <span class="material-symbols-rounded text-[14px] mr-2">{{ getRoleConfig(user.role).icon }}</span>
                                {{ getRoleConfig(user.role).label }}
                            </Badge>
                            <Badge variant="neutral" class="!px-5 !py-1 !text-[10px] !font-black !bg-white dark:!bg-white/5 !text-on-surface-variant dark:!text-white/40 !border-outline-variant/10 dark:!border-white/5 tracking-[0.2em] uppercase italic">
                                UID: {{ user.id.split('-')[0] }}
                            </Badge>
                        </div>
                        <h1 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">{{ user.name }}</h1>
                        <p class="text-sm font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] italic">{{ user.email }}</p>
                    </div>

                    <!-- Stats de Integridad -->
                    <div class="grid grid-cols-2 gap-4">
                         <div class="px-8 py-6 bg-surface-container-low dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/10 dark:border-white/5 text-center">
                            <p class="text-[9px] font-black text-primary uppercase tracking-widest mb-1">MIEMBRO DESDE</p>
                            <p class="text-lg font-black text-on-surface dark:text-white uppercase tracking-tighter italic">{{ new Date(user.created_at).getFullYear() }}</p>
                         </div>
                         <div class="px-8 py-6 bg-surface-container-low dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/10 dark:border-white/5 text-center">
                            <p class="text-[9px] font-black text-emerald-500 uppercase tracking-widest mb-1">ESTADO CUENTA</p>
                            <p class="text-lg font-black text-on-surface dark:text-white uppercase tracking-tighter italic">VÁLIDA</p>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Bento Boxes de Configuración -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <!-- Information Section -->
                <div class="lg:col-span-12 xl:col-span-7">
                    <Card class="!p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]">
                        <div class="flex justify-between items-start mb-10">
                            <div>
                                <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic">Datos de <span class="text-primary font-black italic">Identidad</span></h3>
                                <p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Sincronización de parámetros personales del núcleo</p>
                            </div>
                            <div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-inner">
                                <span class="material-symbols-rounded text-2xl">person_edit</span>
                            </div>
                        </div>

                        <form @submit.prevent="updateProfile" class="space-y-8">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4">Nombre Completo</label>
                                    <div class="relative group">
                                        <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg">person</span>
                                        <input v-model="profileForm.name" class="w-full h-14 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all shadow-sm" required />
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4">Enlace de Comunicación</label>
                                    <div class="relative group">
                                        <span class="material-symbols-rounded absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant/40 group-focus-within:text-primary transition-colors text-lg">mail</span>
                                        <input v-model="profileForm.email" type="email" class="w-full h-14 pl-12 pr-4 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-primary/5 outline-none transition-all shadow-sm" required />
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-6 border-t border-outline-variant/5 dark:border-white/5">
                                <div class="flex items-center gap-4">
                                     <p v-if="profileForm.recentlySuccessful" class="text-[10px] font-black text-emerald-500 uppercase tracking-widest animate-pulse italic">¡NÚCLEO ACTUALIZADO!</p>
                                </div>
                                <Button type="submit" variant="primary" :loading="profileForm.processing" icon="sync_saved_locally" class="!rounded-2xl !h-14 !px-10 !text-[11px] font-black uppercase shadow-xl shadow-primary/10">Sincronizar Perfil</Button>
                            </div>
                        </form>
                    </Card>
                </div>

                <!-- Security Section -->
                <div class="lg:col-span-12 xl:col-span-5">
                    <Card class="!p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]">
                        <div class="flex justify-between items-start mb-10">
                            <div>
                                <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic">Cámara <span class="text-secondary font-black italic">Acorazada</span></h3>
                                <p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Renovación de llaves de acceso</p>
                            </div>
                            <div class="w-14 h-14 bg-secondary/10 rounded-2xl flex items-center justify-center text-secondary shadow-inner">
                                <span class="material-symbols-rounded text-2xl">shield_locked</span>
                            </div>
                        </div>

                        <form @submit.prevent="updatePassword" class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4">Clave Técnica Actual</label>
                                <input v-model="passwordForm.current_password" type="password" class="w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all" required />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4">Nueva Llave de Acceso</label>
                                <input v-model="passwordForm.password" type="password" class="w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all" required />
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] ml-4">Validar Nueva Llave</label>
                                <input v-model="passwordForm.password_confirmation" type="password" class="w-full h-14 px-6 bg-white dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 rounded-2xl text-xs font-bold text-on-surface dark:text-white focus:ring-4 focus:ring-secondary/5 outline-none transition-all" required />
                            </div>

                            <div class="pt-6">
                                <Button type="submit" variant="secondary" :loading="passwordForm.processing" icon="key_visualizer" class="w-full !rounded-2xl !h-14 !text-[11px] font-black uppercase shadow-xl shadow-secondary/10">Renovar Credenciales</Button>
                            </div>
                        </form>
                    </Card>
                </div>
            </div>

            <!-- Dashboard de Seguridad (Bento Inferior) -->
            <div class="bg-surface-container dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 rounded-[3.5rem] p-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="flex items-center gap-8 text-center md:text-left">
                    <div class="w-20 h-20 rounded-[2.5rem] bg-emerald-500/10 flex items-center justify-center shadow-inner relative overflow-hidden">
                        <div class="absolute inset-0 bg-emerald-500/10 animate-pulse"></div>
                        <span class="material-symbols-rounded text-4xl text-emerald-500 relative z-10">health_metrics</span>
                    </div>
                    <div>
                        <h4 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Salud de la Cuenta: <span class="text-emerald-500">EXCELENTE</span></h4>
                        <p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.2em] mt-3 leading-relaxed">Tu perfil cumple con todos los protocolos de seguridad de Nexo-Core.</p>
                    </div>
                </div>
                <div class="flex gap-4">
                    <div class="flex items-center gap-3 bg-white dark:bg-white/5 px-6 py-4 rounded-2xl border border-outline-variant/10 dark:border-white/5">
                        <span class="material-symbols-rounded text-emerald-500">security</span>
                        <span class="text-[10px] font-black text-on-surface dark:text-white uppercase tracking-widest">2FA ACTIVO</span>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
