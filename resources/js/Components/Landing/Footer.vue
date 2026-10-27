<script setup lang="ts">
/**
 * Footer.vue - Pie de Página Institucional y Enlaces de Confianza
 */
import { Link } from '@inertiajs/vue3';
import Logo from '@/Components/UI/Logo.vue';
</script>

<template>
    <footer class="bg-slate-100 dark:bg-[#070a12] pt-20 pb-28 sm:pb-20 px-4 sm:px-6 lg:px-8 border-t border-slate-200/80 dark:border-white/10 relative overflow-hidden transition-colors duration-300">
        <!-- Resplandor Ambiental -->
        <div class="absolute bottom-0 right-0 w-72 h-72 bg-blue-500/5 blur-[110px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-12 sm:gap-16 relative z-10">
            <div class="space-y-4 max-w-sm">
                <div class="flex items-center gap-2.5">
                    <Logo width="36px" height="36px" :show-text="false" class="!p-0" />
                    <div class="flex flex-col">
                        <span class="font-black text-lg tracking-tight text-slate-900 dark:text-white uppercase leading-none">
                            NEXO<span class="text-blue-600 dark:text-blue-400">PRO</span>
                        </span>
                        <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none mt-0.5">
                            Cloud Suite
                        </span>
                    </div>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed font-normal">
                    La plataforma empresarial para gestión de copropiedades en Colombia. Automatización de recaudo bancario, asambleas con quórum en vivo y estricto cumplimiento de la Ley 675 de 2001.
                </p>
                <div class="flex items-center gap-2 pt-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-400 text-xs font-semibold border border-emerald-200/60 dark:border-emerald-800/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        Pasarela Segura PSE & Wompi
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-10 sm:gap-16 w-full md:w-auto">
                <div class="space-y-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 dark:text-white">Plataforma</h4>
                    <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-400">
                        <li><a href="/#soluciones" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Soluciones</a></li>
                        <li><a href="/#roles" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Roles y Accesos</a></li>
                        <li><a href="/#calculadora-roi" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Calculadora ROI</a></li>
                        <li><a href="/#precios" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Planes y Tarifas</a></li>
                    </ul>
                </div>
                <div class="space-y-4">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 dark:text-white">Legal & Seguridad</h4>
                    <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-400">
                        <li><a href="/#contacto" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Ley 675 de 2001</a></li>
                        <li><a href="/#contacto" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Habeas Data</a></li>
                        <li><a href="/#contacto" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Seguridad de Datos</a></li>
                        <li><a href="/#contacto" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Términos de Servicio</a></li>
                    </ul>
                </div>
                <div class="space-y-4 col-span-2 md:col-span-1">
                    <h4 class="text-xs font-extrabold uppercase tracking-wider text-slate-900 dark:text-white">Contacto VIP</h4>
                    <ul class="space-y-2.5 text-xs text-slate-600 dark:text-slate-400">
                        <li><a href="https://wa.me/573001234567" target="_blank" rel="noopener" class="text-emerald-600 dark:text-emerald-400 font-bold hover:underline">WhatsApp Consultoría</a></li>
                        <li><a href="/#contacto" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Agendar Demostración</a></li>
                        <li><span class="text-slate-400">Bogotá, Colombia</span></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto mt-16 pt-6 border-t border-slate-200/80 dark:border-white/10 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-slate-500 dark:text-slate-500">
            <p>
                © {{ new Date().getFullYear() }} NEXO-PRO. Todos los derechos reservados. Colombia.
            </p>
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="font-medium text-emerald-600 dark:text-emerald-400 text-xs">Infraestructura 99.9% Operativa</span>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
