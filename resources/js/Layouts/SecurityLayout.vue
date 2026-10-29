<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import ThemeToggle from '@/Components/UI/ThemeToggle.vue';
import Logo from '@/Components/UI/Logo.vue';
import RelevoTurnoModal from '@/Components/Porteria/RelevoTurnoModal.vue';

const page = usePage<any>();
const user = computed(() => page.props.auth?.user);
const copropiedad = computed(() => user.value?.current_copropiedad);
const showRelevoModal = ref(false);
const isMobileDrawerOpen = ref(false);

// Reloj Digital Táctico en Tiempo Real (Indispensable para garitas y turnos de vigilancia)
const currentTime = ref('');
const currentDate = ref('');

const updateClock = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString('es-CO', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit', 
        hour12: true 
    });
    currentDate.value = now.toLocaleDateString('es-CO', { 
        weekday: 'short', 
        day: 'numeric', 
        month: 'short' 
    });
};

onMounted(() => {
    updateClock();
    const interval = setInterval(updateClock, 1000);
    onUnmounted(() => clearInterval(interval));
});

router.on('navigate', () => {
    isMobileDrawerOpen.value = false;
});

const navItems = [
    { name: 'Consola Garita', icon: 'shield_person', route: 'porteria.dashboard', shortName: 'Garita' },
    { name: 'Visitas', icon: 'badge', route: 'porteria.visitas.index', shortName: 'Visitas' },
    { name: 'Recibos Públicos', icon: 'receipt_long', route: 'porteria.recibos.index', shortName: 'Recibos' },
    { name: 'Paquetería', icon: 'package_2', route: 'porteria.paquetes.index', shortName: 'Paquetes' },
    { name: 'Parqueaderos', icon: 'local_parking', route: 'porteria.parqueaderos.index', shortName: 'Parqueo' },
    { name: 'Minuta Forense', icon: 'menu_book', route: 'porteria.minuta.index', shortName: 'Minuta' },
    { name: 'Supervisión B2B', icon: 'shield_person', route: 'porteria.supervision', shortName: 'Supervisión' },
];

const mobilePrimaryRoutes = [
    'porteria.dashboard',
    'porteria.visitas.index',
    'porteria.paquetes.index',
    'porteria.parqueaderos.index',
];

