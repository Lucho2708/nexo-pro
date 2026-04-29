<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';

const props = defineProps<{
    asamblea: any;
    unidad: any;
    message: string;
    can_reset: boolean;
}>();

const resetConnection = () => {
    router.post(route('asambleas.reset-connection', props.asamblea.id), {}, {
        onSuccess: () => {
            // Re-intento automático tras resetear
            window.location.href = route('asambleas.show', props.asamblea.id);
        }
    });
};
</script>

<template>
    <Head title="Acceso Denegado — Nexo-Pro" />

    <div class="min-h-screen bg-[#00173c] flex items-center justify-center p-6 relative overflow-hidden">
        <!-- Decorative Background -->
        <div class="absolute top-0 left-0 w-full h-full opacity-10">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-secondary rounded-full blur-[120px]"></div>
        </div>

        <div class="max-w-md w-full relative z-10 animate-fade-in-up">
            <div class="text-center mb-8">
                <div class="w-20 h-20 bg-white/10 backdrop-blur-xl border border-white/20 rounded-[2rem] flex items-center justify-center mx-auto mb-6 shadow-2xl">
                    <span class="material-symbols-rounded text-4xl text-amber-400 animate-pulse">devices_off</span>
                </div>
                <h1 class="text-2xl font-black text-white uppercase tracking-tighter mb-2">Conexión Duplicada</h1>
                <p class="text-white/60 text-sm font-medium leading-relaxed px-4">
                    {{ message }}
                </p>
            </div>

            <div class="bg-white/5 backdrop-blur-2xl border border-white/10 rounded-[2.5rem] p-8 shadow-3xl">
                <div class="flex items-center gap-4 p-4 bg-white/5 rounded-2xl border border-white/10 mb-8">
                    <div class="w-10 h-10 rounded-xl bg-primary/20 flex items-center justify-center">
                        <span class="material-symbols-rounded text-primary">apartment</span>
                    </div>
                    <div class="text-left">
                        <p class="text-[10px] font-black text-white/40 uppercase tracking-widest">Unidad Detectada</p>
                        <p class="text-sm font-bold text-white uppercase">{{ unidad.torre }} - {{ unidad.nombre }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <template v-if="can_reset">
                        <Button 
                            variant="primary" 
                            size="lg" 
                            icon="sync_alt" 
                            class="w-full !bg-amber-500 hover:!bg-amber-600 !text-white border-0 shadow-lg shadow-amber-500/20"
                            @click="resetConnection"
                        >
                            Ingresar aquí (Cerrar otra sesión)
                        </Button>
                        <p class="text-[10px] text-center text-white/30 font-bold uppercase tracking-wider">
                            Esto desconectará cualquier otro dispositivo de esta unidad.
                        </p>
                    </template>
                    
                    <Link :href="route('owner.dashboard')" class="block">
                        <Button variant="ghost" size="lg" icon="arrow_back" class="w-full !text-white/60 hover:!bg-white/10">
                            Regresar al Panel
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="mt-12 text-center">
                <p class="text-[10px] font-black text-white/20 uppercase tracking-[0.3em]">Nexo-Pro Assembly Guard 2026</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in-up {
    animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
