<script setup>
/**
 * Forbidden.vue - Pantalla de Error 403 (Acceso Denegado)
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Proporciona retroalimentación inmediata, clara y amigable cuando un usuario autenticado intenta
 * acceder a un módulo o recurso de copropiedad para el cual no cuenta con privilegios en el sistema RBAC
 * o intenta una operación no autorizada por su rol.
 * - Reduce la frustración del usuario ofreciendo salidas directas (Volver al Dashboard o Regresar).
 * - Mantiene la identidad de marca industrial y de seguridad empresarial de NEXO-PRO.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Utiliza iconos vectoriales SVG limpios e integrados (cero dependencias externas no resueltas).
 * - Paleta de colores dark mode (slate-950 con acento rojo defensivo).
 * - Cumple contraste WCAG 2.1 AA (> 4.5:1) y navegación accesible.
 */
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    message: {
        type: String,
        default: 'No tienes los permisos necesarios para acceder a este módulo.'
    }
});
</script>

<template>
    <Head title="Acceso Denegado" />

    <div class="min-h-screen bg-slate-950 flex items-center justify-center p-6 text-white overflow-hidden relative">
        <!-- Fondo Decorativo (Industrial Inmersivo) -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-blue-600/20 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-md w-full z-10 text-center space-y-8 bg-slate-900/50 backdrop-blur-xl border border-slate-800 p-10 rounded-3xl shadow-2xl">
            <!-- Icono con animación -->
            <div class="flex justify-center">
                <div class="bg-red-500/10 p-4 rounded-2xl border border-red-500/20 animate-pulse">
                    <svg class="w-16 h-16 text-red-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.002A11.959 11.959 0 0112 2.714zm0 13.036h.008v.008H12v-.008z" />
                    </svg>
                </div>
            </div>

            <div class="space-y-4">
                <h1 class="text-4xl font-black tracking-tight text-white italic uppercase">
                    Acceso <span class="text-red-500">Restringido</span>
                </h1>
                <p class="text-slate-400 text-lg leading-relaxed">
                    {{ message }}
                </p>
                <div class="bg-slate-950/50 rounded-lg p-3 border border-slate-800 inline-block text-xs font-mono text-slate-500 uppercase tracking-widest">
                    Code: 403 Forbidden | NEXO-PRO Security
                </div>
            </div>

            <div class="flex flex-col gap-4 pt-4">
                <Link
                    :href="route('dashboard')"
                    class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 px-8 rounded-2xl transition-all duration-300 transform hover:scale-[1.02] active:scale-95 shadow-lg shadow-blue-600/20"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                    Ir al Dashboard Principal
                </Link>

                <button
                    @click="() => window.history.back()"
                    class="flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium py-4 px-8 rounded-2xl transition-all border border-slate-700 cursor-pointer"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Regresar
                </button>
            </div>
            
            <p class="text-slate-600 text-[10px] pt-4 uppercase tracking-[0.2em]">
                Ingeniería Industrial para Propiedad Horizontal
            </p>
        </div>
    </div>
</template>
