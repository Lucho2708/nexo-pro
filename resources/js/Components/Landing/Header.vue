<script setup lang="ts">
/**
 * Header.vue - Landing Navigation Header (Mobile-First & Accessible)
 * 
 * SOLID Implementation:
 * - SRP: Encapsula exclusivamente la navegación global y accesos primarios del portal.
 * - ISP: Expone únicamente los controles contextuales necesarios para navegación y autenticación.
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
    <header class="fixed top-0 w-full z-50 transition-all duration-300 bg-white/85 dark:bg-[#0b0e14]/85 backdrop-blur-xl border-b border-slate-200/80 dark:border-white/10">
        <div class="flex justify-between items-center w-full px-4 sm:px-6 lg:px-8 py-3.5 sm:py-4 max-w-7xl mx-auto">
            
            <!-- Marca y Logo -->
            <div class="flex items-center gap-4 lg:gap-10">
                <Link href="/" class="hover:opacity-85 transition-opacity active:scale-95 flex items-center gap-2" @click="closeMobileMenu" aria-label="NEXO-PRO Inicio">
                    <Logo width="38px" height="38px" class="sm:w-[44px] sm:h-[44px]" />
                    <span class="font-extrabold text-base sm:text-lg tracking-tight text-slate-900 dark:text-white uppercase italic">
                        Nexo<span class="text-blue-600 dark:text-blue-400">Pro</span>
                    </span>
                </Link>
                
                <!-- Navegación Desktop -->
                <nav class="hidden lg:flex items-center space-x-8" aria-label="Navegación principal">
                    <a
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        class="text-xs font-bold uppercase tracking-wider text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors py-2"
                    >
                        {{ link.name }}
                    </a>
                </nav>
            </div>
            
            <!-- Acciones y Controles -->
            <div class="flex items-center gap-2 sm:gap-4">
                <!-- Theme toggle desktop -->
                <ThemeToggle class="hidden sm:flex" />
                
                <!-- CTA Rápido Móvil / Desktop -->
                <a
                    href="/#contacto"
                    class="hidden sm:inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-extrabold uppercase tracking-wider text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-950/50 hover:bg-blue-100 dark:hover:bg-blue-900/50 transition-all border border-blue-200 dark:border-blue-800 min-h-[44px]"
                >
                    Demo Gratis
                </a>

                <!-- Botón de Acceso (Portal / Mando Central) -->
                <template v-if="$page.props.auth?.user">
                    <Link 
                        :href="route('dashboard')" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md shadow-blue-600/20 active:scale-95 transition-all min-h-[44px] flex items-center justify-center"
                    >
                        Mando Central
                    </Link>
                </template>
                <Link 
                    v-else 
                    :href="route('login')" 
                    class="bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-900 px-3.5 sm:px-6 py-2 sm:py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider shadow-md active:scale-95 transition-all min-h-[44px] flex items-center justify-center"
                >
                    Ingresar
                </Link>

                <!-- Botón Menú Hamburguesa Móvil (Min 44x44px touch target) -->
                <button
                    type="button"
                    @click="toggleMobileMenu"
                    class="lg:hidden flex items-center justify-center w-11 h-11 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-white/10 active:scale-95 transition-all"
                    :aria-expanded="isMobileMenuOpen"
                    aria-label="Abrir o cerrar menú de navegación"
                    aria-controls="mobile-menu-drawer"
                >
                    <span class="material-symbols-outlined text-2xl">
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
                class="lg:hidden bg-white/95 dark:bg-[#0b0e14]/95 backdrop-blur-2xl border-b border-slate-200 dark:border-white/10 px-6 py-6 shadow-2xl max-h-[calc(100vh-64px)] overflow-y-auto"
            >
                <div class="flex flex-col space-y-4">
                    <!-- Enlaces de navegación con hit target >= 48px -->
                    <a
                        v-for="link in navLinks"
                        :key="link.href"
                        :href="link.href"
                        @click="closeMobileMenu"
                        class="flex items-center justify-between py-3.5 px-4 rounded-xl text-base font-bold text-slate-800 dark:text-slate-100 hover:bg-blue-50 dark:hover:bg-white/5 active:scale-[0.98] transition-all min-h-[48px]"
                    >
                        <span>{{ link.name }}</span>
                        <span class="material-symbols-outlined text-slate-400 text-lg">chevron_right</span>
                    </a>

                    <!-- Línea divisoria -->
                    <div class="border-t border-slate-200 dark:border-white/10 my-2"></div>

                    <!-- Selector de tema en móvil -->
                    <div class="flex items-center justify-between py-2 px-4 rounded-xl bg-slate-50 dark:bg-white/5">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Modo de Visualización</span>
                        <ThemeToggle />
                    </div>

                    <!-- Botón Principal de Conversión en Drawer -->
                    <a
                        href="/#contacto"
                        @click="closeMobileMenu"
                        class="flex items-center justify-center gap-2 w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-extrabold text-sm uppercase tracking-wider shadow-lg shadow-blue-600/30 active:scale-[0.98] transition-transform text-center min-h-[48px]"
                    >
                        <span class="material-symbols-outlined text-lg">rocket_launch</span>
                        <span>Solicitar Demo Guiada</span>
                    </a>

                    <!-- Asistencia directa vía WhatsApp -->
                    <a
                        href="https://wa.me/573001234567?text=Hola%2C%20solicito%20asesor%C3%ADa%20personalizada%20de%20NEXO-PRO"
                        target="_blank"
                        rel="noopener noreferrer"
                        @click="closeMobileMenu"
                        class="flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 font-bold text-xs uppercase tracking-wider active:scale-[0.98] transition-transform min-h-[44px]"
                    >
                        <span class="material-symbols-outlined text-base">chat</span>
                        <span>Asesoría por WhatsApp</span>
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
