<script setup lang="ts">
/**
 * Header.vue - Landing Navigation Header (Ultra Premium & Accessible)
 * 
 * SOLID Implementation:
 * - SRP: Encapsula exclusivamente la navegación global, identidad de marca y control de autenticación.
 * - ISP: Expone únicamente los controles contextuales necesarios según dispositivo y estado de sesión.
 */
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import Logo from '@/Components/UI/Logo.vue';
import ThemeToggle from '@/Components/UI/ThemeToggle.vue';

const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
    if (isMobileMenuOpen.value) {
        document.body.style.overflow = 'hidden';
    } else {
        document.body.style.overflow = '';
    }
};

const closeMobileMenu = () => {
    isMobileMenuOpen.value = false;
    document.body.style.overflow = '';
};

const handleKeyDown = (e: KeyboardEvent) => {
    if (e.key === 'Escape' && isMobileMenuOpen.value) {
        closeMobileMenu();
    }
};

onMounted(() => {
    window.addEventListener('keydown', handleKeyDown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeyDown);
    document.body.style.overflow = '';
});

const navLinks = [
    { name: 'Soluciones', href: '/#soluciones' },
    { name: 'Roles', href: '/#roles' },
    { name: 'Calculadora ROI', href: '/#calculadora-roi' },
    { name: 'Precios', href: '/#precios' },
    { name: 'Contacto', href: '/#contacto' },
];
</script>

<template>
    <header class="fixed top-0 left-0 right-0 z-50 transition-all duration-300 bg-white/80 dark:bg-[#070a12]/80 backdrop-blur-xl border-b border-slate-200/70 dark:border-white/[0.08]">
        <div class="flex justify-between items-center w-full px-4 sm:px-6 lg:px-8 py-3 max-w-7xl mx-auto">
            
            <!-- Identidad de Marca (Sin duplicación de logo/texto) -->
            <Link href="/" class="flex items-center gap-2.5 active:scale-95 transition-transform group" @click="closeMobileMenu" aria-label="NEXO-PRO Inicio">
                <Logo width="34px" height="34px" :show-text="false" class="!p-0" />
                <div class="flex flex-col">
                    <span class="font-black text-base sm:text-lg tracking-tight text-slate-900 dark:text-white uppercase leading-none">
                        NEXO<span class="text-blue-600 dark:text-blue-400 font-extrabold">PRO</span>
                    </span>
                    <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none mt-0.5">
                        Propiedad Horizontal
                    </span>
                </div>
            </Link>
            
            <!-- Navegación Desktop -->
            <nav class="hidden lg:flex items-center space-x-7" aria-label="Navegación principal">
                <a
                    v-for="link in navLinks"
                    :key="link.href"
                    :href="link.href"
                    class="text-xs font-semibold tracking-wide text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors py-1.5"
                >
                    {{ link.name }}
                </a>
            </nav>
            
            <!-- Controles y Accesos -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Theme Toggle Desktop -->
                <ThemeToggle class="hidden sm:flex" />
                
                <!-- CTA Secundario Desktop: Demo Rápida -->
                <a
                    href="/#contacto"
                    class="hidden md:inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-bold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/40 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-all border border-blue-200/80 dark:border-blue-800/60 min-h-[40px]"
                >
                    Agendar Demo
                </a>

                <!-- Botón de Acceso (Portal / Mando Central) -->
                <template v-if="$page.props.auth?.user">
                    <Link 
                        :href="route('dashboard')" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-3.5 sm:px-5 py-2 rounded-xl text-xs font-bold tracking-wide shadow-sm shadow-blue-600/25 active:scale-95 transition-all min-h-[40px] flex items-center justify-center"
                    >
                        Mando Central
                    </Link>
                </template>
                <Link 
                    v-else 
                    :href="route('login')" 
                    class="px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold text-slate-800 dark:text-slate-100 bg-slate-100 hover:bg-slate-200 dark:bg-white/10 dark:hover:bg-white/15 border border-slate-200/80 dark:border-white/10 active:scale-95 transition-all min-h-[40px] flex items-center justify-center"
                >
                    Ingresar
                </Link>

                <!-- Botón Hamburguesa Móvil (Min 44x44px touch target) -->
                <button
                    type="button"
                    @click="toggleMobileMenu"
                    class="lg:hidden flex items-center justify-center w-11 h-11 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10 active:scale-95 transition-all ml-1"
                    :aria-expanded="isMobileMenuOpen"
                    aria-label="Abrir o cerrar menú de navegación"
                    aria-controls="mobile-menu-drawer"
                >
                    <span class="material-symbols-outlined notranslate text-2xl select-none" translate="no">
                        {{ isMobileMenuOpen ? 'close' : 'menu' }}
                    </span>
                </button>
            </div>
        </div>

        <!-- Drawer Menú Móvil Desplegable -->
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <div
                v-if="isMobileMenuOpen"
                id="mobile-menu-drawer"
                class="lg:hidden bg-white/95 dark:bg-[#080c16]/95 backdrop-blur-2xl border-b border-slate-200 dark:border-white/10 px-6 py-6 shadow-2xl max-h-[calc(100vh-60px)] overflow-y-auto"
            >
                <div class="flex flex-col space-y-3">
                    <!-- Enlaces de navegación -->
                    <a
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        @click="closeMobileMenu"
                        class="flex items-center justify-between py-3 px-4 rounded-xl text-sm font-bold text-slate-800 dark:text-slate-100 hover:bg-blue-50 dark:hover:bg-white/5 active:scale-[0.98] transition-all min-h-[48px]"
                    >
                        <span>{{ link.name }}</span>
                        <span class="material-symbols-outlined text-slate-400 text-base">chevron_right</span>
                    </a>

                    <!-- Línea divisoria -->
                    <div class="border-t border-slate-200 dark:border-white/10 my-2"></div>

                    <!-- Selector de tema en móvil -->
                    <div class="flex items-center justify-between py-2 px-4 rounded-xl bg-slate-50 dark:bg-white/5">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Tema Visual</span>
                        <ThemeToggle />
                    </div>

                    <!-- Botón Principal de Conversión en Drawer -->
                    <a
                        href="/#contacto"
                        @click="closeMobileMenu"
                        class="flex items-center justify-center gap-2 w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold text-sm tracking-wide shadow-lg shadow-blue-600/25 active:scale-[0.98] transition-transform text-center min-h-[48px]"
                    >
                        <span class="material-symbols-outlined text-lg">calendar_today</span>
                        <span>Solicitar Demo Guiada</span>
                    </a>

                    <!-- Asistencia directa vía WhatsApp -->
                    <a
                        href="https://wa.me/573001234567?text=Hola%2C%20solicito%20asesor%C3%ADa%20personalizada%20de%20NEXO-PRO"
                        target="_blank"
                        rel="noopener noreferrer"
                        @click="closeMobileMenu"
                        class="flex items-center justify-center gap-2 w-full py-2.5 px-4 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 font-bold text-xs active:scale-[0.98] transition-transform min-h-[44px]"
                    >
                        <span class="material-symbols-outlined text-base">chat</span>
                        <span>Asesoría Directa por WhatsApp</span>
                    </a>
                </div>
            </div>
        </Transition>
    </header>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
