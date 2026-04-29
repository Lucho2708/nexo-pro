<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Logo from '@/Components/UI/Logo.vue';
</script>

<template>
    <footer class="bg-surface-container-lowest dark:bg-[#080a0f] py-24 px-8 border-t border-outline-variant/10 dark:border-white/5 relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-primary/5 blur-[100px] rounded-full -mr-32 -mb-32"></div>

        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start gap-16 relative z-10">
            <div class="space-y-6 max-w-sm">
                <Logo width="50px" height="50px" />
                <p class="text-[11px] font-bold text-on-surface-variant/60 dark:text-white/30 uppercase tracking-widest leading-relaxed italic">
                    Elevando la gestión de copropiedades al estándar de ingeniería PRO. La plataforma definitiva para el futuro de la PropTech.
                </p>
                <div class="flex gap-4">
                    <div v-for="i in 3" :key="i" class="w-10 h-10 rounded-xl bg-surface-container-high dark:bg-white/5 flex items-center justify-center hover:bg-primary/10 hover:text-primary transition-all cursor-pointer border border-outline-variant/5">
                        <span class="material-symbols-rounded text-lg">public</span>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-16">
                <div class="space-y-6">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary italic">Plataforma</h4>
                    <ul class="space-y-4">
                        <li><a href="#soluciones" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline">Soluciones</a></li>
                        <li><a href="#roles" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline">Roles</a></li>
                        <li><a href="#precios" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline">Planes</a></li>
                    </ul>
                </div>
                <div class="space-y-6">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary italic">Legal</h4>
                    <ul class="space-y-4">
                        <li><Link href="#" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline">Privacidad</Link></li>
                        <li><Link href="#" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline">Términos</Link></li>
                        <li><Link href="#" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline">Cookies</Link></li>
                    </ul>
                </div>
                <div class="space-y-6 col-span-2 md:col-span-1">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-primary italic">Soporte</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline">Centro de Ayuda</a></li>
                        <li><a href="/soporte" class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant/40 hover:text-primary transition-all underline-offset-4 hover:underline">Ticket de Sistema</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto mt-24 pt-8 border-t border-outline-variant/10 dark:border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-[9px] font-black text-on-surface-variant/30 dark:text-white/10 uppercase tracking-[0.3em]">
                © {{ new Date().getFullYear() }} NEXO-PRO / HIGH-FIDELITY PROPTECH SOLUTIONS.
            </p>
            <div class="flex items-center gap-2">
                <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                <span class="text-[9px] font-black text-emerald-500/40 uppercase tracking-widest italic">Sistemas Operativos 100%</span>
            </div>
        </div>
    </footer>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