const isRouteActive = (name: string) => {
    try {
        return route().current(name);
    } catch (e) {
        return false;
    }
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="min-h-screen bg-surface dark:bg-[#06080D] flex flex-col text-on-surface w-full max-w-full overflow-x-hidden font-sans antialiased transition-colors duration-300">
        
        <!-- ========================================== -->
        <!-- 1. DESKTOP TACTICAL SIDEBAR (lg:flex)     -->
        <!-- ========================================== -->
        <aside class="hidden lg:flex fixed inset-y-0 left-0 w-64 bg-surface-container-lowest/90 dark:bg-[#0A0D14]/95 backdrop-blur-2xl border-r border-outline-variant/15 dark:border-white/10 flex-col z-40 transition-all duration-300 shadow-xl">
            <!-- Brand & Garita Status Header -->
            <div class="p-5 border-b border-outline-variant/10 dark:border-white/5">
                <Link :href="route('porteria.dashboard')" class="flex items-center gap-3 group">
                    <div class="relative shrink-0">
                        <Logo width="40px" height="40px" :show-text="false" />
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 border-2 border-surface dark:border-[#0A0D14] animate-pulse"></span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[9px] font-black uppercase tracking-[0.2em] text-emerald-500 flex items-center gap-1">
                                <span class="material-symbols-rounded !text-[12px] notranslate" translate="no">verified</span>
                                Garita Activa
                            </span>
                        </div>
                        <h1 class="text-xs font-black uppercase tracking-tight text-on-surface dark:text-white truncate mt-0.5 group-hover:text-primary transition-colors">
                            {{ copropiedad?.nombre || 'Portería Principal' }}
                        </h1>
                    </div>
                </Link>

                <!-- Reloj Digital Táctico en Vivo -->
                <div class="mt-4 p-3 rounded-2xl bg-surface-container/60 dark:bg-white/5 border border-outline-variant/10 dark:border-white/5 flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-1">
                            <span class="material-symbols-rounded !text-[12px] text-primary notranslate" translate="no">schedule</span>
                            Hora Garita
                        </p>
                        <p class="text-sm font-black font-mono tracking-tight text-on-surface dark:text-white mt-0.5">
                            {{ currentTime || '--:--:--' }}
                        </p>
                    </div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-primary px-2 py-0.5 rounded-lg bg-primary/10 border border-primary/20">
                        {{ currentDate || 'Hoy' }}
                    </span>
                </div>
            </div>

            <!-- Navegación Vertical por Módulos -->
            <nav class="flex-1 py-4 px-3 space-y-1.5 overflow-y-auto no-scrollbar" aria-label="Navegación de Portería">
                <Link 
                    v-for="item in navItems" 
                    :key="item.name"
                    :href="route(item.route)"
                    class="group relative min-h-[48px] px-3.5 py-2.5 rounded-2xl flex items-center gap-3 text-xs font-black uppercase tracking-wider transition-all duration-200 border"
                    :class="[
                        isRouteActive(item.route)
                            ? 'bg-primary text-white border-primary/80 shadow-md shadow-primary/25 translate-x-1'
                            : 'bg-transparent border-transparent text-on-surface-variant hover:bg-surface-container-high/60 hover:text-on-surface'
                    ]"
                >
                    <!-- Barra de activación visual -->
                    <span 
                        v-if="isRouteActive(item.route)" 
                        class="absolute -left-1 top-2.5 bottom-2.5 w-1 rounded-full bg-white dark:bg-primary-container"
                    ></span>
                    
                    <span 
                        class="material-symbols-rounded text-xl shrink-0 transition-transform group-hover:scale-110 notranslate" 
                        translate="no"
                        :class="isRouteActive(item.route) ? 'text-white' : 'text-primary/70 group-hover:text-primary'"
                    >
                        {{ item.icon }}
                    </span>
                    <span class="truncate">{{ item.name }}</span>
                </Link>
            </nav>

            <!-- Footer Sidebar: Guarda en Turno, Relevo & Logout -->
            <div class="p-4 border-t border-outline-variant/10 dark:border-white/5 space-y-3 bg-surface-container-lowest/50 dark:bg-black/20">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="w-8 h-8 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-black text-xs shrink-0 border border-primary/20">
                            {{ (user?.name || 'G').charAt(0).toUpperCase() }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-black text-on-surface dark:text-white uppercase truncate leading-tight">
                                {{ user?.name || 'Guarda' }}
                            </p>
                            <p class="text-[9px] font-black text-primary uppercase tracking-widest">
                                En Servicio
                            </p>
                        </div>
                    </div>
                    <ThemeToggle />
                </div>

                <!-- Botón de Relevo de Turno por PIN -->
                <button 
                    @click="showRelevoModal = true"
                    type="button"
                    class="w-full min-h-[44px] px-3.5 py-2 rounded-xl bg-primary/10 hover:bg-primary hover:text-white text-primary font-black text-xs uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2 border border-primary/20 shadow-sm active:scale-95"
                >
                    <span class="material-symbols-rounded text-lg notranslate" translate="no">badge</span>
                    <span>Relevar Turno</span>
                </button>

                <!-- Botón de Cerrar Sesión -->
                <button 
                    @click="logout"
                    type="button"
                    class="w-full min-h-[40px] px-3.5 py-1.5 rounded-xl bg-surface-container/60 hover:bg-rose-500/10 text-on-surface-variant hover:text-rose-500 text-[10px] font-black uppercase tracking-wider transition-all duration-200 flex items-center justify-center gap-2 border border-outline-variant/15"
                >
                    <span class="material-symbols-rounded text-base notranslate" translate="no">logout</span>
                    <span>Cerrar Sesión</span>
                </button>
            </div>
        </aside>

        <!-- ========================================== -->
        <!-- 2. MAIN VIEWPORT & TOPBAR (Desktop & Mobile) -->
        <!-- ========================================== -->
        <div class="flex-1 flex flex-col min-w-0 w-full max-w-full lg:pl-64">
            
            <!-- Minimalist Topbar for Mobile / Desktop -->
            <header class="sticky top-0 z-30 bg-surface/90 dark:bg-[#06080D]/90 backdrop-blur-xl border-b border-outline-variant/15 dark:border-white/10 px-4 md:px-8 py-3 flex items-center justify-between shadow-sm">
                <!-- Mobile Left: Hamburger + Logo & Copropiedad -->
                <div class="flex items-center gap-3 min-w-0">
                    <button 
                        @click="isMobileDrawerOpen = true"
                        type="button"
                        aria-label="Abrir menú de portería"
                        class="lg:hidden min-h-[44px] min-w-[44px] p-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-on-surface flex items-center justify-center border border-outline-variant/15 active:scale-95"
                    >
                        <span class="material-symbols-rounded text-xl notranslate" translate="no">menu</span>
                    </button>

                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="lg:hidden shrink-0">
                            <Logo width="32px" height="32px" :show-text="false" />
                        </div>
                        <div class="min-w-0">
                            <span class="hidden sm:inline-block text-[9px] font-black uppercase tracking-[0.2em] text-emerald-500">
                                Garita NEXO-PRO
                            </span>
                            <h2 class="text-xs md:text-sm font-black uppercase tracking-tight text-on-surface dark:text-white truncate">
                                {{ copropiedad?.nombre || 'Control de Portería' }}
                            </h2>
                        </div>
                    </div>
                </div>

                <!-- Right Quick Controls -->
                <div class="flex items-center gap-2.5">
                    <!-- Live Clock (Mobile Tablet visible) -->
                    <div class="hidden sm:flex lg:hidden items-center gap-2 px-3 py-1.5 rounded-xl bg-surface-container text-xs font-mono font-bold text-on-surface-variant border border-outline-variant/15">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                        <span>{{ currentTime }}</span>
                    </div>

                    <!-- Relevo Rápido Móvil -->
                    <button 
                        @click="showRelevoModal = true"
                        type="button"
                        title="Relevar Turno por PIN"
                        class="min-h-[44px] px-3 sm:px-4 py-2 rounded-xl bg-primary text-white font-black text-xs uppercase tracking-wider transition-all flex items-center gap-1.5 shadow-md shadow-primary/20 active:scale-95"
                    >
                        <span class="material-symbols-rounded text-lg notranslate" translate="no">badge</span>
                        <span class="hidden sm:inline">Relevar Turno</span>
                    </button>

                    <div class="lg:hidden">
                        <ThemeToggle />
                    </div>

                    <button 
                        @click="logout"
                        type="button"
                        title="Salir"
                        class="hidden md:flex lg:hidden min-h-[44px] min-w-[44px] p-2 rounded-xl bg-surface-container hover:bg-rose-500/10 text-on-surface-variant hover:text-rose-500 items-center justify-center border border-outline-variant/15"
                    >
                        <span class="material-symbols-rounded text-lg notranslate" translate="no">logout</span>
                    </button>
                </div>
            </header>

            <!-- Main Work Area for Portería Views -->
            <main class="flex-1 w-full max-w-7xl mx-auto p-4 md:p-8 pb-28 lg:pb-8">
                <slot />
            </main>
        </div>

        <!-- ========================================== -->
        <!-- 3. MOBILE STICKY BOTTOM BAR (Thumb Zone)   -->
        <!-- ========================================== -->
        <nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-surface/95 dark:bg-[#0A0D14]/95 backdrop-blur-2xl border-t border-outline-variant/20 dark:border-white/10 px-2 py-1.5 shadow-2xl flex items-center justify-around" aria-label="Navegación inferior móvil">
            <Link 
                :href="route('porteria.dashboard')"
                class="flex flex-col items-center justify-center min-h-[48px] min-w-[48px] px-2 py-1 rounded-xl transition-all"
                :class="isRouteActive('porteria.dashboard') ? 'text-primary font-black scale-105' : 'text-on-surface-variant hover:text-on-surface'"
            >
                <span class="material-symbols-rounded text-xl notranslate" translate="no">shield_person</span>
                <span class="text-[9px] font-black uppercase tracking-wider mt-0.5">Garita</span>
            </Link>

            <Link 
                :href="route('porteria.visitas.index')"
                class="flex flex-col items-center justify-center min-h-[48px] min-w-[48px] px-2 py-1 rounded-xl transition-all"
                :class="isRouteActive('porteria.visitas.index') ? 'text-primary font-black scale-105' : 'text-on-surface-variant hover:text-on-surface'"
            >
                <span class="material-symbols-rounded text-xl notranslate" translate="no">badge</span>
                <span class="text-[9px] font-black uppercase tracking-wider mt-0.5">Visitas</span>
            </Link>

            <Link 
                :href="route('porteria.paquetes.index')"
                class="flex flex-col items-center justify-center min-h-[48px] min-w-[48px] px-2 py-1 rounded-xl transition-all"
                :class="isRouteActive('porteria.paquetes.index') ? 'text-primary font-black scale-105' : 'text-on-surface-variant hover:text-on-surface'"
            >
                <span class="material-symbols-rounded text-xl notranslate" translate="no">package_2</span>
                <span class="text-[9px] font-black uppercase tracking-wider mt-0.5">Paquetes</span>
            </Link>

            <Link 
                :href="route('porteria.parqueaderos.index')"
                class="flex flex-col items-center justify-center min-h-[48px] min-w-[48px] px-2 py-1 rounded-xl transition-all"
                :class="isRouteActive('porteria.parqueaderos.index') ? 'text-primary font-black scale-105' : 'text-on-surface-variant hover:text-on-surface'"
            >
                <span class="material-symbols-rounded text-xl notranslate" translate="no">local_parking</span>
                <span class="text-[9px] font-black uppercase tracking-wider mt-0.5">Parqueo</span>
            </Link>

            <!-- Toggle Menú Completo -->
            <button 
                @click="isMobileDrawerOpen = true"
                type="button"
                aria-label="Abrir menú completo de módulos"
                class="flex flex-col items-center justify-center min-h-[48px] min-w-[48px] px-2 py-1 rounded-xl text-on-surface-variant hover:text-on-surface transition-all"
                :class="{ 'text-primary font-black': !mobilePrimaryRoutes.some(r => isRouteActive(r)) }"
            >
                <span class="material-symbols-rounded text-xl notranslate" translate="no">grid_view</span>
                <span class="text-[9px] font-black uppercase tracking-wider mt-0.5">Más</span>
            </button>
        </nav>

        <!-- ========================================== -->
        <!-- 4. MOBILE SLIDING DRAWER (Full Navigation) -->
        <!-- ========================================== -->
        <Teleport to="body">
            <div 
                v-if="isMobileDrawerOpen" 
                class="fixed inset-0 z-50 lg:hidden flex"
                role="dialog"
                aria-modal="true"
            >
                <!-- Backdrop oscuro animado -->
                <div 
                    @click="isMobileDrawerOpen = false" 
                    class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity animate-in fade-in"
                ></div>

                <!-- Panel Lateral Deslizante -->
                <div class="relative w-full max-w-xs bg-surface dark:bg-[#0A0D14] border-r border-outline-variant/20 h-full flex flex-col z-10 shadow-2xl animate-in slide-in-from-left duration-200">
                    <!-- Drawer Header -->
                    <div class="p-5 border-b border-outline-variant/10 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <Logo width="36px" height="36px" :show-text="false" />
                            <div>
                                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-emerald-500">Módulo Garita</span>
                                <h3 class="text-xs font-black uppercase text-on-surface dark:text-white truncate">
                                    {{ copropiedad?.nombre || 'Portería' }}
                                </h3>
                            </div>
                        </div>
                        <button 
                            @click="isMobileDrawerOpen = false"
                            type="button"
                            class="min-h-[44px] min-w-[44px] p-2 rounded-xl text-on-surface-variant hover:text-on-surface hover:bg-surface-container flex items-center justify-center"
                        >
                            <span class="material-symbols-rounded notranslate" translate="no">close</span>
                        </button>
                    </div>

                    <!-- Reloj en Drawer -->
                    <div class="px-5 py-3 border-b border-outline-variant/10 bg-surface-container/30 flex items-center justify-between">
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest text-on-surface-variant">Hora Oficial</p>
                            <p class="text-xs font-black font-mono text-on-surface dark:text-white">{{ currentTime }}</p>
                        </div>
                        <span class="text-[10px] font-bold uppercase text-primary">{{ currentDate }}</span>
                    </div>

                    <!-- Drawer Nav Items (Todos los 7 módulos) -->
                    <div class="flex-1 py-4 px-3 space-y-1 overflow-y-auto no-scrollbar">
                        <Link 
                            v-for="item in navItems" 
                            :key="item.name"
                            :href="route(item.route)"
                            @click="isMobileDrawerOpen = false"
                            class="min-h-[50px] px-4 py-3 rounded-2xl flex items-center gap-3.5 text-xs font-black uppercase tracking-wider transition-all border"
                            :class="[
                                isRouteActive(item.route)
                                    ? 'bg-primary text-white border-primary shadow-md shadow-primary/25'
                                    : 'bg-transparent border-transparent text-on-surface-variant hover:bg-surface-container-high'
                            ]"
                        >
                            <span class="material-symbols-rounded text-xl notranslate" translate="no">{{ item.icon }}</span>
                            <span>{{ item.name }}</span>
                        </Link>
                    </div>

                    <!-- Drawer Footer -->
                    <div class="p-5 border-t border-outline-variant/10 space-y-3 bg-surface-container/30">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-primary/10 text-primary flex items-center justify-center font-black text-sm border border-primary/20 shrink-0">
                                {{ (user?.name || 'G').charAt(0).toUpperCase() }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-black text-on-surface dark:text-white uppercase truncate">
                                    {{ user?.name }}
                                </p>
                                <p class="text-[9px] font-black text-primary uppercase tracking-widest">
                                    Guarda de Turno
                                </p>
                            </div>
                        </div>

                        <button 
                            @click="showRelevoModal = true; isMobileDrawerOpen = false"
                            type="button"
                            class="w-full min-h-[48px] px-4 py-2.5 rounded-xl bg-primary text-white font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 shadow-md shadow-primary/20"
                        >
                            <span class="material-symbols-rounded notranslate" translate="no">badge</span>
                            <span>Relevar Turno</span>
                        </button>

                        <button 
                            @click="logout"
                            type="button"
                            class="w-full min-h-[44px] px-4 py-2 rounded-xl bg-surface-container hover:bg-rose-500/10 text-rose-500 font-black text-xs uppercase tracking-wider flex items-center justify-center gap-2 border border-outline-variant/15"
                        >
                            <span class="material-symbols-rounded notranslate" translate="no">logout</span>
                            <span>Cerrar Sesión</span>
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>

        <!-- Relevo Rápido de Turno Modal -->
        <RelevoTurnoModal
            :show="showRelevoModal"
            :guarda-actual="user?.name"
            @close="showRelevoModal = false"
        />
    </div>
</template>
