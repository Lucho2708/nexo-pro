<script setup lang="ts">
/**
 * MobileStickyBar.vue
 *
 * [UX / CONVERSIÓN PRO]
 * Barra flotante inferior de alta conversión exclusiva para dispositivos móviles.
 * - Mantiene el llamado a la acción (CTA) accesible en cualquier punto de scroll con el pulgar.
 * - Permite dos canales de conversión inmediata: Formulario de demostración técnica y canal directo WhatsApp.
 * - Cumple con Apple HIG y Android Touch Target (min-h >= 44px) y zona segura (pb-safe).
 */
</script>

<template>
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-[#0b0e14]/95 backdrop-blur-xl border-t border-slate-200 dark:border-white/10 px-4 py-2.5 shadow-[0_-8px_25px_rgba(0,0,0,0.1)] pb-safe transition-all duration-300">
        <div class="max-w-md mx-auto flex items-center justify-between gap-3">
            <!-- Enlace Directo WhatsApp Asesoría Rápida -->
            <a
                href="https://wa.me/573001234567?text=Hola%2C%20estoy%20interesado%20en%20una%20demostraci%C3%B3n%20t%C3%A9cnica%20de%20NEXO-PRO%20para%20mi%20copropiedad."
                target="_blank"
                rel="noopener noreferrer"
                class="flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 active:scale-95 transition-transform shrink-0"
                aria-label="Contactar por WhatsApp para asesoría de copropiedad"
            >
                <span class="material-symbols-outlined text-2xl">chat</span>
            </a>

            <!-- Botón Principal: Agendar Demo Guiada -->
            <a
                href="/#contacto"
                class="flex-1 flex items-center justify-center gap-2 h-12 px-4 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 active:scale-[0.98] text-white font-extrabold text-xs sm:text-sm uppercase tracking-wider shadow-md shadow-blue-600/20 transition-all text-center leading-none"
            >
                <span class="material-symbols-outlined text-lg">calendar_today</span>
                <span>Agendar Demo Gratis</span>
            </a>
        </div>
    </div>
</template>

<style scoped>
.pb-safe {
    padding-bottom: max(0.625rem, env(safe-area-inset-bottom));
}

.material-symbols-outlined {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
