<script setup lang="ts">
/**
 * MobileStickyBar.vue
 *
 * [UX / ULTRA CONVERSIÓN INTELLIGENT DOCK]
 * Barra flotante inferior de nivel premium exclusiva para dispositivos móviles.
 * - Detecta el scroll: se mantiene oculta en la parte superior (Hero) para evitar
 *   la colisión visual de botones duplicados.
 * - Se despliega suavemente cuando el usuario navega fuera del Hero (scrollY > 350px).
 * - Proporciona dos canales ergonómicos de conversión: WhatsApp directo y Formulario VIP.
 * - Respeta la zona segura de dispositivos iOS y Android (Safe Area Inset).
 */
import { ref, onMounted, onUnmounted } from 'vue';

const isVisible = ref(false);

const handleScroll = () => {
    // Aparece únicamente cuando el usuario se desplaza más allá del Hero
    isVisible.value = window.scrollY > 380;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<template>
    <div
        class="lg:hidden fixed bottom-0 left-0 right-0 z-40 px-3 pb-safe transition-all duration-400 ease-out"
        :class="[
            isVisible
                ? 'translate-y-0 opacity-100 pointer-events-auto'
                : 'translate-y-full opacity-0 pointer-events-none'
        ]"
    >
        <div class="max-w-md mx-auto mb-2 p-2 rounded-2xl bg-white/95 dark:bg-[#090d16]/95 backdrop-blur-2xl border border-slate-200/90 dark:border-white/10 shadow-[0_12px_40px_-8px_rgba(0,0,0,0.25)] flex items-center justify-between gap-2.5">
            <!-- Enlace Directo WhatsApp Asesoría Inmediata -->
            <a
                href="https://wa.me/573001234567?text=Hola%2C%20solicito%20asesor%C3%ADa%20personalizada%20de%20NEXO-PRO%20para%20mi%20copropiedad."
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 active:scale-95 transition-transform shrink-0"
                aria-label="Contactar por WhatsApp"
            >
                <span class="material-symbols-outlined text-2xl">chat</span>
            </a>

            <!-- Botón Principal: Agendar Demostración VIP -->
            <a
                href="/#contacto"
                class="flex-1 flex items-center justify-center gap-2 h-12 px-4 rounded-xl bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 active:scale-[0.98] text-white font-extrabold text-xs sm:text-sm tracking-wide shadow-md shadow-blue-600/30 transition-all text-center leading-none"
            >
                <span class="material-symbols-outlined text-lg">calendar_today</span>
                <span>Agendar Demo Gratis</span>
            </a>
        </div>
    </div>
</template>

<style scoped>
.pb-safe {
    padding-bottom: max(0.5rem, env(safe-area-inset-bottom));
}

.material-symbols-outlined {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
