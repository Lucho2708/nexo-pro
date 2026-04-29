<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Checkbox from '@/Components/UI/Checkbox.vue';
import Badge from '@/Components/UI/Badge.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    copropiedad: any;
    settings: any;
}>();

const toast = useToast();

const form = useForm({
    settings: {
        payments_enabled: props.settings.payments_enabled ?? false,
        wompi_public_key: props.settings.wompi_public_key ?? '',
        wompi_private_key: props.settings.wompi_private_key ?? '',
        wompi_integrity_key: props.settings.wompi_integrity_key ?? '',
        wompi_webhook_secret: props.settings.wompi_webhook_secret ?? '',
        pqrs_enabled: props.settings.pqrs_enabled ?? true,
        reservas_enabled: props.settings.reservas_enabled ?? true,
        interes_mora: props.settings.interes_mora ?? 1.5,
        gateways: props.settings.gateways ?? {
            wompi: { enabled: false, label: 'Wompi (PSE, Tarjetas, Nequi)', type: 'gateway' },
            aval: { enabled: false, label: 'Aval Pay Center', type: 'redirect', url: '' },
            manual: { enabled: true, label: 'Efectivo / Consignación', type: 'instruction' }
        }
    }
});

const submit = () => {
    form.patch(route('admin.settings.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.add('Núcleo de configuración actualizado', 'success');
        }
    });
};
</script>

