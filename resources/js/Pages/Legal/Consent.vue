<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Logo from '@/Components/UI/Logo.vue';

const props = defineProps<{
    document: {
        id: string;
        type: string;
        title: string;
        body: string;
        version: string;
    };
    type: string;
}>();

const form = useForm({});

const accept = () => {
    form.post(route('legal.accept', props.document.id));
};
</script>

<template>
    <Head :title="document.title" />

    <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex flex-col items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-3xl space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <div class="flex flex-col items-center text-center space-y-4">
                <Logo class="h-12 w-auto" />
                <h1 class="text-2xl md:text-3xl font-display font-bold text-slate-900 dark:text-white leading-tight">
                    {{ document.title }}
                </h1>
                <p class="text-slate-500 max-w-md text-sm md:text-base">
                    Para continuar utilizando NEXO-PRO, por favor revisa y acepta la actualización de nuestra 
                    <span class="font-bold text-brand-600 dark:text-brand-400">
                        {{ type === 'terms' ? 'Términos y Condiciones' : 'Política de Privacidad' }}
                    </span>.
                </p>
            </div>

            <Card variant="flat" class="overflow-hidden border-slate-200 dark:border-slate-800 !rounded-3xl shadow-xl shadow-brand-500/5">
                <div class="max-h-[50vh] overflow-y-auto p-6 md:p-10 text-slate-700 dark:text-slate-300 font-sans leading-relaxed text-sm md:text-base">
                    <div class="whitespace-pre-wrap">{{ document.body }}</div>
                </div>
                
                <div class="p-6 md:p-8 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-200 dark:border-slate-800 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex flex-col">
                        <span class="text-[10px] uppercase tracking-widest font-black text-slate-400">Estado de cumplimiento</span>
                        <div class="text-sm text-slate-600 dark:text-slate-400 font-medium">
                            Versión {{ document.version }} · <span class="text-brand-500">Habeas Data Activo</span>
                        </div>
                    </div>
                    
                    <Button 
                        @click="accept" 
                        variant="primary" 
                        size="lg"
                        class="w-full md:w-auto px-10 !rounded-2xl"
                        :loading="form.processing"
                        icon="check_circle"
                    >
                        Acepto y deseo continuar
                    </Button>
                </div>
            </Card>
            
            <p class="text-center text-[10px] md:text-xs text-slate-400 max-w-md mx-auto leading-relaxed">
                Al hacer clic en aceptar, confirmas que has leído y comprendes el documento legal arriba mencionado. 
                Tus datos están protegidos bajo la <span class="font-bold">Ley 1581 de 2012</span> de la República de Colombia.
            </p>
        </div>
    </div>
</template>

<style scoped>
@reference "tailwindcss";

/* Custom scrollbar for the legal text */
.max-h-\[50vh\]::-webkit-scrollbar {
    width: 6px;
}
.max-h-\[50vh\]::-webkit-scrollbar-track {
    background: transparent;
}
.max-h-\[50vh\]::-webkit-scrollbar-thumb {
    @apply bg-slate-200 dark:bg-slate-800 rounded-full;
}
</style>
