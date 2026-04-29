<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Button from '@/Components/UI/Button.vue';

defineProps<{
    message: string;
}>();
</script>

<template>
    <Head title="Servicio Suspendido — PH360" />

    <div class="min-h-screen bg-surface flex flex-col items-center justify-center p-6 text-center">
        <div class="max-w-md space-y-8 animate-in fade-in zoom-in-95 duration-700">
            <div class="w-24 h-24 bg-error/10 text-error rounded-[2.5rem] flex items-center justify-center mx-auto shadow-xl shadow-error/10 border border-error/10">
                <span class="material-symbols-outlined text-5xl" style="font-variation-settings: 'FILL' 1;">gavel</span>
            </div>

            <div class="space-y-3">
                <h1 class="text-3xl font-black text-primary tracking-tighter uppercase">Servicio Restringido</h1>
                <p class="text-sm font-medium text-on-surface-variant/70 leading-relaxed">
                    {{ message }}
                </p>
            </div>

            <div class="bg-surface-container-low p-6 rounded-3xl border border-outline-variant/10 space-y-4">
                <p class="text-[10px] font-black text-on-surface-variant/40 uppercase tracking-widest leading-tight">
                    Si eres el administrador, puedes contactar a nuestro equipo comercial para regularizar tu situación.
                </p>
                <div class="flex flex-col gap-3">
                    <Button variant="primary" class="w-full" icon="support_agent">Contactar Soporte</Button>
                    <Link :href="route('logout')" method="post" as="button" class="text-[10px] font-black text-primary uppercase tracking-widest hover:underline">Cerrar Sesión</Link>
                </div>
            </div>

            <div class="pt-8 opacity-20">
                <p class="text-[9px] font-black uppercase tracking-[0.3em]">Nexo-Pro Platform · Security Shield</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 24;
}
</style>
