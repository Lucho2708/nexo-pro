<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import ThemeToggle from '@/Components/UI/ThemeToggle.vue';
import Logo from '@/Components/UI/Logo.vue';
import Dropdown from '@/Components/UI/Dropdown.vue';
import AnnouncementModal from '@/Components/Announcements/AnnouncementModal.vue';
import NotificationCenter from '@/Components/Nav/NotificationCenter.vue';
import PropertySwitcher from '@/Components/Nav/PropertySwitcher.vue';

const page = usePage<any>();
const user = computed(() => page.props.auth?.user);

const isSidebarOpen = ref(true);
const showUserMenu = ref(false);
const isLargeScreen = ref(true);

onMounted(() => {
    isLargeScreen.value = window.innerWidth >= 1024;
    isSidebarOpen.value = isLargeScreen.value;
    
    window.addEventListener('resize', () => {
        isLargeScreen.value = window.innerWidth >= 1024;
        if (isLargeScreen.value) isSidebarOpen.value = true;
    });
});

router.on('navigate', () => {
    if (!isLargeScreen.value) isSidebarOpen.value = false;
});

// Helper para detectar ruta activa
const isRouteActive = (name: string) => {
    try {
        return route().current(name);
    } catch (e) {
        return false;
    }
};

const navigationItems = computed(() => {
    const permissions = user.value?.permissions || [];
    const settings = user.value?.copropiedad_settings;
    const isStandalone = user.value?.is_standalone || settings?.is_standalone;

    if (user.value?.role === 'super_admin') {
        return [
            { name: 'Inicio Global', icon: 'monitoring', href: route('superadmin.dashboard'), active: isRouteActive('superadmin.dashboard') },
            { name: 'Copropiedades', icon: 'business', href: route('superadmin.licenses.index'), active: isRouteActive('superadmin.licenses.*') },
            { name: 'Usuarios', icon: 'group', href: route('superadmin.users.index'), active: isRouteActive('superadmin.users.*') },
            { name: 'Auditoría', icon: 'history', href: route('superadmin.audit'), active: isRouteActive('superadmin.audit') },
            { name: 'Logs Sistema', icon: 'monitor_heart', href: route('superadmin.logs'), active: isRouteActive('superadmin.logs') },
            { name: 'Anuncios', icon: 'campaign', href: route('superadmin.announcements.index'), active: isRouteActive('superadmin.announcements.*') },
            { name: 'C. Soporte', icon: 'support_agent', href: route('superadmin.support.index'), active: isRouteActive('superadmin.support.*') },
            { name: 'Configuración Global', icon: 'settings_suggest', href: route('superadmin.settings.index'), active: isRouteActive('superadmin.settings.*') },
        ];
    }

    const items = [];

    // ── DASHBOARD INICIAL ───────────────────────────────────────
    items.push({ name: 'Inicio', icon: 'dashboard', href: route(user.value?.role === 'owner' ? 'owner.dashboard' : 'dashboard'), active: isRouteActive('dashboard') || isRouteActive('owner.dashboard') });

    // ── FINANZAS / CARTERA ──────────────────────────────────────
    if (permissions.includes('finance:manage') || permissions.includes('finance:view')) {
        const href = user.value?.role === 'owner' ? route('owner.payments') : route('cartera.index');
        items.push({ name: 'Cartera', icon: 'payments', href: href, active: isRouteActive('cartera.*') || isRouteActive('owner.payments') });
    }

    // ── INMUEBLES / ZONAS ───────────────────────────────────────
    if (permissions.includes('property:manage')) {
        items.push({ name: 'Zonas Comunes', icon: 'home_work', href: route('admin.zonas.index'), active: isRouteActive('admin.zonas.*') });
    }

    // ── COMUNIDAD (PQRS & Reservas) ─────────────────────────────
    if (permissions.includes('pqrs:manage') || permissions.includes('pqrs:create')) {
        items.push({ name: 'PQRS', icon: 'forum', href: route('pqrs.index'), active: isRouteActive('pqrs.index') });
    }

    if (permissions.includes('reservations:manage') || permissions.includes('reservations:create')) {
        const href = user.value?.role === 'owner' ? route('reservas.index') : route('admin.reservas.index');
        items.push({ name: 'Reservas', icon: 'event_available', href: href, active: isRouteActive('admin.reservas.index') || isRouteActive('reservas.index') });
    }

    // ── ASAMBLEAS ───────────────────────────────────────────────
    if (permissions.includes('assembly:admin') || permissions.includes('assembly:vote')) {
        // Solo mostrar si el módulo está activo para este conjunto
        if (settings?.asamblea_virtual_active) {
            const href = user.value?.role === 'admin' ? route('admin.asambleas.index') : '#'; // Los propietarios entran vía link o invitación
            if (user.value?.role === 'admin') {
                items.push({ name: 'Asambleas', icon: 'groups', href: href, active: isRouteActive('admin.asambleas.*') });
            }
        }
    }

    // ── CONFIGURACIÓN (Solo si no es Standalone) ────────────────
    if (permissions.includes('property:manage') && !isStandalone) {
        items.push({ name: 'Configuración', icon: 'settings', href: route('admin.settings'), active: isRouteActive('admin.settings') });
    }

    return items;
});

