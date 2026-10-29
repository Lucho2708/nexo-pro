<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import ThemeToggle from '@/Components/UI/ThemeToggle.vue';
import Logo from '@/Components/UI/Logo.vue';
import Dropdown from '@/Components/UI/Dropdown.vue';
import NotificationCenter from '@/Components/Nav/NotificationCenter.vue';

const page = usePage();
// @ts-ignore
const user = computed(() => page.props.auth?.user);
const twoFactorEnabled = computed(() => !!page.props.features?.two_factor_enabled);
// @ts-ignore
const notifications = computed(() => page.props.notifications || []);
const unreadCount = computed(() => notifications.value.length);
// @ts-ignore
const availableCopropiedades = computed(() => page.props.auth?.user?.available_copropiedades || []);
// @ts-ignore
const currentCopropiedad = computed(() => page.props.auth?.user?.current_copropiedad || null);

const isSidebarExpanded = ref(true);
const isMobileMenuOpen = ref(false);
const showUserMenu = ref(false);
const windowWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1200);

const updateWidth = () => {
    if (typeof window === 'undefined') return;
    windowWidth.value = window.innerWidth;
    if (windowWidth.value >= 1024) isSidebarExpanded.value = true;
    else if (windowWidth.value >= 768) isSidebarExpanded.value = false;
    else isMobileMenuOpen.value = false;
};

onMounted(() => {
    window.addEventListener('resize', updateWidth);
    updateWidth();
});

onUnmounted(() => {
    if (typeof window !== 'undefined') {
        window.removeEventListener('resize', updateWidth);
    }
});

router.on('navigate', () => {
    isMobileMenuOpen.value = false;
    showUserMenu.value = false;
});

const navItems = [
    { name: 'Inicio',    icon: 'home',            href: route('owner.dashboard'),   active: route().current('owner.dashboard') },
    { name: 'Pagos',     icon: 'receipt_long',    href: route('owner.payments'),    active: route().current('owner.payments') },
    { name: 'Reservas',  icon: 'event_available', href: route('reservas.index'),    active: route().current('reservas.index') },
    { name: 'PQRS',      icon: 'chat_bubble',     href: route('pqrs.index'),        active: route().current('pqrs.index') },
];

const mobileNavItems = computed(() => navItems);

const logout = () => {
    router.post(route('logout'));
};

const switchCopropiedad = (propId: string) => {
    router.post(route('tenant.switch', propId), {}, {
        preserveState: false,
        onSuccess: () => {
            isMobileMenuOpen.value = false;
        }
    });
};
</script>