<template>
    <Head title="Configuración — NEXO-PRO" />

    <div class="space-y-12 pb-32 animate-in fade-in slide-in-from-bottom-4 duration-700">
        
        <!-- Interactive Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 px-1">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary rounded-full shadow-[0_0_10px_rgba(var(--primary),0.5)]"></div>
                    <p class="text-[10px] font-black text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.4em] italic leading-none">Dispatcher de Parámetros Globales</p>
                </div>
                <h2 class="text-5xl font-black text-on-surface dark:text-white tracking-tighter uppercase italic leading-none">Sala de <span class="text-primary italic">Máquinas</span></h2>
                <p class="text-[11px] font-bold text-on-surface-variant/40 dark:text-white/10 uppercase tracking-[0.2em] mt-2 leading-relaxed italic">Gestión de llaves de integración, módulos y reglas financieras del conjunto</p>
            </div>
            <Badge variant="primary" class="!px-6 !py-2 !rounded-xl !text-[10px] font-black uppercase tracking-widest italic border-2">Copropiedad ID: {{ copropiedad.id.split('-')[0] }}</Badge>
        </div>

        <form @submit.prevent="submit" class="grid grid-cols-1 xl:grid-cols-12 gap-10 items-start">
            
            <!-- SECTION 1: PAYMENTS & GATEWAYS -->
            <div class="xl:col-span-8 space-y-10">
                
                <!-- Wompi Integration Card -->
                <Card class="!p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14]">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 bg-emerald-500/10 rounded-2xl flex items-center justify-center text-emerald-500 shadow-inner">
                                <span class="material-symbols-rounded text-2xl">payments</span>
                            </div>
                            <div>
                                <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Pasarela <span class="text-emerald-500">Wompi</span></h3>
                                <p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Integración oficial para recaudo automatizado</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                             <div class="px-6 py-3 bg-emerald-500/10 rounded-2xl flex items-center gap-3 border border-emerald-500/20">
                                 <Checkbox v-model:checked="form.settings.payments_enabled" class="!w-6 !h-6" />
                                 <span class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">SISTEMA ACTIVO</span>
                             </div>
                        </div>
                    </div>

                    <div v-if="form.settings.payments_enabled" class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-6 border-t border-outline-variant/5 dark:border-white/5 animate-in slide-in-from-top-4 duration-500">
                        <Input
                            v-model="form.settings.wompi_public_key"
                            label="LLAVE PÚBLICA (PUB_TEST_...)"
                            placeholder="Ingrese su public key oficial"
                            :error="form.errors['settings.wompi_public_key']"
                            class="!rounded-2xl"
                        />
                        <Input
                            v-model="form.settings.wompi_integrity_key"
                            label="LLAVE DE INTEGRIDAD"
                            placeholder="Ingrese su integrity key"
                            :error="form.errors['settings.wompi_integrity_key']"
                            class="!rounded-2xl"
                        />
                        <Input
                            v-model="form.settings.wompi_private_key"
                            label="LLAVE PRIVADA (PRV_...)"
                            type="password"
                            placeholder="Ingrese su private key"
                            :error="form.errors['settings.wompi_private_key']"
                            class="!rounded-2xl"
                        />
                        <Input
                            v-model="form.settings.wompi_webhook_secret"
                            label="WEBHOOK SECRET"
                            type="password"
                            placeholder="Ingrese su webhook secret"
                            :error="form.errors['settings.wompi_webhook_secret']"
                            class="!rounded-2xl"
                        />
                        
                        <div class="col-span-2 p-6 bg-primary/5 rounded-[2rem] border border-primary/20 flex gap-4 text-primary relative overflow-hidden group">
                            <div class="absolute inset-0 bg-primary/5 translate-x-full group-hover:translate-x-0 transition-transform duration-[2s]"></div>
                            <span class="material-symbols-rounded shrink-0 relative z-10">info</span>
                            <div class="relative z-10 space-y-2">
                                <p class="text-[11px] font-black uppercase tracking-widest leading-none">Protocolo de Redirección Webhook</p>
                                <p class="text-[9px] font-medium leading-relaxed italic">
                                    Enlace obligatorio para Dash de Wompi: 
                                    <span class="bg-primary/10 px-2 py-0.5 rounded italic select-all cursor-pointer">https://{{ $page.props.auth.user.current_copropiedad?.domain || 'nexo-pro.io' }}/payments/webhook</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div v-else class="py-12 text-center opacity-30 border-t border-outline-variant/10 dark:border-white/5 mt-6">
                        <span class="material-symbols-rounded text-6xl">lock_open</span>
                        <p class="text-[10px] font-black uppercase tracking-[0.4em] mt-4">Integración de Pagos Desactivada</p>
                    </div>
                </Card>

                <!-- Gateways Monitor -->
                <Card v-if="form.settings.payments_enabled" class="!rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14] overflow-hidden">
                    <template #header>
                        <div class="p-10 border-b border-outline-variant/5 dark:border-white/5 flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Métodos de <span class="text-primary">Fondeo</span></h3>
                                <p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Gestión de canales de pago visibles para el residente</p>
                            </div>
                        </div>
                    </template>

                    <div class="divide-y divide-outline-variant/5 dark:divide-white/5">
                        <div v-for="(gateway, key) in form.settings.gateways" :key="key" class="p-8 hover:bg-surface-container-low dark:hover:bg-white/5 transition-all flex flex-col md:flex-row md:items-center justify-between gap-6 group">
                            <div class="flex items-center gap-6">
                                <div class="w-14 h-14 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center text-on-surface-variant border border-outline-variant/10 group-hover:bg-primary group-hover:text-white transition-all shadow-xl shadow-black/5">
                                    <span class="material-symbols-rounded text-2xl">{{ key === 'wompi' ? 'account_balance' : (key === 'aval' ? 'account_balance_wallet' : 'payments') }}</span>
                                </div>
                                <div>
                                    <h4 class="text-base font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">{{ gateway.label }}</h4>
                                    <p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest mt-2 italic">{{ key === 'manuel' ? 'REGISTRO DE SOPORTE FÍSICO' : 'CANAL DE PAGO DIGITAL' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-8">
                                <div v-if="gateway.enabled && key === 'aval'" class="animate-in slide-in-from-right-4">
                                    <Input
                                        v-model="gateway.url"
                                        label="URL POINT-OF-SALE"
                                        placeholder="https://..."
                                        :error="form.errors[`settings.gateways.${key}.url`]"
                                        class="!rounded-xl !h-12 !w-64"
                                    />
                                </div>
                                <div class="flex items-center gap-4 bg-surface-container-high dark:bg-white/5 px-6 py-4 rounded-2xl border border-outline-variant/10 group-hover:border-primary/20 transition-all">
                                    <span class="text-[9px] font-black uppercase tracking-widest" :class="gateway.enabled ? 'text-primary' : 'text-on-surface-variant/40'">{{ gateway.enabled ? 'HABILITADO' : 'INACTIVO' }}</span>
                                    <Checkbox v-model:checked="gateway.enabled" class="!w-6 !h-6" />
                                </div>
                            </div>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- SECTION 2: MODULES & GLOBAL RULES -->
            <div class="xl:col-span-4 space-y-10">
                
                <!-- Modules Control -->
                <Card class="!p-10 !rounded-[3.5rem] border border-outline-variant/10 dark:border-white/5 shadow-2xl dark:bg-[#0b0e14]">
                    <h3 class="text-xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none mb-8">Hardware de <span class="text-primary italic">Módulos</span></h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-6 rounded-[2rem] bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-rounded text-xl">forum</span>
                                </div>
                                <span class="text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest italic group-hover:text-primary transition-colors">Integración PQRS</span>
                            </div>
                            <Checkbox v-model:checked="form.settings.pqrs_enabled" />
                        </div>

                        <div class="flex items-center justify-between p-6 rounded-[2rem] bg-surface-container-low dark:bg-white/[0.02] border border-outline-variant/10 dark:border-white/5 hover:border-primary/30 transition-all group">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                    <span class="material-symbols-rounded text-xl">event_available</span>
                                </div>
                                <span class="text-[11px] font-black text-on-surface-variant dark:text-white/40 uppercase tracking-widest italic group-hover:text-primary transition-colors">Sistema Reservas</span>
                            </div>
                            <Checkbox v-model:checked="form.settings.reservas_enabled" />
                        </div>
                    </div>

                    <!-- Market Rules -->
                    <div class="mt-12 pt-8 border-t border-outline-variant/5 dark:border-white/5 space-y-6">
                        <div class="flex items-end gap-4">
                            <div class="flex-1">
                                <Input
                                    v-model="form.settings.interes_mora"
                                    type="number"
                                    step="0.01"
                                    label="COEFICIENTE DE MORA (%)"
                                    suffix="%"
                                    class="!rounded-2xl"
                                />
                            </div>
                            <div class="w-12 h-14 bg-surface-container-high dark:bg-white/5 flex items-center justify-center rounded-2xl border border-outline-variant/10">
                                <span class="material-symbols-rounded text-xl opacity-20">trending_up</span>
                            </div>
                        </div>
                        <p class="text-[9px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-widest leading-relaxed italic pr-6 italic px-2">
                            * Este vector porcentual afectará automáticamente los balances financieros al cierre de cada ciclo.
                        </p>
                    </div>
                </Card>

                <!-- Action Hub -->
                <div class="bg-surface-container-highest dark:bg-white/5 p-10 rounded-[3.5rem] border border-outline-variant/10 flex flex-col gap-6">
                    <h4 class="text-xs font-black text-on-surface dark:text-white uppercase tracking-[0.3em] text-center italic">Despachador Maestro</h4>
                    <Button 
                        type="submit" 
                        variant="primary" 
                        size="lg" 
                        class="w-full !h-16 !rounded-2xl !text-[12px] font-black uppercase italic shadow-2xl shadow-primary/20"
                        :loading="form.processing"
                        icon="save"
                    >
                        {{ form.processing ? 'SINCRONIZANDO...' : 'Sincronizar Kernels' }}
                    </Button>
                    <p class="text-[8px] font-bold text-on-surface-variant/30 dark:text-white/10 uppercase tracking-widest text-center mt-2 leading-relaxed">
                        LOS CAMBIOS SON APLICADOS DE FORMA PERMANENTE EN EL NÚCLEO DE LA COPROPIEDAD. VERIFIQUE CADA LLAVE DE API.
                    </p>
                </div>
            </div>
        </form>
    </div>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 24, 'wght' 600, 'FILL' 0;
}
</style>
