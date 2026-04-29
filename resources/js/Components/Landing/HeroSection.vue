<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Button from '@/Components/UI/Button.vue';

defineProps({
    title: {
        type: String,
        default: 'Control Maestro de tu Copropiedad'
    },
    subtitle: {
        type: String,
        default: 'La infraestructura digital definitiva para administradores que buscan control absoluto y propietarios que exigen transparencia total.'
    }
});
</script>

<template>
    <section class="relative pt-32 pb-48 overflow-hidden bg-gradient-to-b from-[#F0F4F8] to-white dark:from-[#0b0e14] dark:to-[#0b0e14]">
        <!-- High-Impact Background Decoration -->
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/10 blur-[150px] rounded-full -mr-96 -mt-96 animate-pulse duration-[10s]"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-secondary/5 blur-[120px] rounded-full -ml-48 -mb-48"></div>

        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-20 items-center relative z-10">
            <div class="space-y-12 animate-in fade-in slide-in-from-left-12 duration-1000 ease-out">
                <div class="space-y-8">
                    <div class="inline-flex items-center gap-3 px-5 py-2 bg-primary/10 text-primary rounded-full border border-primary/20 backdrop-blur-md">
                        <span class="material-symbols-rounded text-sm animate-spin-slow">verified</span>
                        <span class="text-[10px] font-black uppercase tracking-[0.4em] italic">Estándar Industrial PRO</span>
                    </div>
                    
                    <h1 class="text-6xl md:text-8xl font-black text-on-surface dark:text-white leading-[0.95] tracking-tighter uppercase italic">
                        {{ title }} <span class="text-primary block not-italic">Nexo-Pro</span>
                    </h1>
                    
                    <p class="text-xl md:text-2xl text-on-surface-variant/60 dark:text-white/40 max-w-xl font-medium leading-relaxed italic border-l-4 border-primary/20 pl-8">
                        {{ subtitle }}
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-6 pt-4">
                    <Button 
                        href="/#contacto"
                        variant="primary" 
                        size="lg" 
                        icon="rocket_launch" 
                        class="!h-20 !px-12 shadow-2xl shadow-primary/30 !rounded-3xl !text-[11px] font-black uppercase italic"
                    >
                        Solicitar Acceso Demo
                    </Button>
                    <Button 
                        href="/#roles"
                        variant="outline" 
                        size="lg" 
                        class="!h-20 !px-12 !rounded-3xl !text-[11px] font-black uppercase italic border-2 hover:bg-white/5 transition-all"
                    >
                        Explorar Funcionalidades
                    </Button>
                </div>

                <!-- Trusted By / Stats -->
                <div class="flex flex-wrap items-center gap-10 pt-10 opacity-60">
                    <div class="flex flex-col">
                        <span class="text-3xl font-black text-on-surface dark:text-white tabular-nums tracking-tighter italic">+500k</span>
                        <span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]">Unidades Gestionadas</span>
                    </div>
                    <div class="w-px h-10 bg-outline-variant/20 dark:bg-white/10 hidden sm:block"></div>
                    <div class="flex flex-col">
                        <span class="text-3xl font-black text-on-surface dark:text-white tabular-nums tracking-tighter italic">99.9%</span>
                        <span class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em]">Disponibilidad</span>
                    </div>
                </div>
            </div>
            
            <div class="relative lg:h-[700px] flex items-center justify-center animate-in fade-in zoom-in-95 duration-1000 delay-300">
                <!-- Orbital Ring Decoration -->
                <div class="absolute inset-0 border-2 border-primary/5 rounded-full scale-[1.2] animate-spin-slow"></div>
                <div class="absolute inset-0 border border-secondary/5 rounded-full scale-[0.8] animate-reverse-spin"></div>
                
                <div class="bg-white/5 dark:bg-[#12161e] p-4 rounded-[4rem] shadow-3xl border border-white/10 relative z-10 transition-all duration-700 hover:scale-[1.03] group">
                    <!-- Dashboard Mockup Image -->
                    <div class="rounded-[3rem] overflow-hidden border-4 border-white/5 shadow-2xl aspect-video relative">
                        <img 
                          src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?q=80&w=2426&auto=format&fit=crop" 
                          alt="NEXO-PRO Dashboard Preview" 
                          class="w-full h-full object-cover transition-all duration-1000 group-hover:scale-110"
                        >
                        <!-- Glass Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-tr from-primary/20 to-transparent"></div>
                    </div>

                    <!-- Tactical Floating Badges -->
                    <div class="absolute -right-8 -top-8 bg-white dark:bg-[#1a1f29] p-6 rounded-3xl shadow-2xl border border-white/10 animate-float">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                                <span class="material-symbols-rounded">security</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-on-surface-variant dark:text-white/20 uppercase tracking-widest leading-none mb-2">Protocolo</p>
                                <p class="text-sm font-black text-on-surface dark:text-white uppercase italic tracking-tighter">AES-256 Activo</p>
                            </div>
                        </div>
                    </div>

                    <div class="absolute -left-12 bottom-12 bg-white dark:bg-[#1a1f29] p-6 rounded-3xl shadow-2xl border border-white/10 animate-float-delayed">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                                <span class="material-symbols-rounded">bar_chart_4_bars</span>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-on-surface-variant dark:text-white/20 uppercase tracking-widest leading-none mb-2">Cartera</p>
                                <p class="text-sm font-black text-on-surface dark:text-white uppercase italic tracking-tighter">98% Eficiencia</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}

@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes reverse-spin {
    from { transform: rotate(360deg); }
    to { transform: rotate(0deg); }
}

@keyframes float {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-15px); }
}

@keyframes float-delayed {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-20px); }
}

.animate-spin-slow { animation: spin-slow 20s linear infinite; }
.animate-reverse-spin { animation: reverse-spin 25s linear infinite; }
.animate-float { animation: float 6s ease-in-out infinite; }
.animate-float-delayed { animation: float-delayed 8s ease-in-out infinite; }
</style>