<template>
    <div class="min-h-screen bg-surface flex text-on-surface font-sans antialiased overflow-x-hidden w-full max-w-full">
        <!-- Desktop Tactical Sidebar -->
        <aside 
            class="hidden md:flex fixed inset-y-0 left-0 bg-surface-container-lowest border-r border-outline-variant/30 flex-col transition-all duration-300 z-40"
            :class="[isSidebarExpanded ? 'w-64' : 'w-20']"
        >
            <div class="p-5 flex justify-center items-center h-20 border-b border-outline-variant/10">
                <Link :href="route('owner.dashboard')" class="flex items-center overflow-hidden transition-all whitespace-nowrap">
                    <Logo :width="isSidebarExpanded ? '44px' : '32px'" :height="isSidebarExpanded ? '44px' : '32px'" :show-text="isSidebarExpanded" />
                </Link>
            </div>

            <nav class="flex-1 py-6 px-3 space-y-1.5 overflow-y-auto no-scrollbar">
                <Link 
                    v-for="item in navItems" 
                    :key="item.name" 
                    :href="item.href"
                    class="flex items-center py-3 px-3 mx-1 rounded-xl transition-all group overflow-hidden border border-transparent"
                    :class="[item.active ? 'bg-primary/10 text-primary font-bold shadow-sm border-primary/20' : 'text-on-surface-variant hover:bg-surface-container-high hover:text-on-surface']"
                    :title="item.name"
                >
                    <span class="material-symbols-rounded shrink-0 text-xl" :class="{ 'text-primary fill-current': item.active }">{{ item.icon }}</span>
                    <span class="ml-3 text-sm font-bold tracking-wide transition-opacity duration-300 whitespace-nowrap" :class="isSidebarExpanded ? 'opacity-100' : 'opacity-0 hidden'">{{ item.name }}</span>
                </Link>
            </nav>

            <div class="p-4 border-t border-outline-variant/20 space-y-3">
                <!-- 2FA Security Pill -->
                <div v-if="isSidebarExpanded" class="relative group cursor-help px-1">
                    <div class="absolute inset-0 blur-xl opacity-20 transition-all group-hover:opacity-40" :class="twoFactorEnabled ? 'bg-emerald-500' : 'bg-amber-500'"></div>
                    <div class="relative p-2.5 rounded-2xl flex items-center gap-3 border transition-all" :class="twoFactorEnabled ? 'bg-emerald-500/5 border-emerald-500/20' : 'bg-amber-500/10 border-amber-500/20'">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center shadow-inner shrink-0" :class="twoFactorEnabled ? 'bg-emerald-500/20' : 'bg-amber-500/20'">
                             <span class="material-symbols-rounded !text-[15px]" :class="twoFactorEnabled ? 'text-emerald-500' : 'text-amber-500'">{{ twoFactorEnabled ? 'verified_user' : 'gpp_maybe' }}</span>
                        </div>
                        <div class="flex flex-col min-w-0">
                            <span class="text-[9px] font-black uppercase tracking-wider truncate" :class="twoFactorEnabled ? 'text-emerald-500' : 'text-amber-500'">{{ twoFactorEnabled ? '2FA Activo' : '2FA Opcional' }}</span>
                            <span class="text-[7px] font-bold text-on-surface-variant/40 uppercase tracking-widest truncate">Seguridad de Cuenta</span>
                        </div>
                    </div>
                </div>
                <div v-else class="flex justify-center">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center border transition-all" :class="twoFactorEnabled ? 'bg-emerald-500/5 border-emerald-500/20' : 'bg-amber-500/10 border-amber-500/20'" :title="twoFactorEnabled ? '2FA Activo' : '2FA Opcional'">
                        <span class="material-symbols-rounded !text-[16px]" :class="twoFactorEnabled ? 'text-emerald-500' : 'text-amber-500'">{{ twoFactorEnabled ? 'verified_user' : 'gpp_maybe' }}</span>
                    </div>
                </div>

                <Link :href="route('support.index')" class="w-full flex items-center p-2.5 rounded-xl text-on-surface-variant hover:bg-primary/10 hover:text-primary transition-all group overflow-hidden border border-transparent">
                    <span class="material-symbols-rounded shrink-0 text-lg">support_agent</span>
                    <span class="ml-3 text-xs font-bold tracking-wide transition-opacity duration-300 whitespace-nowrap" :class="isSidebarExpanded ? 'opacity-100' : 'opacity-0 hidden'">Soporte Técnico</span>
                </Link>

                <button 
                    @click="isSidebarExpanded = !isSidebarExpanded"
                    class="w-full flex justify-center items-center p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors"
                    :aria-label="isSidebarExpanded ? 'Colapsar barra lateral' : 'Expandir barra lateral'"
                >
                    <span class="material-symbols-rounded text-lg">{{ isSidebarExpanded ? 'keyboard_double_arrow_left' : 'keyboard_double_arrow_right' }}</span>
                </button>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main 
            class="flex-1 flex flex-col transition-all duration-300 min-h-screen w-full max-w-full overflow-x-hidden"
            :class="[windowWidth >= 768 ? (isSidebarExpanded ? 'ml-64' : 'ml-20') : 'ml-0 pb-20']"
        >
            <div class="brand-accent-line sticky top-0 z-[50]"></div>

            <header class="sticky top-[3px] z-30 bg-surface/85 backdrop-blur-md border-b border-outline-variant/30 px-4 md:px-8 py-3.5 flex justify-between items-center transition-shadow">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="flex flex-col">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-primary animate-pulse shrink-0"></span>
                            <span class="text-[10px] font-black uppercase tracking-widest text-primary truncate">Portal Residencial</span>
                        </div>
                        <h1 class="text-base sm:text-lg font-black text-on-surface tracking-tight truncate leading-tight">
                            {{ currentCopropiedad?.nombre || 'Mi Copropiedad' }}
                        </h1>
                    </div>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-4">
                    <ThemeToggle />
                    
                    <!-- Multi-Copropiedad Selector (Desktop) -->
                    <div v-if="availableCopropiedades.length > 1" class="hidden lg:block border-l border-outline-variant/30 pl-3">
                        <Dropdown
                            label="Cambiar Conjunto"
                            variant="ghost"
                            size="sm"
                            icon="apartment"
                            :items="availableCopropiedades.map(prop => ({
                                label: prop.nombre,
                                icon: currentCopropiedad?.id === prop.id ? 'check_circle' : 'apartment',
                                action: () => switchCopropiedad(prop.id)
                            }))"
                        />
                    </div>

                    <NotificationCenter />

                    <!-- User Profile Button & Dropdown -->
                    <div class="relative flex items-center gap-2.5 border-l border-outline-variant/30 pl-2.5 sm:pl-4">
                        <div class="hidden sm:flex flex-col text-right">
                            <span class="text-xs font-black text-on-surface tracking-tight leading-none truncate max-w-[140px]">{{ user?.name }}</span>
                            <span class="text-[9px] font-bold text-primary uppercase tracking-widest mt-1">Propietario</span>
                        </div>
                        <button 
                            @click="showUserMenu = !showUserMenu" 
                            type="button"
                            aria-label="Menú de usuario"
                            :aria-expanded="showUserMenu"
                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-full border-2 border-primary/20 overflow-hidden hover:border-primary transition-all focus:outline-none focus-visible:ring-2 focus-visible:ring-primary cursor-pointer shrink-0"
                        >
                            <img 
                                :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'U')}&background=2563eb&color=fff&bold=true`" 
                                class="w-full h-full object-cover" 
                                :alt="user?.name || 'Usuario'"
                            />
                        </button>
                        <Transition enter-active-class="transition ease-out duration-150" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100" leave-active-class="transition ease-in duration-100" leave-to-class="opacity-0 scale-95">
                            <div v-if="showUserMenu" class="absolute top-full right-0 mt-3 w-56 bg-surface-container-lowest rounded-2xl shadow-xl border border-outline-variant/30 py-2 z-50">
                                <div class="px-4 py-2 border-b border-outline-variant/10 mb-1 sm:hidden">
                                    <p class="text-xs font-black text-on-surface truncate">{{ user?.name }}</p>
                                    <p class="text-[9px] text-primary font-bold uppercase tracking-widest">Propietario</p>
                                </div>
                                <Link :href="route('profile.edit')" class="w-full px-4 py-2.5 hover:bg-surface-container text-xs font-bold flex items-center gap-3 text-on-surface-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-rounded text-base">person</span> Mi Perfil
                                </Link>
                                <Link :href="route('support.index')" class="w-full px-4 py-2.5 hover:bg-surface-container text-xs font-bold flex items-center gap-3 text-on-surface-variant hover:text-primary transition-colors">
                                    <span class="material-symbols-rounded text-base">help</span> Soporte y Ayuda
                                </Link>
                                <button @click="logout" class="w-full px-4 py-2.5 hover:bg-error/10 text-xs font-bold flex items-center gap-3 text-error border-t border-outline-variant/10 pt-2 transition-colors cursor-pointer">
                                    <span class="material-symbols-rounded text-base">logout</span> Cerrar sesión
                                </button>
                            </div>
                        </Transition>
                    </div>
                </div>
            </header>

            <div class="px-4 py-6 md:p-8 max-w-[1400px] w-full mx-auto flex-1">
                <Transition name="page" mode="out-in" appear>
                    <div :key="page.component"><slot /></div>
                </Transition>
            </div>
        </main>

        <!-- Mobile Bottom Ergonomic Navigation Dock (4 Core + Drawer) -->
        <nav v-if="windowWidth < 768" class="fixed bottom-0 inset-x-0 bg-surface-container-lowest/95 backdrop-blur-lg border-t border-outline-variant/30 grid grid-cols-5 h-16 z-50 pb-safe shadow-[0_-4px_20px_rgba(0,0,0,0.06)]">
            <Link 
                v-for="item in mobileNavItems" 
                :key="item.name" 
                :href="item.href"
                class="flex flex-col items-center justify-center w-full h-full transition-colors relative"
                :class="item.active ? 'text-primary' : 'text-on-surface-variant hover:text-on-surface'"
            >
                <span class="material-symbols-rounded text-[22px]" :class="{ 'fill-current': item.active }">{{ item.icon }}</span>
                <span class="text-[10px] font-bold mt-0.5 tracking-tight">{{ item.name }}</span>
                <span v-if="item.active" class="absolute bottom-1 w-6 h-0.5 bg-primary rounded-full"></span>
            </Link>

            <button 
                @click="isMobileMenuOpen = true"
                class="flex flex-col items-center justify-center w-full h-full text-on-surface-variant hover:text-on-surface transition-colors"
                aria-label="Abrir menú complementario"
            >
                <span class="material-symbols-rounded text-[22px]">menu</span>
                <span class="text-[10px] font-bold mt-0.5 tracking-tight">Más</span>
            </button>
        </nav>

        <!-- Mobile Drawer Off-Canvas -->
        <Transition enter-active-class="transition-transform duration-300 ease-out" enter-from-class="translate-y-full" enter-to-class="translate-y-0" leave-active-class="transition-transform duration-200 ease-in" leave-from-class="translate-y-0" leave-to-class="translate-y-full">
            <div v-if="isMobileMenuOpen && windowWidth < 768" class="fixed inset-0 z-[60] flex flex-col bg-surface/95 backdrop-blur-xl">
                <div class="flex items-center justify-between p-5 border-b border-outline-variant/20">
                    <Logo width="36px" height="36px" :show-text="true" />
                    <button @click="isMobileMenuOpen = false" class="p-2 rounded-full bg-surface-container text-on-surface hover:bg-surface-container-high transition-colors" aria-label="Cerrar menú">
                        <span class="material-symbols-rounded text-xl">close</span>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto p-5 space-y-4">
                    <!-- User Card -->
                    <div class="p-4 rounded-2xl bg-surface-container flex items-center gap-4 border border-outline-variant/10">
                        <img 
                            :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(user?.name || 'U')}&background=2563eb&color=fff&bold=true`" 
                            class="w-12 h-12 rounded-xl object-cover shrink-0 border border-primary/20" 
                            :alt="user?.name || 'Usuario'"
                        />
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-black text-on-surface truncate">{{ user?.name }}</p>
                            <p class="text-xs text-on-surface-variant/70 truncate">{{ user?.email }}</p>
                            <span class="inline-flex items-center gap-1 mt-1 text-[9px] font-bold uppercase tracking-wider text-primary bg-primary/10 px-2 py-0.5 rounded-md">
                                <span class="material-symbols-rounded !text-[12px]">verified</span> Propietario
                            </span>
                        </div>
                    </div>

                    <!-- Multi-Copropiedad Switcher (Mobile) -->
                    <div v-if="availableCopropiedades.length > 1" class="space-y-2">
                        <p class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-widest pl-1">Tus Conjuntos Residenciales</p>
                        <div class="space-y-1.5">
                            <button
                                v-for="prop in availableCopropiedades"
                                :key="prop.id"
                                @click="switchCopropiedad(prop.id)"
                                class="w-full flex items-center justify-between p-3.5 rounded-xl border transition-all text-left"
                                :class="currentCopropiedad?.id === prop.id ? 'bg-primary/10 border-primary text-primary font-bold' : 'bg-surface-container border-outline-variant/10 text-on-surface'"
                            >
                                <div class="flex items-center gap-3 truncate">
                                    <span class="material-symbols-rounded text-lg">apartment</span>
                                    <span class="text-xs truncate">{{ prop.nombre }}</span>
                                </div>
                                <span v-if="currentCopropiedad?.id === prop.id" class="material-symbols-rounded text-primary text-base">check_circle</span>
                            </button>
                        </div>
                    </div>

                    <!-- Options List -->
                    <div class="space-y-2 pt-2">
                        <Link :href="route('profile.edit')" class="flex items-center p-3.5 bg-surface-container rounded-xl text-on-surface hover:bg-surface-container-high transition-colors">
                            <span class="material-symbols-rounded mr-3 text-primary">person</span>
                            <span class="text-xs font-bold">Editar Mi Perfil</span>
                        </Link>

                        <Link :href="route('support.index')" class="flex items-center p-3.5 bg-surface-container rounded-xl text-on-surface hover:bg-surface-container-high transition-colors">
                            <span class="material-symbols-rounded mr-3 text-secondary">support_agent</span>
                            <span class="text-xs font-bold">Soporte y Mesa de Ayuda</span>
                        </Link>
                    </div>

                    <!-- Logout Button -->
                    <div class="pt-4 border-t border-outline-variant/20">
                        <button 
                            @click="logout" 
                            class="w-full flex items-center justify-center gap-2 p-3.5 rounded-xl bg-error/10 text-error font-bold text-xs hover:bg-error/20 transition-colors"
                        >
                            <span class="material-symbols-rounded text-lg">logout</span>
                            <span>Cerrar Sesión</span>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </div>
</template>

<style>
.pb-safe { padding-bottom: max(env(safe-area-inset-bottom, 0px), 8px); }
.page-enter-active, .page-leave-active { transition: opacity 0.2s ease, transform 0.2s ease; }
.page-enter-from, .page-leave-to { opacity: 0; transform: translateY(4px); }
</style>
