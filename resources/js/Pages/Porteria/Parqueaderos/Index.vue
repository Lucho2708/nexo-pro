<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import SecurityLayout from '@/Layouts/SecurityLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Modal from '@/Components/UI/Modal.vue';

defineOptions({ layout: SecurityLayout });

const props = defineProps<{
    registros: any[];
    disponibilidad: {
        capacidad_total: number;
        ocupados: number;
        disponibles: number;
        porcentaje_ocupacion: number;
        alerta_sobrecupo: boolean;
    };
}>();

const isModalOpen = ref(false);
const isLiquidacionModalOpen = ref(false);
const selectedRegistro = ref<any>(null);
const liquidacionData = ref<any>(null);
const isProcessing = ref(false);

const form = useForm({
    placa_vehiculo: '',
    tipo_espacio: 'visitante',
    numero_bahia: '',
});

const submitIngresoVehiculo = () => {
    form.post(route('porteria.parqueaderos.store'), {
        onSuccess: () => {
            form.reset();
            isModalOpen.value = false;
        },
    });
};

const iniciarSalidaVehiculo = async (registro: any) => {
    selectedRegistro.value = registro;
    isProcessing.value = true;

    try {
        const response = await fetch(route('porteria.parqueaderos.liquidar', registro.id), {
            headers: { 'Accept': 'application/json' },
        });
        const data = await response.json();
        if (data.success) {
            liquidacionData.value = data.liquidacion;
            isLiquidacionModalOpen.value = true;
        }
    } catch (e) {
        // Fallback: salida directa si no hay tarificación activa
        router.patch(route('porteria.parqueaderos.salida', registro.id));
    } finally {
        isProcessing.value = false;
    }
};

const confirmarPagoYSalida = (metodoPago: string) => {
    if (!selectedRegistro.value) return;

    isProcessing.value = true;
    router.post(route('porteria.parqueaderos.pagar', selectedRegistro.value.id), {
        metodo_pago: metodoPago,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            router.patch(route('porteria.parqueaderos.salida', selectedRegistro.value.id), {}, {
                onSuccess: () => {
                    isLiquidacionModalOpen.value = false;
                    selectedRegistro.value = null;
                    liquidacionData.value = null;
                },
                onFinish: () => { isProcessing.value = false; }
            });
        },
        onError: () => { isProcessing.value = false; }
    });
};

const salidaCortesiaDirecta = () => {
    if (!selectedRegistro.value) return;
    isProcessing.value = true;
    router.patch(route('porteria.parqueaderos.salida', selectedRegistro.value.id), {}, {
        onSuccess: () => {
            isLiquidacionModalOpen.value = false;
            selectedRegistro.value = null;
            liquidacionData.value = null;
        },
        onFinish: () => { isProcessing.value = false; }
    });
};
</script>

