<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Input from '@/Components/UI/Input.vue';
import Button from '@/Components/UI/Button.vue';
import Card from '@/Components/UI/Card.vue';

const showSuccessMessage = ref(false);

const form = useForm({
    fullName: '',
    email: '',
    phone: '',
    buildingName: '',
    honeypot: '',
});

const submit = () => {
    form.post(route('demo.request'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            showSuccessMessage.value = true;
            setTimeout(() => {
                showSuccessMessage.value = false;
            }, 6000);
        },
    });
};
</script>

<template>
    <section id="contacto" class="py-32 md:py-48 bg-surface dark:bg-[#080a0f] relative overflow-hidden">
        <!-- High-End Background Decoration -->
        <div class="absolute top-0 right-0 w-[1000px] h-[1000px] bg-primary/5 blur-[150px] rounded-full pointer-events-none -mr-96 -mt-96"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-secondary/5 blur-[120px] rounded-full pointer-events-none -ml-48 -mb-48"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10">
            <Card class="!p-0 !rounded-[4rem] border-2 border-outline-variant/10 dark:border-white/5 shadow-[0_50px_100px_-20px_rgba(0,0,0,0.5)] overflow-hidden bg-white dark:bg-[#0b0e14] relative">
                
                <!-- Full-Screen Success Layer -->
                <Transition
                    enter-active-class="transition duration-700 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition duration-500 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div v-if="showSuccessMessage" class="absolute inset-0 bg-primary z-50 flex flex-col items-center justify-center text-center p-12 italic">
                        <div class="w-32 h-32 bg-white/20 rounded-[2.5rem] flex items-center justify-center mb-10 text-white animate-bounce shadow-2xl border-4 border-white/20">
                            <span class="material-symbols-rounded text-7xl">rocket_launch</span>
                        </div>
                        <h3 class="text-6xl md:text-7xl font-black text-white mb-6 uppercase tracking-tighter">¡Transmisión Exitosa!</h3>
                        <p class="text-white/70 max-w-xl text-lg font-bold uppercase tracking-widest leading-relaxed">
                            Dispatcher activado. Un consultor técnico de Nexo-Pro iniciará el protocolo de contacto en menos de 24 horas.
                        </p>
                        <button @click="showSuccessMessage = false" class="mt-16 text-white/30 font-black uppercase tracking-[0.5em] hover:text-white transition-all text-[11px] underline underline-offset-8">Nueva Operación de Registro</button>
                    </div>
                </Transition>

                <div class="flex flex-col lg:flex-row min-h-[800px]">
                    <!-- Sidebar Informativo de Alto Impacto -->
                    <div class="lg:w-[40%] bg-primary p-12 md:p-20 text-white flex flex-col justify-between italic relative overflow-hidden shrink-0">
                         <div class="absolute top-0 left-0 w-full h-full bg-gradient-to-br from-white/20 to-transparent opacity-50"></div>
                         <!-- Abstract Lines -->
                         <div class="absolute inset-0 opacity-10">
                            <div class="absolute top-1/4 left-0 w-full h-px bg-white transform rotate-12"></div>
                            <div class="absolute top-2/4 left-0 w-full h-px bg-white transform -rotate-12"></div>
                         </div>

                         <div class="relative z-10 space-y-10">
                            <div class="flex items-center gap-4">
                                <div class="w-2 h-10 bg-secondary rounded-full"></div>
                                <span class="text-[11px] font-black uppercase tracking-[0.6em] text-white/60">Dispatcher de Acceso</span>
                            </div>
                            <h2 class="text-6xl md:text-7xl font-black uppercase tracking-tighter leading-[0.9]">Inicia la <br/> <span class="text-secondary italic font-black">Evolución</span></h2>
                            <p class="text-base md:text-lg font-bold text-white/70 uppercase tracking-widest leading-relaxed max-w-md">Descubre por qué la élite de la propiedad raíz en Colombia ha migrado al ecosistema Nexo-Pro.</p>
                         </div>
                         
                         <div class="relative z-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-10 pt-20">
                            <div class="space-y-4 group cursor-pointer">
                                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-white/40">Frecuencia de Voz</p>
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center group-hover:bg-secondary group-hover:text-white transition-all duration-500 shadow-xl border border-white/10">
                                        <span class="material-symbols-rounded text-2xl">call</span>
                                    </div>
                                    <span class="text-base font-black uppercase tracking-tighter tabular-nums">+57 (601) 444 0000</span>
                                </div>
                            </div>
                            <div class="space-y-4 group cursor-pointer">
                                <p class="text-[9px] font-black uppercase tracking-[0.4em] text-white/40">Canal de Ingeniería</p>
                                <div class="flex items-center gap-5">
                                    <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center group-hover:bg-secondary group-hover:text-white transition-all duration-500 shadow-xl border border-white/10">
                                        <span class="material-symbols-rounded text-2xl">alternate_email</span>
                                    </div>
                                    <span class="text-base font-black uppercase tracking-tighter italic">ingenieria@nexo-pro.io</span>
                                </div>
                            </div>
                         </div>
                    </div>

                    <!-- Formulario Expansivo -->
                    <form @submit.prevent="submit" class="flex-1 p-12 md:p-24 space-y-12 bg-white dark:bg-[#0b0e14]">
                        <div class="space-y-4 mb-12">
                             <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic">Protocolo de Registro de Cliente</h3>
                             <p class="text-sm font-medium text-on-surface-variant/60 dark:text-white/30 uppercase tracking-widest">Ingrese los metadatos obligatorios para iniciar la auditoría técnica.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 lg:gap-12">
                            <Input 
                                v-model="form.fullName"
                                label="Nombre Completo del Mando"
                                placeholder="Ej: Ing. Mauricio Silva"
                                required
                                icon="person"
                                class="scale-105"
                            />
                            <Input 
                                v-model="form.email"
                                type="email"
                                label="Email Corporativo"
                                placeholder="contacto@edificio.com"
                                required
                                icon="mail"
                                class="scale-105"
                            />
                            <Input 
                                v-model="form.phone"
                                type="tel"
                                label="Línea de Enlace Móvil"
                                placeholder="+57 300 000 0000"
                                required
                                icon="smartphone"
                                class="scale-105"
                            />
                            <Input 
                                v-model="form.buildingName"
                                label="Nombre de la Copropiedad"
                                placeholder="Residencial Las Acacias..."
                                required
                                icon="domain"
                                class="scale-105"
                            />
                        </div>

                        <div class="space-y-10 pt-8">
                            <div class="flex items-start gap-4 p-6 rounded-2xl bg-surface-container-low dark:bg-white/[0.03] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all cursor-pointer group">
                                <input type="checkbox" required class="mt-1 w-6 h-6 rounded border-2 border-primary text-primary focus:ring-8 focus:ring-primary/5 cursor-pointer transition-all" />
                                <span class="text-[11px] font-black text-on-surface dark:text-white/50 uppercase tracking-[0.2em] italic leading-relaxed group-hover:text-primary transition-colors">Acepto los términos de tratamiento de datos bajo el protocolo de seguridad Nexo-Armor y la política de privacidad PropTech.</span>
                            </div>

                            <Button 
                                type="submit"
                                variant="primary"
                                class="w-full !h-24 !rounded-[2rem] !text-sm font-black uppercase italic shadow-[0_20px_50px_rgba(var(--primary),0.3)] hover:scale-[1.02] active:scale-95 transition-all duration-500"
                                :disabled="form.processing"
                                icon="double_arrow"
                            >
                                {{ form.processing ? 'DESPLEGANDO PROTOCOLO...' : 'Activar Demostración de Ingeniería' }}
                            </Button>
                        </div>
                    </form>
                </div>
            </Card>
        </div>
    </section>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
