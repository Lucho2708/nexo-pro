<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Switch from '@/Components/UI/Switch.vue';
import Badge from '@/Components/UI/Badge.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    settings: {
        '2fa_enabled': boolean;
        'maintenance_mode': boolean;
        'log_retention_days': number;
        'audit_retention_days': number;
        'allow_new_registrations': boolean;
        'system_announcements': boolean;
    };
}>();

const toast = useToast();
const form = useForm({
    '2fa_enabled': props.settings['2fa_enabled'],
    'maintenance_mode': props.settings['maintenance_mode'],
    'log_retention_days': props.settings['log_retention_days'],
    'audit_retention_days': props.settings['audit_retention_days'],
    'allow_new_registrations': props.settings['allow_new_registrations'],
    'system_announcements': props.settings['system_announcements'],
});

const submit = () => {
    form.patch(route('superadmin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add('Núcleo de configuración actualizado exitosamente', 'success');
        }
    });
};
</script>

<template>
    <Head title="Panel de Control Global — NEXO-PRO" />

    <div class="max-w-6xl mx-auto space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-6 duration-700">
        <!-- Header Industrial Transparente -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-8 pt-4 px-2">
            <div class="flex items-center gap-6">
                <div class="w-16 h-16 rounded-[2rem] bg-secondary flex items-center justify-center shadow-[0_0_40px_-10px_rgba(var(--secondary),0.3)]">
                    <span class="material-symbols-rounded text-3xl text-white">settings_input_component</span>
                </div>
                <div>
                    <h2 class="text-4xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">CEREBRO <span class="text-secondary italic">GLOBAL</span></h2>
                    <div class="flex items-center gap-2 mt-3">
                        <span class="w-1.5 h-1.5 rounded-full bg-secondary animate-pulse"></span>
                        <p class="text-[9px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em]">Gestión de Parámetros Maestros del Ecosistema</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-surface-container-low dark:bg-white/5 p-2 rounded-[1.8rem] border border-outline-variant/10 dark:border-white/5 flex items-center gap-4">
                <Badge variant="neutral" class="!px-6 !py-1.5 !text-[9px] !font-black uppercase tracking-[0.2em] !bg-white dark:!bg-white/[0.03]">{{ new Date().getFullYear() }} — STABLE RELEASE</Badge>
                <div class="h-6 w-px bg-outline-variant/10 dark:bg-white/10 mx-1"></div>
                 <Button 
                    @click="submit"
                    variant="primary" 
                    icon="save" 
                    :loading="form.processing"
                    class="!rounded-2xl !h-12 shadow-xl shadow-primary/20 !text-[10px] font-black uppercase px-8"
                >
                    Aplicar Cambios Globales
                </Button>
            </div>
        </header>

        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Bloque 1: Blindaje Forense -->
            <div class="space-y-8">
                <Card class="!p-8 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 hover:border-primary/20 transition-all shadow-xl dark:bg-[#0b0e14]">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <h3 class="text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic">Blindaje & <span class="text-primary italic font-black">Identidad</span></h3>
                            <p class="text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2">Protocolos de acceso global</p>
                        </div>
                        <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center">
                            <span class="material-symbols-rounded text-primary">verified_user</span>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-6 bg-surface-container dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/5 dark:border-white/5 group hover:border-primary/20 transition-all">
                            <div class="space-y-1 pr-4">
                                <h4 class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-wider">Multi-Factor (2FA) Obligatorio</h4>
                                <p class="text-[9px] text-on-surface-variant/60 dark:text-white/30 font-medium leading-relaxed">
                                    Fuerza a todos los usuarios a usar autenticación de dos pasos.
                                </p>
                            </div>
                            <Switch v-model="form['2fa_enabled']" />
                        </div>

                        <div class="flex items-center justify-between p-6 bg-surface-container dark:bg-white/[0.02] rounded-[2rem] border border-outline-variant/5 dark:border-white/5 group hover:border-primary/20 transition-all">
                            <div class="space-y-1 pr-4">
                                <h4 class="text-[11px] font-black text-on-surface dark:text-white uppercase tracking-wider">Registro de Nuevos Clientes</h4>
                                <p class="text-[9px] text-on-surface-variant/60 dark:text-white/30 font-medium leading-relaxed">
                                    Habilita o deshabilita el formulario de Onboarding público.
                                </p>
                            </div>
                            <Switch v-model="form['allow_new_registrations']" />
                        </div>
                    </div>
                </Card>

                <Card class="!p-8 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 hover:border-secondary/20 transition-all shadow-xl dark:bg-[#0b0e14]">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <h3 class="text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic">Operaciones <span class="text-secondary italic font-black">Críticas</span></h3>
                            <p class="text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2">Acciones de infraestructura masiva</p>
                        </div>
                        <div class="w-12 h-12 bg-secondary/10 rounded-2xl flex items-center justify-center">
                            <span class="material-symbols-rounded text-secondary">power_settings_new</span>
                        </div>
                    </div>

                    <div class="p-6 bg-secondary/10 dark:bg-secondary/5 rounded-[2rem] border border-secondary/20 space-y-4">
                         <div class="flex items-center justify-between">
                            <div class="space-y-1 pr-4">
                                <h4 class="text-[11px] font-black text-secondary uppercase tracking-wider italic">Modo Mantenimiento Maestro</h4>
                                <p class="text-[9px] text-secondary/60 dark:text-secondary/40 font-bold leading-relaxed uppercase tracking-widest">
                                    Bloquea el acceso a todas las interfaces excepto SuperAdmin.
                                </p>
                            </div>
                            <Switch v-model="form['maintenance_mode']" variant="secondary" />
                        </div>
                        <div v-if="form['maintenance_mode']" class="p-4 bg-white/10 rounded-xl border border-white/10 animate-pulse">
                            <p class="text-[8px] font-black text-secondary text-center uppercase tracking-[0.2em]">EL SISTEMA SE ENCUENTRA EN AISLAMIENTO</p>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Bloque 2: Mantenimiento de Datos -->
            <div class="space-y-8">
                <Card class="!p-8 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 hover:border-emerald-500/20 transition-all shadow-xl dark:bg-[#0b0e14]">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <h3 class="text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic">Kernel <span class="text-emerald-500 italic font-black">Retention</span></h3>
                            <p class="text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2">Ciclo de vida de registros técnicos</p>
                        </div>
                        <div class="w-12 h-12 bg-emerald-500/10 rounded-2xl flex items-center justify-center">
                            <span class="material-symbols-rounded text-emerald-500">history_toggle_off</span>
                        </div>
                    </div>

                    <div class="space-y-8">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <label class="text-[10px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest">Retención de Logs de Sistema</label>
                                <span class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-lg">{{ form['log_retention_days'] }} DÍAS</span>
                            </div>
                             <input type="range" v-model="form['log_retention_days']" min="7" max="365" step="1" class="w-full h-1.5 bg-surface-container-high dark:bg-white/10 rounded-lg appearance-none cursor-pointer accent-emerald-500" />
                             <p class="text-[8px] font-bold text-on-surface-variant/30 uppercase text-right tracking-widest">Mínimo 7 días — Máximo 1 año</p>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <label class="text-[10px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest">Retención de Auditoría Forense</label>
                                <span class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-3 py-1 rounded-lg">{{ form['audit_retention_days'] }} DÍAS</span>
                            </div>
                             <input type="range" v-model="form['audit_retention_days']" min="30" max="1000" step="30" class="w-full h-1.5 bg-surface-container-high dark:bg-white/10 rounded-lg appearance-none cursor-pointer accent-emerald-500" />
                             <p class="text-[8px] font-bold text-on-surface-variant/30 uppercase text-right tracking-widest">Sección Forense — Hasta 3 años</p>
                        </div>
                    </div>
                </Card>

                <Card class="!p-8 !rounded-[3rem] border border-outline-variant/10 dark:border-white/5 hover:border-blue-500/20 transition-all shadow-xl dark:bg-[#0b0e14]">
                    <div class="flex justify-between items-start mb-10">
                        <div>
                            <h3 class="text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic">Global <span class="text-blue-500 italic font-black">Broadcast</span></h3>
                            <p class="text-[10px] text-on-surface-variant/40 dark:text-white/30 font-black uppercase tracking-widest mt-2">Gestión de notificaciones núcleo</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500/10 rounded-2xl flex items-center justify-center">
                            <span class="material-symbols-rounded text-blue-500">campaign</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-6 bg-blue-500/5 rounded-[2rem] border border-blue-500/10">
                        <div class="space-y-1 pr-4">
                            <h4 class="text-[11px] font-black text-blue-500 uppercase tracking-wider">Servicio de Difusión Activo</h4>
                            <p class="text-[9px] text-blue-500/60 font-medium leading-relaxed">
                                Habilita los anuncios globales en todos los dashboards.
                            </p>
                        </div>
                        <Switch v-model="form['system_announcements']" />
                    </div>
                </Card>
            </div>
        </form>

        <!-- Guardado Flotante Móvil (Opcional UX) -->
        <div class="md:hidden fixed bottom-10 left-6 right-6 z-50">
             <Button 
                @click="submit"
                variant="primary" 
                icon="save" 
                :loading="form.processing"
                class="w-full !h-16 !rounded-3xl shadow-[0_20px_50px_rgba(var(--primary),0.3)] !text-[12px] font-black uppercase"
            >
                Guardar Nucleo
            </Button>
        </div>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
/* Estilo personalizado para el slider */
input[type='range']::-webkit-slider-thumb {
    appearance: none;
    width: 20px;
    height: 20px;
    background: white;
    border: 4px solid #10b981;
    border-radius: 50%;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    transition: all 0.2s;
}
input[type='range']::-webkit-slider-thumb:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
}
</style>
