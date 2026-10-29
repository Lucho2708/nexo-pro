<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import ThemeToggle from '@/Components/UI/ThemeToggle.vue';
import Logo from '@/Components/UI/Logo.vue';
import RelevoTurnoModal from '@/Components/Porteria/RelevoTurnoModal.vue';

const page = usePage<any>();
const user = computed(() => page.props.auth?.user);
const copropiedad = computed(() => user.value?.current_copropiedad);
const showRelevoModal = ref(false);

const navItems = [
    { name: 'Consola Garita', icon: 'shield_person', route: 'porteria.dashboard' },
    { name: 'Visitas', icon: 'badge', route: 'porteria.visitas.index' },
    { name: 'Recibos Públicos', icon: 'receipt_long', route: 'porteria.recibos.index' },
    { name: 'Paquetería', icon: 'package_2', route: 'porteria.paquetes.index' },
    { name: 'Parqueaderos', icon: 'local_parking', route: 'porteria.parqueaderos.index' },
    { name: 'Minuta Forense', icon: 'menu_book', route: 'porteria.minuta.index' },
    { name: 'Supervisión B2B', icon: 'shield_person', route: 'porteria.supervision' },
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
    <div class="min-h-screen bg-surface dark:bg-[#06080D] flex flex-col text-on-surface transition-colors duration-300 font-sans">
        <!-- Persistent Tactical Header for Security Gatehouse -->
        <header class="sticky top-0 z-40 bg-surface/95 dark:bg-[#0b0e14]/95 backdrop-blur-xl border-b border-outline-variant/20 dark:border-white/10 px-4 md:px-8 py-3.5 flex items-center justify-between shadow-md">
            <div class="flex items-center gap-6">
                <Link :href="route('porteria.dashboard')" class="flex items-center gap-3">
                    <Logo width="38px" height="38px" :show-text="false" />
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                            <span class="text-[9px] font-black uppercase tracking-[0.25em] text-emerald-500">Módulo Garita Activo</span>
                        </div>
                        <h1 class="text-xs md:text-sm font-black uppercase tracking-tight text-on-surface dark:text-white truncate">
                            {{ copropiedad?.nombre || 'Portería Principal' }}
                        </h1>
                    </div>
                </Link>
            </div>

            <!-- Navigation Links (Large Touch Targets >= 48px) -->
            <nav class="hidden xl:flex items-center gap-2">
                <Link 
                    v-for="item in navItems" 
                    :key="item.name"
                    :href="route(item.route)"
                    class="min-h-[48px] px-4 py-2.5 rounded-2xl flex items-center gap-2.5 text-xs font-black uppercase tracking-wider transition-all duration-200 border"
                    :class="[
                        isRouteActive(item.route)
                            ? 'bg-primary text-white border-primary shadow-lg shadow-primary/20 scale-[1.02]'
                            : 'bg-surface-container/40 dark:bg-white/5 border-transparent text-on-surface-variant hover:bg-surface-container hover:text-on-surface'
                    ]"
                >
                    <span class="material-symbols-rounded text-lg">{{ item.icon }}</span>
                    <span>{{ item.name }}</span>
                </Link>
            </nav>

            <div class="flex items-center gap-4">
                <ThemeToggle />

                <div class="flex items-center gap-3 pl-4 border-l border-outline-variant/20">
                    <div class="hidden sm:block text-right">
                        <p class="text-xs font-black text-on-surface dark:text-white uppercase leading-none">{{ user?.name }}</p>
                        <p class="text-[9px] font-black text-primary uppercase tracking-widest mt-1">Guarda de Turno</p>
                    </div>

                    <!-- Relevar Turno Táctico por PIN -->
                    <button 
                        @click="showRelevoModal = true"
                        type="button"
                        title="Relevo Rápido de Turno por PIN"
                        class="min-h-[48px] px-3.5 py-2.5 rounded-2xl bg-primary/10 text-primary hover:bg-primary hover:text-white font-bold text-xs uppercase tracking-wider transition-all flex items-center gap-2 border border-primary/25 active:scale-95 shadow-sm"
                    >
                        <span class="material-symbols-rounded text-lg">badge</span>
                        <span class="hidden md:inline">Relevar Turno</span>
                    </button>

                    <button 
                        @click="logout"
                        type="button"
                        title="Cerrar Sesión Completa"
                        class="min-h-[44px] min-w-[44px] p-2.5 rounded-2xl bg-surface-container hover:bg-rose-500/10 text-on-surface-variant hover:text-rose-500 transition-all flex items-center justify-center border border-outline-variant/20"
                    >
                        <span class="material-symbols-rounded text-lg">logout</span>
                    </button>
                </div>
            </div>
        </header>

        <!-- Mobile / Tablet Quick Navigation Bar -->
        <div class="xl:hidden bg-surface-container-high/60 dark:bg-[#0d121c] border-b border-outline-variant/10 px-4 py-2.5 flex items-center gap-2 overflow-x-auto no-scrollbar">
            <Link 
                v-for="item in navItems" 
                :key="item.name"
                :href="route(item.route)"
                class="min-h-[44px] px-3.5 py-2 rounded-xl flex items-center gap-2 text-[10px] font-black uppercase tracking-wider whitespace-nowrap shrink-0 transition-all border"
                :class="[
                    isRouteActive(item.route)
                        ? 'bg-primary text-white border-primary shadow-sm'
                        : 'bg-surface dark:bg-white/5 text-on-surface-variant border-outline-variant/10'
                ]"
            >
                <span class="material-symbols-rounded text-sm">{{ item.icon }}</span>
                {{ item.name }}
            </Link>
        </div>

        <!-- Main Work Area for Portería -->
        <main class="flex-1 w-full max-w-7xl mx-auto p-4 md:p-8">
            <slot />
        </main>

        <!-- Relevo Rápido de Turno Modal -->
        <RelevoTurnoModal
            :show="showRelevoModal"
            :guarda-actual="user?.name"
            @close="showRelevoModal = false"
        />
    </div>
</template>
