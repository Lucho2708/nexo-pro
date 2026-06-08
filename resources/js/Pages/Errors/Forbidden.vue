<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ShieldExclamationIcon, ArrowLeftIcon, HomeIcon } from '@heroicons/vue/24/outline';

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
                    <ShieldExclamationIcon class="w-16 h-16 text-red-500" />
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
                    <HomeIcon class="w-5 h-5" />
                    Ir al Dashboard Principal
                </Link>

                <button
                    @click="() => window.history.back()"
                    class="flex items-center justify-center gap-2 bg-slate-800 hover:bg-slate-700 text-slate-200 font-medium py-4 px-8 rounded-2xl transition-all border border-slate-700"
                >
                    <ArrowLeftIcon class="w-5 h-5" />
                    Regresar
                </button>
            </div>
            
            <p class="text-slate-600 text-[10px] pt-4 uppercase tracking-[0.2em]">
                Ingeniería Industrial para Propiedad Horizontal
            </p>
        </div>
    </div>
</template>