const configItems = computed(() => {
    const settings = user.value?.copropiedad_settings;
    const isStandalone = user.value?.is_standalone || settings?.is_standalone;

    if (user.value?.role === 'admin' && isStandalone) {
        return [];
    }

    const items = [
        { name: 'Mi Perfil', icon: 'manage_accounts', href: route('profile.edit'), active: isRouteActive('profile.edit') },
    ];

    if (user.value?.role !== 'super_admin') {
        items.push({ name: 'Soporte', icon: 'contact_support', href: route('support.index'), active: isRouteActive('support.index') });
    }

    return items;
});

const twoFactorStatus = computed(() => {
    return !!user.value?.two_factor_confirmed_at;
});
</script>

<template>
    <div class="min-h-screen bg-surface flex text-on-surface">
        <!-- Impersonation Bar -->
        <div v-if="user?.is_impersonating" class="fixed top-0 inset-x-0 z-[100] bg-error text-on-error px-4 py-2 flex items-center justify-center gap-4 shadow-xl">
            <span class="material-symbols-rounded text-sm animate-pulse">support_agent</span>
            <p class="text-[10px] font-black uppercase tracking-[0.2em]">Modo Soporte: Navegando como {{ user.name }}</p>
            <Link :href="route('superadmin.impersonate.stop')" method="post" as="button" class="bg-white/20 hover:bg-white/30 px-4 py-1 rounded-full text-[9px] font-black uppercase tracking-widest transition-all">Regresar a Super Admin</Link>
        </div>

        <!-- Sidebar Pro -->
        <aside 
            class="fixed inset-y-0 left-0 bg-surface-container-low dark:bg-[#0b0e14]/95 backdrop-blur-3xl border-r border-outline-variant/10 flex flex-col transition-all duration-500 z-[70] no-scrollbar shadow-2xl lg:translate-x-0"
            :class="[
                isSidebarOpen ? 'w-72 translate-x-0' : 'w-24 -translate-x-full lg:translate-x-0',
                !isSidebarOpen && 'lg:w-24'
            ]"
        >
            <div class="px-6 py-12 flex items-center justify-center">
                <Link :href="route('dashboard')" class="flex items-center gap-3">
                    <Logo :width="isSidebarOpen ? '45px' : '35px'" :height="isSidebarOpen ? '45px' : '35px'" :show-text="isSidebarOpen" class="transition-all duration-500" />
                </Link>
            </div>

            <nav class="flex-1 px-4 space-y-1">
                <Link 
                    v-for="item in navigationItems" 
                    :key="item.name" 
                    :href="item.href"
                    class="flex items-center gap-4 py-4 px-5 rounded-[1.5rem] transition-all group relative overflow-hidden"
                    :class="[
                        item.active 
                            ? 'bg-primary text-white shadow-xl shadow-primary/20 translate-x-1' 
                            : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'
                    ]"
                >
                    <div v-if="item.active" class="absolute left-0 top-1/4 bottom-1/4 w-1 bg-white rounded-full"></div>
                    
                    <span 
                        class="material-symbols-rounded shrink-0 transition-transform group-hover:scale-110" 
                        :class="[item.active ? 'text-white' : 'text-on-surface-variant/50 group-hover:text-primary']"
                    >
                        {{ item.icon }}
                    </span>
                    <span 
                        v-if="isSidebarOpen"
                        class="text-[10px] font-black uppercase tracking-[0.2em] whitespace-nowrap" 
                    >
                        {{ item.name }}
                    </span>
                </Link>
            </nav>

            <div class="px-4 border-t border-outline-variant/5 py-8 space-y-1">
                <Link 
                    v-for="item in configItems" 
                    :key="item.name" 
                    :href="item.href"
                    class="flex items-center gap-4 py-4 px-5 rounded-2xl transition-all group"
                    :class="[item.active ? 'bg-primary/5 text-primary' : 'text-on-surface-variant/50 hover:bg-surface-container-high hover:text-on-surface']"
                >
                    <span class="material-symbols-rounded shrink-0 text-xl group-hover:text-primary transition-colors text-lg">{{ item.icon }}</span>
                    <span v-if="isSidebarOpen" class="text-[10px] font-black uppercase tracking-widest">{{ item.name }}</span>
                </Link>
                
                <div v-if="isSidebarOpen" class="mt-8 relative group cursor-help">
                    <div class="absolute inset-0 blur-xl opacity-20 transition-all group-hover:opacity-40" :class="twoFactorStatus ? 'bg-emerald-500' : 'bg-rose-500'"></div>
                    <div class="relative p-5 rounded-[2rem] flex items-center gap-4 border transition-all" :class="twoFactorStatus ? 'bg-emerald-500/5 border-emerald-500/20' : 'bg-rose-500/10 border-rose-500/20'">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center shadow-inner" :class="twoFactorStatus ? 'bg-emerald-500/20' : 'bg-rose-500/20'">
                             <span class="material-symbols-rounded text-xl" :class="twoFactorStatus ? 'text-emerald-500' : 'text-rose-500'">{{ twoFactorStatus ? 'verified_user' : 'gpp_maybe' }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[9px] font-black uppercase tracking-widest" :class="twoFactorStatus ? 'text-emerald-500' : 'text-rose-500'">{{ twoFactorStatus ? 'Escudo Activo' : 'Riesgo Crítico' }}</span>
                            <span class="text-[7px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1">{{ twoFactorStatus ? 'IDENTIDAD PROTEGIDA' : 'CONFIGURA 2FA YA' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <button 
                v-if="isLargeScreen"
                @click="isSidebarOpen = !isSidebarOpen"
                class="absolute bottom-6 -right-3 w-8 h-8 bg-surface dark:bg-[#0b0e14] border border-outline-variant/20 rounded-xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-2xl z-50 text-on-surface-variant"
            >
                <span class="material-symbols-rounded text-xs transition-transform duration-500" :class="{'rotate-180': !isSidebarOpen}">chevron_left</span>
            </button>
        </aside>

        <!-- Main Content -->
        <main 
            class="flex-1 transition-all duration-500 min-h-screen w-full overflow-x-hidden"
            :class="[isLargeScreen ? (isSidebarOpen ? 'ml-72' : 'ml-24') : 'ml-0']"
        >
            <header class="sticky top-0 z-40 bg-surface/60 dark:bg-[#05070a]/60 backdrop-blur-2xl border-b border-outline-variant/10 px-6 md:px-10 py-6 flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-6">
                    <button @click="isSidebarOpen = !isSidebarOpen" class="lg:hidden p-3 rounded-2xl bg-primary/5 text-primary">
                        <span class="material-symbols-rounded">{{ isSidebarOpen ? 'menu_open' : 'menu' }}</span>
                    </button>
                    <div>
                        <h2 class="text-xs md:text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic">
                            HOLA, <span class="text-primary">{{ user?.name?.split(' ')[0] || '...' }}</span>
                        </h2>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"></div>
                            <p class="hidden sm:block text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]">
                                {{ new Date().toLocaleDateString('es-ES', { weekday: 'long', day: 'numeric', month: 'long' }).toUpperCase() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Multi-Tenant Property Switcher -->
                <div v-if="user?.role !== 'super_admin'" class="hidden lg:block flex-1 max-w-sm ml-12">
                    <PropertySwitcher />
                </div>

                <div class="flex items-center gap-8">
                    <ThemeToggle />
                    <NotificationCenter />

                    <div class="flex items-center gap-4 pl-6 border-l border-outline-variant/20 relative">
                        <div class="hidden lg:block text-right">
                            <p class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-tighter leading-none">{{ user?.name }}</p>
                            <p class="text-[8px] font-black text-primary uppercase tracking-widest mt-1 italic">{{ user?.role?.replace('_', ' ') }}</p>
                        </div>
                        <button @click="showUserMenu = !showUserMenu" class="relative group">
                            <div class="w-12 h-12 rounded-2xl bg-primary ring-4 ring-primary/10 overflow-hidden shadow-2xl group-hover:ring-primary/30 transition-all">
                                <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${user?.email}`" class="w-full h-full object-cover" />
                            </div>
                            <Transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="opacity-0 translate-y-4 scale-95"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition ease-in duration-100"
                                leave-to-class="opacity-0 translate-y-4 scale-95"
                            >
                                <div v-if="showUserMenu" class="absolute top-full right-0 mt-6 w-64 bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[2rem] p-4 shadow-2xl z-50">
                                    <div class="px-5 py-4 border-b border-outline-variant/5 dark:border-white/5 mb-2">
                                        <p class="text-xs font-black text-on-surface dark:text-white uppercase tracking-tighter">{{ user?.name }}</p>
                                        <p class="text-[9px] text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-1 italic italic">Nexo-Core Identity</p>
                                    </div>
                                    <Link :href="route('profile.edit')" class="w-full flex items-center gap-3 px-5 py-3.5 rounded-2xl hover:bg-primary/5 text-on-surface-variant hover:text-primary text-[10px] font-black uppercase tracking-widest transition-all">
                                        <span class="material-symbols-rounded text-lg">person</span>
                                        Mi Perfil
                                    </Link>
                                    <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center gap-3 px-5 py-3.5 rounded-2xl hover:bg-error/10 text-on-surface-variant hover:text-error text-[10px] font-black uppercase tracking-widest transition-all mt-1">
                                        <span class="material-symbols-rounded text-lg">logout</span>
                                        Cerrar Sesión
                                    </Link>
                                </div>
                            </Transition>
                        </button>
                    </div>
                </div>
            </header>

            <div class="p-8 md:p-12 max-w-screen-2xl mx-auto">
                <Transition name="page" mode="out-in" appear>
                    <div :key="page.component">
                        <slot />
                    </div>
                </Transition>
            </div>
        </main>

        <AnnouncementModal />
    </div>
</template>

<style>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
.no-scrollbar::-webkit-scrollbar { display: none; }

.page-enter-active, .page-leave-active { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
.page-enter-from { opacity: 0; transform: translateY(10px); }
.page-leave-to { opacity: 0; transform: translateY(-10px); }
</style>
