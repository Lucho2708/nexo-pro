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

const isSidebarOpen = ref(false);
const showUserMenu = ref(false);
const isLargeScreen = ref(false);

onMounted(() => {
    isLargeScreen.value = window.innerWidth >= 1024;
    isSidebarOpen.value = isLargeScreen.value;
    
    window.addEventListener('resize', () => {
        const large = window.innerWidth >= 1024;
        if (large !== isLargeScreen.value) {
            isLargeScreen.value = large;
            isSidebarOpen.value = large;
        }
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

const homeRoute = computed(() => {
    if (user.value?.role === 'super_admin') return route('superadmin.dashboard');
    if (user.value?.role === 'owner') return route('owner.dashboard');
    if (user.value?.role === 'admin') return route('admin.dashboard');
    if (user.value?.role === 'security') return route('porteria.dashboard');
    if (user.value?.role === 'backoffice') return route('backoffice.leads.index');
    return route('dashboard');
});

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

    // ── DASHBOARD INICIAL SEGÚN ENTORNO ─────────────────────────
    items.push({ 
        name: 'Inicio', 
        icon: 'dashboard', 
        href: homeRoute.value, 
        active: isRouteActive('admin.dashboard') || isRouteActive('dashboard') || isRouteActive('owner.dashboard') 
    });

    // ── FINANZAS / CARTERA ──────────────────────────────────────
    if (permissions.includes('finance:manage') || permissions.includes('finance:view')) {
        const href = user.value?.role === 'owner' ? route('owner.payments') : route('admin.cartera.index');
        items.push({ name: 'Cartera', icon: 'payments', href: href, active: isRouteActive('admin.cartera.*') || isRouteActive('cartera.*') || isRouteActive('owner.payments') });
    }

    // ── INMUEBLES / ZONAS ───────────────────────────────────────
    if (permissions.includes('property:manage')) {
        items.push({ name: 'Zonas Comunes', icon: 'home_work', href: route('admin.zonas.index'), active: isRouteActive('admin.zonas.*') });
    }

    // ── COMUNIDAD (PQRS & Reservas) ─────────────────────────────
    if (permissions.includes('pqrs:manage') || permissions.includes('pqrs:create')) {
        const href = user.value?.role === 'admin' ? route('admin.pqrs.index') : route('pqrs.index');
        items.push({ name: 'PQRS', icon: 'forum', href: href, active: isRouteActive('admin.pqrs.*') || isRouteActive('pqrs.*') });
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

const mobileBottomNavItems = computed(() => {
    const items = [];
    const permissions = user.value?.permissions || [];
    const settings = user.value?.copropiedad_settings;
    const isStandalone = user.value?.is_standalone || settings?.is_standalone;

    if (user.value?.role === 'super_admin') {
        return [
            { name: 'Inicio', icon: 'monitoring', href: route('superadmin.dashboard'), active: isRouteActive('superadmin.dashboard') },
            { name: 'Conjuntos', icon: 'business', href: route('superadmin.licenses.index'), active: isRouteActive('superadmin.licenses.*') },
            { name: 'Usuarios', icon: 'group', href: route('superadmin.users.index'), active: isRouteActive('superadmin.users.*') },
            { name: 'Ajustes', icon: 'settings_suggest', href: route('superadmin.settings.index'), active: isRouteActive('superadmin.settings.*') },
        ];
    }

    items.push({ 
        name: 'Inicio', 
        icon: 'dashboard', 
        href: homeRoute.value, 
        active: isRouteActive('admin.dashboard') || isRouteActive('dashboard') || isRouteActive('owner.dashboard') 
    });

    if (permissions.includes('finance:manage') || permissions.includes('finance:view')) {
        const href = user.value?.role === 'owner' ? route('owner.payments') : route('admin.cartera.index');
        items.push({ name: 'Cartera', icon: 'payments', href: href, active: isRouteActive('admin.cartera.*') || isRouteActive('cartera.*') || isRouteActive('owner.payments') });
    }

    if (permissions.includes('pqrs:manage') || permissions.includes('pqrs:create')) {
        const href = user.value?.role === 'admin' ? route('admin.pqrs.index') : route('pqrs.index');
        items.push({ name: 'PQRS', icon: 'forum', href: href, active: isRouteActive('admin.pqrs.*') || isRouteActive('pqrs.*') });
    }

    if (permissions.includes('property:manage') && !isStandalone) {
        items.push({ name: 'Ajustes', icon: 'settings', href: route('admin.settings'), active: isRouteActive('admin.settings') });
    }

    return items.slice(0, 4);
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

        <!-- Mobile Drawer (Off-Canvas Modal, Teleported to Body to avoid overflow leakage) -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition-opacity duration-300 ease-out"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="transition-opacity duration-200 ease-in"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div 
                    v-if="!isLargeScreen && isSidebarOpen" 
                    class="fixed inset-0 z-[100] lg:hidden"
                    role="dialog"
                    aria-modal="true"
                    aria-label="Menú de Navegación"
                >
                    <!-- Backdrop -->
                    <div 
                        @click="isSidebarOpen = false" 
                        class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm cursor-pointer"
                    />

                    <!-- Drawer Sheet -->
                    <aside 
                        class="fixed inset-y-0 left-0 w-80 max-w-[85vw] bg-surface-container-low dark:bg-[#0b0e14] border-r border-outline-variant/10 dark:border-white/10 flex flex-col z-10 shadow-2xl overflow-hidden animate-in slide-in-from-left duration-300"
                    >
                        <div class="px-5 pt-6 pb-4 flex items-center justify-between border-b border-outline-variant/10">
                            <Link :href="homeRoute" class="flex items-center gap-3" @click="isSidebarOpen = false">
                                <Logo width="38px" height="38px" :show-text="true" />
                            </Link>
                            <button 
                                @click="isSidebarOpen = false"
                                class="w-10 h-10 rounded-2xl bg-surface-container-high hover:bg-surface-container-highest text-on-surface-variant flex items-center justify-center transition-all active:scale-95 cursor-pointer"
                                aria-label="Cerrar menú"
                            >
                                <span class="material-symbols-rounded text-xl">close</span>
                            </button>
                        </div>

                        <!-- Mobile Condominium Switcher -->
                        <div v-if="user?.role !== 'super_admin'" class="p-4 border-b border-outline-variant/5">
                            <PropertySwitcher />
                        </div>

                        <!-- Navigation Items -->
                        <nav class="flex-1 px-4 py-3 space-y-1.5 overflow-y-auto custom-scrollbar">
                            <Link 
                                v-for="item in navigationItems" 
                                :key="item.name" 
                                :href="item.href"
                                @click="isSidebarOpen = false"
                                class="flex items-center gap-4 py-3 px-4 rounded-2xl transition-all group relative overflow-hidden"
                                :class="[
                                    item.active 
                                        ? 'bg-primary text-white shadow-lg shadow-primary/20 translate-x-1' 
                                        : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'
                                ]"
                            >
                                <span 
                                    class="material-symbols-rounded shrink-0 text-xl"
                                    :class="item.active ? 'text-white' : 'text-primary/70'"
                                >
                                    {{ item.icon }}
                                </span>
                                <span class="text-xs font-black uppercase tracking-wider">
                                    {{ item.name }}
                                </span>
                            </Link>
                        </nav>

                        <!-- Config & Profile links -->
                        <div class="p-4 border-t border-outline-variant/10 space-y-1 bg-surface-container-lowest dark:bg-white/[0.02]">
                            <Link 
                                v-for="item in configItems" 
                                :key="item.name" 
                                :href="item.href"
                                @click="isSidebarOpen = false"
                                class="flex items-center gap-4 py-2.5 px-4 rounded-xl text-on-surface-variant hover:text-primary transition-all text-xs font-bold uppercase tracking-wider"
                            >
                                <span class="material-symbols-rounded text-lg">{{ item.icon }}</span>
                                <span>{{ item.name }}</span>
                            </Link>
                            <Link 
                                :href="route('logout')" 
                                method="post" 
                                as="button" 
                                class="w-full flex items-center gap-4 py-2.5 px-4 rounded-xl text-error hover:bg-error/10 transition-all text-xs font-black uppercase tracking-wider mt-1"
                            >
                                <span class="material-symbols-rounded text-lg">logout</span>
                                <span>Cerrar Sesión</span>
                            </Link>
                        </div>
                    </aside>
                </div>
            </Transition>
        </Teleport>

        <!-- Desktop Sidebar Pro (Rendered ONLY on lg+ screens, never on mobile) -->
        <aside 
            v-if="isLargeScreen"
            class="hidden lg:flex fixed inset-y-0 left-0 bg-surface-container-low dark:bg-[#0b0e14]/98 backdrop-blur-3xl border-r border-outline-variant/10 flex-col transition-all duration-300 z-[70] no-scrollbar shadow-2xl overflow-visible"
            :class="[isSidebarOpen ? 'w-72' : 'w-24']"
        >
            <div class="px-5 py-8 flex items-center justify-center">
                <Link :href="homeRoute" class="flex items-center gap-3">
                    <Logo :width="isSidebarOpen ? '42px' : '32px'" :height="isSidebarOpen ? '42px' : '32px'" :show-text="isSidebarOpen" class="transition-all duration-300" />
                </Link>
            </div>

            <nav class="flex-1 px-4 space-y-1 overflow-y-auto custom-scrollbar">
                <Link 
                    v-for="item in navigationItems" 
                    :key="item.name" 
                    :href="item.href"
                    class="flex items-center gap-4 py-3.5 px-4 rounded-[1.5rem] transition-all group relative overflow-hidden"
                    :class="[
                        item.active 
                            ? 'bg-primary text-white shadow-xl shadow-primary/20 translate-x-1' 
                            : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface'
                    ]"
                >
                    <div v-if="item.active" class="absolute left-0 top-1/4 bottom-1/4 w-1 bg-white rounded-full"></div>
                    
                    <span 
                        class="material-symbols-rounded shrink-0 transition-transform group-hover:scale-110 text-xl" 
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

            <div class="px-4 border-t border-outline-variant/5 py-6 space-y-1">
                <Link 
                    v-for="item in configItems" 
                    :key="item.name" 
                    :href="item.href"
                    class="flex items-center gap-4 py-3 px-4 rounded-2xl transition-all group"
                    :class="[item.active ? 'bg-primary/5 text-primary' : 'text-on-surface-variant/50 hover:bg-surface-container-high hover:text-on-surface']"
                >
                    <span class="material-symbols-rounded shrink-0 group-hover:text-primary transition-colors text-lg">{{ item.icon }}</span>
                    <span v-if="isSidebarOpen" class="text-[10px] font-black uppercase tracking-widest">{{ item.name }}</span>
                </Link>
                
                <div v-if="isSidebarOpen" class="mt-4 relative group cursor-help">
                    <div class="absolute inset-0 blur-xl opacity-20 transition-all group-hover:opacity-40" :class="twoFactorStatus ? 'bg-emerald-500' : 'bg-rose-500'"></div>
                    <div class="relative p-4 rounded-[2rem] flex items-center gap-3 border transition-all" :class="twoFactorStatus ? 'bg-emerald-500/5 border-emerald-500/20' : 'bg-rose-500/10 border-rose-500/20'">
                        <div class="w-9 h-9 rounded-2xl flex items-center justify-center shadow-inner shrink-0" :class="twoFactorStatus ? 'bg-emerald-500/20' : 'bg-rose-500/20'">
                             <span class="material-symbols-rounded text-lg" :class="twoFactorStatus ? 'text-emerald-500' : 'text-rose-500'">{{ twoFactorStatus ? 'verified_user' : 'gpp_maybe' }}</span>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[9px] font-black uppercase tracking-widest truncate" :class="twoFactorStatus ? 'text-emerald-500' : 'text-rose-500'">{{ twoFactorStatus ? 'Escudo Activo' : 'Riesgo Crítico' }}</span>
                            <span class="text-[7px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-0.5 truncate">{{ twoFactorStatus ? 'IDENTIDAD PROTEGIDA' : 'CONFIGURA 2FA YA' }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <button 
                @click="isSidebarOpen = !isSidebarOpen"
                class="absolute bottom-6 -right-3 w-8 h-8 bg-surface dark:bg-[#0b0e14] border border-outline-variant/20 rounded-xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-2xl z-50 text-on-surface-variant cursor-pointer"
                aria-label="Colapsar barra lateral"
            >
                <span class="material-symbols-rounded text-xs transition-transform duration-300" :class="{'rotate-180': !isSidebarOpen}">chevron_left</span>
            </button>
        </aside>

        <!-- Main Content -->
        <main 
            class="flex-1 flex flex-col transition-all duration-300 min-h-screen w-full min-w-0"
            :class="[isLargeScreen ? (isSidebarOpen ? 'ml-72' : 'ml-24') : 'ml-0']"
        >
            <header 
                class="sticky z-40 bg-surface/95 dark:bg-[#0b0e14]/95 backdrop-blur-xl border-b border-outline-variant/15 dark:border-white/5 px-3 sm:px-6 md:px-10 py-3 sm:py-5 flex justify-between items-center shadow-sm transition-all duration-200"
                :class="[user?.is_impersonating ? 'top-10' : 'top-0']"
            >
                <div class="flex items-center gap-2.5 sm:gap-6 min-w-0 flex-1">
                    <button 
                        @click="isSidebarOpen = true" 
                        class="lg:hidden w-10 h-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center active:scale-95 transition-all cursor-pointer shrink-0" 
                        aria-label="Abrir menú"
                    >
                        <span class="material-symbols-rounded text-xl">menu</span>
                    </button>
                    <div class="min-w-0 pr-1">
                        <h2 class="text-xs sm:text-lg md:text-xl font-black text-on-surface dark:text-white uppercase tracking-tight italic flex items-center gap-1.5 truncate">
                            HOLA, <span class="text-primary truncate max-w-[95px] sm:max-w-none">{{ user?.name?.split(' ')[0] || '...' }}</span>
                        </h2>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <div class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse shrink-0"></div>
                            <p class="text-[7.5px] sm:text-[9px] font-black text-on-surface-variant/50 dark:text-white/30 uppercase tracking-widest truncate max-w-[130px] sm:max-w-none">
                                {{ user?.current_copropiedad?.nombre || new Date().toLocaleDateString('es-ES', { weekday: 'short', day: 'numeric', month: 'short' }).toUpperCase() }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Multi-Tenant Property Switcher Desktop -->
                <div v-if="user?.role !== 'super_admin'" class="hidden lg:block flex-1 max-w-sm ml-12">
                    <PropertySwitcher />
                </div>

                <div class="flex items-center gap-1.5 sm:gap-4 md:gap-6 shrink-0">
                    <ThemeToggle />
                    <NotificationCenter />

                    <div class="flex items-center gap-2 sm:gap-4 pl-1.5 sm:pl-6 border-l border-outline-variant/20 relative">
                        <div class="hidden lg:block text-right">
                            <p class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-tighter leading-none">{{ user?.name }}</p>
                            <p class="text-[8px] font-black text-primary uppercase tracking-widest mt-1 italic">{{ user?.role?.replace('_', ' ') }}</p>
                        </div>
                        <button @click="showUserMenu = !showUserMenu" class="relative group cursor-pointer" aria-label="Menú de usuario">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-2xl bg-primary ring-2 sm:ring-4 ring-primary/10 overflow-hidden shadow-xl group-hover:ring-primary/30 transition-all flex items-center justify-center">
                                <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${user?.email}`" class="w-full h-full object-cover" alt="Avatar" />
                            </div>
                            <Transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="opacity-0 translate-y-4 scale-95"
                                enter-to-class="opacity-100 translate-y-0 scale-100"
                                leave-active-class="transition ease-in duration-100"
                                leave-to-class="opacity-0 translate-y-4 scale-95"
                            >
                                <div v-if="showUserMenu" class="absolute top-full right-0 mt-4 sm:mt-6 w-60 sm:w-64 bg-white dark:bg-[#0b0e14] border border-outline-variant/10 dark:border-white/5 rounded-[2rem] p-4 shadow-2xl z-50">
                                    <div class="px-4 py-3 border-b border-outline-variant/5 dark:border-white/5 mb-2">
                                        <p class="text-xs font-black text-on-surface dark:text-white uppercase tracking-tighter truncate">{{ user?.name }}</p>
                                        <p class="text-[8px] text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-0.5 italic truncate">{{ user?.email }}</p>
                                    </div>
                                    <Link :href="route('profile.edit')" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-primary/5 text-on-surface-variant hover:text-primary text-[10px] font-black uppercase tracking-widest transition-all">
                                        <span class="material-symbols-rounded text-lg">person</span>
                                        Mi Perfil
                                    </Link>
                                    <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-error/10 text-on-surface-variant hover:text-error text-[10px] font-black uppercase tracking-widest transition-all mt-1">
                                        <span class="material-symbols-rounded text-lg">logout</span>
                                        Cerrar Sesión
                                    </Link>
                                </div>
                            </Transition>
                        </button>
                    </div>
                </div>
            </header>

            <!-- Responsive Main Content Wrapper -->
            <div class="p-3.5 sm:p-6 md:p-10 lg:p-12 max-w-screen-2xl mx-auto w-full pb-28 lg:pb-12">
                <Transition name="page" mode="out-in" appear>
                    <div :key="page.component">
                        <slot />
                    </div>
                </Transition>
            </div>
        </main>

        <!-- Mobile Bottom Floating Navigation Bar (Thumb Zone) -->
        <nav 
            v-if="!isLargeScreen" 
            class="fixed bottom-0 inset-x-0 z-50 bg-white/95 dark:bg-[#0b0e14]/95 backdrop-blur-2xl border-t border-outline-variant/10 dark:border-white/10 px-2 py-1.5 flex items-center justify-around shadow-2xl safe-area-bottom lg:hidden"
        >
            <Link 
                v-for="item in mobileBottomNavItems" 
                :key="item.name" 
                :href="item.href"
                class="flex flex-col items-center justify-center py-1.5 px-3 rounded-2xl transition-all relative select-none"
                :class="item.active ? 'text-primary' : 'text-on-surface-variant/60 hover:text-on-surface dark:hover:text-white'"
            >
                <span class="material-symbols-rounded text-2xl transition-transform" :class="item.active ? 'scale-110 font-black' : ''">
                    {{ item.icon }}
                </span>
                <span class="text-[9px] font-black uppercase tracking-wider mt-0.5">
                    {{ item.name }}
                </span>
                <span v-if="item.active" class="w-1.5 h-1.5 bg-primary rounded-full mt-0.5 shadow-[0_0_8px_rgba(var(--primary),0.8)]"></span>
            </Link>
            
            <!-- Botón Más / Menú Completo -->
            <button 
                @click="isSidebarOpen = true"
                class="flex flex-col items-center justify-center py-1.5 px-3 rounded-2xl transition-all text-on-surface-variant/60 hover:text-primary active:scale-95 cursor-pointer select-none"
                aria-label="Abrir menú completo"
            >
                <span class="material-symbols-rounded text-2xl">grid_view</span>
                <span class="text-[9px] font-black uppercase tracking-wider mt-0.5">Más</span>
            </button>
        </nav>

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