<template>
    <Head title="Control de Parqueaderos • Portería" />

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-emerald-500">Aforo y Capacidad Vehicular</span>
                <h2 class="text-2xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-0.5">
                    Parqueaderos de Visitantes
                </h2>
            </div>

            <button
                @click="isModalOpen = true"
                type="button"
                :disabled="disponibilidad.alerta_sobrecupo"
                class="min-h-[52px] px-6 py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 disabled:opacity-40 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/25 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
            >
                <span class="material-symbols-rounded text-xl">directions_car</span>
                {{ disponibilidad.alerta_sobrecupo ? 'Parqueadero Lleno' : 'Ingreso de Vehículo' }}
            </button>
        </div>

        <!-- Banner Aforo Visual -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <Card class="p-6">
                <span class="text-[10px] font-black text-on-surface-variant uppercase tracking-widest">Capacidad Total</span>
                <p class="text-3xl font-black text-on-surface dark:text-white mt-2">{{ disponibilidad.capacidad_total }} bahías</p>
            </Card>
            <Card class="p-6">
                <span class="text-[10px] font-black text-amber-500 uppercase tracking-widest">Vehículos Dentro</span>
                <p class="text-3xl font-black text-amber-500 mt-2">{{ disponibilidad.ocupados }} ocupadas</p>
            </Card>
            <Card class="p-6" :class="disponibilidad.alerta_sobrecupo ? 'border-rose-500/50 bg-rose-500/5' : ''">
                <span class="text-[10px] font-black uppercase tracking-widest" :class="disponibilidad.alerta_sobrecupo ? 'text-rose-500' : 'text-emerald-500'">Cupos Disponibles</span>
                <p class="text-3xl font-black mt-2" :class="disponibilidad.alerta_sobrecupo ? 'text-rose-500' : 'text-emerald-500'">{{ disponibilidad.disponibles }} libres</p>
            </Card>
        </div>

        <!-- Active Vehicles Table -->
        <Card class="overflow-hidden border border-outline-variant/15 w-full max-w-full shadow-sm">
            <div class="p-6 border-b border-outline-variant/10 flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-tight text-on-surface dark:text-white flex items-center gap-2">
                    <span class="material-symbols-rounded text-emerald-500 notranslate" translate="no">garage</span>
                    Vehículos en Parqueadero de Visitantes
                </h3>
                <span class="text-xs font-bold text-on-surface-variant">{{ registros.length }} activos</span>
            </div>

            <div class="w-full max-w-full overflow-x-auto overscroll-x-contain touch-pan-x">
                <table class="w-full min-w-[650px] text-left text-xs">
                    <thead class="bg-surface-container-high/50 dark:bg-white/5 border-b border-outline-variant/10 text-[10px] font-black uppercase tracking-widest text-on-surface-variant">
                        <tr>
                            <th class="p-4">Placa</th>
                            <th class="p-4">Bahía</th>
                            <th class="p-4">Tipo</th>
                            <th class="p-4">Hora de Ingreso</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        <tr v-for="reg in registros" :key="reg.id" class="hover:bg-surface-container/30 transition-colors">
                            <td class="p-4 font-mono font-black text-sm text-primary uppercase">{{ reg.placa_vehiculo }}</td>
                            <td class="p-4 font-bold uppercase">{{ reg.numero_bahia || 'General' }}</td>
                            <td class="p-4 text-on-surface-variant font-bold uppercase text-[10px]">{{ reg.tipo_espacio }}</td>
                            <td class="p-4 text-on-surface-variant text-[11px]">
                                {{ new Date(reg.ingreso_at).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' }) }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-500 border border-emerald-500/20">
                                    Adentro
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button
                                    @click="iniciarSalidaVehiculo(reg)"
                                    type="button"
                                    :disabled="isProcessing"
                                    class="min-h-[40px] px-3.5 py-1.5 rounded-xl bg-surface-container-high hover:bg-rose-500 hover:text-white text-on-surface text-[10px] font-black uppercase tracking-wider transition-all border border-outline-variant/20 inline-flex items-center gap-1.5"
                                >
                                    <span class="material-symbols-rounded text-sm">output</span>
                                    Salida / Cobro
                                </button>
                            </td>
                        </tr>
                        <tr v-if="registros.length === 0">
                            <td colspan="6" class="p-12 text-center text-on-surface-variant font-bold uppercase text-xs">
                                No hay vehículos en parqueadero de visitantes actualmente.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>

        <!-- Modal Ingreso Vehículo -->
        <Modal :show="isModalOpen" @close="isModalOpen = false" max-width="sm">
            <form @submit.prevent="submitIngresoVehiculo" class="p-6 md:p-8 space-y-6">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.25em] text-emerald-500">Control Vehicular</span>
                    <h3 class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-1">
                        Ingreso de Vehículo
                    </h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Placa del Vehículo *</label>
                        <input v-model="form.placa_vehiculo" type="text" required placeholder="Ej: ABC-123" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-sm font-mono font-black text-on-surface focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Número de Bahía Asignada</label>
                        <input v-model="form.numero_bahia" type="text" placeholder="Ej: V-04" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-emerald-500 focus:outline-none uppercase" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button @click="isModalOpen = false" type="button" class="min-h-[44px] px-4 rounded-xl text-xs font-black uppercase tracking-wider text-on-surface-variant hover:bg-surface-container">
                        Cancelar
                    </button>
                    <button :disabled="form.processing" type="submit" class="min-h-[44px] px-6 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-wider shadow-md disabled:opacity-50">
                        Registrar Entrada
                    </button>
                </div>
            </form>
        </Modal>

        <!-- Modal Liquidación y Checkout Fintech de Parqueadero -->
        <Modal :show="isLiquidacionModalOpen" @close="isLiquidacionModalOpen = false" max-width="md">
            <div class="p-6 md:p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-outline-variant/10 pb-4">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Liquidación de Parqueadero</span>
                        <h3 class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-0.5">
                            Salida Vehículo {{ selectedRegistro?.placa_vehiculo }}
                        </h3>
                    </div>
                    <button @click="isLiquidacionModalOpen = false" type="button" class="p-2 text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>

                <!-- Detalle de Tarificación -->
                <div v-if="liquidacionData" class="space-y-4">
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="p-3.5 rounded-2xl bg-surface-container border border-outline-variant/15">
                            <span class="text-[10px] font-bold text-on-surface-variant uppercase">Permanencia Total</span>
                            <p class="text-base font-black text-on-surface dark:text-white mt-0.5">
                                {{ liquidacionData.minutos_permanencia }} minutos
                            </p>
                        </div>
                        <div class="p-3.5 rounded-2xl bg-surface-container border border-outline-variant/15">
                            <span class="text-[10px] font-bold text-on-surface-variant uppercase">Horas Cobrables</span>
                            <p class="text-base font-black text-on-surface dark:text-white mt-0.5">
                                {{ liquidacionData.horas_cobrables }} h
                            </p>
                        </div>
                    </div>

                    <!-- Total a Pagar -->
                    <div 
                        class="p-5 rounded-2xl border text-center space-y-1"
                        :class="liquidacionData.es_cortesia ? 'bg-emerald-500/10 border-emerald-500/30' : 'bg-primary/10 border-primary/30'"
                    >
                        <span class="text-[10px] font-black uppercase tracking-widest" :class="liquidacionData.es_cortesia ? 'text-emerald-500' : 'text-primary'">
                            {{ liquidacionData.es_cortesia ? 'Tiempo de Cortesía Gratuito' : 'Total a Liquidar' }}
                        </span>
                        <p class="text-3xl font-black tracking-tight" :class="liquidacionData.es_cortesia ? 'text-emerald-500' : 'text-primary'">
                            $ {{ Number(liquidacionData.valor_liquidado).toLocaleString('es-CO') }} COP
                        </p>
                        <p v-if="liquidacionData.referencia_pago" class="text-[11px] font-mono text-on-surface-variant font-bold mt-1">
                            Ref: {{ liquidacionData.referencia_pago }}
                        </p>
                    </div>

                    <!-- Acciones según caso: Cortesía vs Pago Excedente -->
                    <div v-if="liquidacionData.es_cortesia" class="pt-2">
                        <button
                            @click="salidaCortesiaDirecta"
                            type="button"
                            :disabled="isProcessing"
                            class="w-full min-h-[52px] px-6 py-3.5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/25 transition-all flex items-center justify-center gap-2 active:scale-95"
                        >
                            <span class="material-symbols-rounded text-xl">check_circle</span>
                            Autorizar Salida Gratuita
                        </button>
                    </div>

                    <div v-else class="space-y-3 pt-2">
                        <!-- Botón Pago Digital Wompi / PSE -->
                        <button
                            @click="confirmarPagoYSalida('wompi_pse')"
                            type="button"
                            :disabled="isProcessing"
                            class="w-full min-h-[52px] px-6 py-3.5 rounded-2xl bg-primary hover:bg-primary/90 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/25 transition-all flex items-center justify-center gap-2 active:scale-95"
                        >
                            <span class="material-symbols-rounded text-xl">qr_code_2</span>
                            Cobrar con Wompi / PSE (QR Digital)
                        </button>

                        <!-- Botón Pago en Efectivo Garita -->
                        <button
                            @click="confirmarPagoYSalida('efectivo')"
                            type="button"
                            :disabled="isProcessing"
                            class="w-full min-h-[48px] px-6 py-3 rounded-2xl bg-surface-container-high hover:bg-surface-container text-on-surface font-black text-xs uppercase tracking-wider border border-outline-variant/20 transition-all flex items-center justify-center gap-2"
                        >
                            <span class="material-symbols-rounded text-lg">payments</span>
                            Registrar Pago en Efectivo
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end pt-2 border-t border-outline-variant/10">
                    <button
                        @click="isLiquidacionModalOpen = false"
                        type="button"
                        class="min-h-[44px] px-5 rounded-xl text-xs font-black uppercase tracking-wider text-on-surface-variant hover:bg-surface-container"
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </Modal>
    </div>
</template>
