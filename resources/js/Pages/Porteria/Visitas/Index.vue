<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import SecurityLayout from '@/Layouts/SecurityLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Modal from '@/Components/UI/Modal.vue';
import { parseColombianIdBarcode } from '@/Utils/colombianIdParser';

defineOptions({ layout: SecurityLayout });

const props = defineProps<{
    visitas: any[];
    unidades: any[];
}>();

const isModalOpen = ref(false);
const isPaseModalOpen = ref(false);
const barcodeInput = ref('');
const scanFeedback = ref('');
const citofoniaEstado = ref<'inactivo' | 'llamando' | 'autorizado' | 'denegado'>('inactivo');

const handleBarcodeScan = () => {
    if (!barcodeInput.value) return;
    const parsed = parseColombianIdBarcode(barcodeInput.value);
    if (parsed) {
        form.visitante_documento = parsed.documento;
        form.visitante_nombre = parsed.nombreCompleto;
        scanFeedback.value = `${parsed.nombreCompleto} (${parsed.documento})`;
        barcodeInput.value = '';
    }
};

const form = useForm({
    unidad_id: '',
    visitante_nombre: '',
    visitante_documento: '',
    tipo_visita: 'personal',
    placa_vehiculo: '',
    empresa_servicio: '',
    pre_autorizado: false,
    observaciones: '',
});

const paseForm = useForm({
    token_o_pin: '',
    visitante_nombre: '',
    visitante_documento: '',
    placa_vehiculo: '',
});

const submitPase = () => {
    paseForm.post(route('porteria.visitas.validar-pase'), {
        preserveScroll: true,
        onSuccess: () => {
            paseForm.reset();
            isPaseModalOpen.value = false;
        },
    });
};

const llamarCitofonia = () => {
    if (!form.unidad_id || !form.visitante_nombre) return;
    citofoniaEstado.value = 'llamando';
    router.post(route('porteria.citofonia.solicitar'), {
        unidad_id: form.unidad_id,
        visitante_nombre: form.visitante_nombre,
        visitante_documento: form.visitante_documento,
        tipo_visita: form.tipo_visita,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            citofoniaEstado.value = 'llamando';
        },
        onError: () => {
            citofoniaEstado.value = 'inactivo';
        },
    });
};

const calcularPermanencia = (ingresoAt: string, limiteMinutos: number = 15) => {
    const diff = Math.floor((Date.now() - new Date(ingresoAt).getTime()) / 60000);
    const restante = limiteMinutos - diff;
    return {
        excedido: restante < 0,
        minutosExcedidos: Math.abs(restante),
        minutosRestantes: Math.max(0, restante),
        transcurridos: diff,
    };
};

const submitIngreso = () => {
    form.post(route('porteria.visitas.store'), {
        onSuccess: () => {
            form.reset();
            isModalOpen.value = false;
        },
    });
};

const marcarSalida = (visitaId: string) => {
    router.patch(route('porteria.visitas.salida', visitaId));
};
</script>

<template>
    <Head title="Control de Visitas • Portería" />

    <div class="space-y-6">
        <!-- Banner Informativo: Copropiedad Sin Propiedades Registradas -->
        <div v-if="!unidades || unidades.length === 0" class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/25 flex items-start gap-3">
            <span class="material-symbols-rounded text-amber-500 text-xl shrink-0 notranslate" translate="no">domain_disabled</span>
            <div class="space-y-1">
                <h4 class="text-xs font-black uppercase tracking-wider text-amber-500">Aún no se cuenta con registro de propiedades</h4>
                <p class="text-xs text-on-surface-variant font-medium leading-relaxed">
                    Esta copropiedad no tiene apartamentos, torres o unidades creadas en el sistema. Los registros de garita requieren asociar una propiedad válida. Contacte a la administración para poblar el censo de inmuebles.
                </p>
            </div>
        </div>

        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Control de Acceso Peatonal & Vehicular</span>
                <h2 class="text-2xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-0.5">
                    Registro de Visitantes
                </h2>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <button
                    @click="isPaseModalOpen = true"
                    type="button"
                    class="min-h-[52px] px-5 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/25 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
                >
                    <span class="material-symbols-rounded text-xl">qr_code_scanner</span>
                    Validar Pase QR / PIN
                </button>

                <button
                    @click="isModalOpen = true"
                    type="button"
                    class="min-h-[52px] px-6 py-3.5 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/25 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
                >
                    <span class="material-symbols-rounded text-xl">person_add</span>
                    Registrar Nuevo Ingreso
                </button>
            </div>
        </div>

        <!-- Visitas List -->
        <Card class="overflow-hidden border border-outline-variant/15">
            <div class="p-6 border-b border-outline-variant/10 flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-tight text-on-surface dark:text-white flex items-center gap-2">
                    <span class="material-symbols-rounded text-primary">history</span>
                    Últimos Ingresos Registrados
                </h3>
                <span class="text-xs font-bold text-on-surface-variant">{{ visitas.length }} registros</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-surface-container-high/50 dark:bg-white/5 border-b border-outline-variant/10 text-[10px] font-black uppercase tracking-widest text-on-surface-variant">
                        <tr>
                            <th class="p-4">Visitante</th>
                            <th class="p-4">Documento</th>
                            <th class="p-4">Destino</th>
                            <th class="p-4">Tipo</th>
                            <th class="p-4">Placa</th>
                            <th class="p-4">Ingreso</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        <tr v-for="visita in visitas" :key="visita.id" class="hover:bg-surface-container/30 transition-colors">
                            <td class="p-4 font-black text-on-surface dark:text-white uppercase">{{ visita.visitante_nombre }}</td>
                            <td class="p-4 font-mono text-on-surface-variant">{{ visita.visitante_documento }}</td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg bg-primary/10 text-primary font-black uppercase text-[10px]">
                                    {{ visita.unidad?.nombre || 'Unidad N/A' }} {{ (visita.unidad?.torre || visita.unidad?.bloque) ? `(${visita.unidad?.torre || visita.unidad?.bloque})` : '' }}
                                </span>
                            </td>
                            <td class="p-4 font-bold uppercase text-[10px] text-on-surface-variant">
                                <div class="flex items-center gap-1.5">
                                    <span>{{ visita.tipo_visita }}</span>
                                    <span 
                                        v-if="visita.pre_autorizado" 
                                        class="px-1.5 py-0.5 rounded bg-indigo-500/10 text-indigo-500 font-black text-[9px] border border-indigo-500/20"
                                    >
                                        QR
                                    </span>
                                </div>
                                <!-- Temporizador express para domicilios y taxis -->
                                <div v-if="visita.estado === 'ingresado' && (visita.tipo_visita === 'domicilio' || visita.tipo_visita === 'taxi')" class="mt-1">
                                    <span 
                                        v-if="calcularPermanencia(visita.ingreso_at, visita.tiempo_limite_minutos || 15).excedido"
                                        class="px-2 py-0.5 rounded-md bg-rose-500 text-white font-black text-[9px] uppercase tracking-wider animate-pulse inline-flex items-center gap-1"
                                    >
                                        ⚠️ Excedido {{ calcularPermanencia(visita.ingreso_at, visita.tiempo_limite_minutos || 15).minutosExcedidos }}m
                                    </span>
                                    <span 
                                        v-else
                                        class="px-2 py-0.5 rounded-md bg-amber-500/10 text-amber-500 border border-amber-500/20 font-black text-[9px] uppercase tracking-wider inline-flex items-center gap-1"
                                    >
                                        ⏱ Restante {{ calcularPermanencia(visita.ingreso_at, visita.tiempo_limite_minutos || 15).minutosRestantes }}m
                                    </span>
                                </div>
                            </td>
                            <td class="p-4 font-mono font-bold">{{ visita.placa_vehiculo || '—' }}</td>
                            <td class="p-4 text-on-surface-variant text-[11px]">
                                {{ new Date(visita.ingreso_at).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' }) }}
                            </td>
                            <td class="p-4">
                                <span 
                                    class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider inline-flex items-center gap-1"
                                    :class="visita.estado === 'ingresado' ? 'bg-emerald-500/10 text-emerald-500 border border-emerald-500/20' : 'bg-surface-container text-on-surface-variant'"
                                >
                                    <span v-if="visita.estado === 'ingresado'" class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    {{ visita.estado }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button
                                    v-if="visita.estado === 'ingresado'"
                                    @click="marcarSalida(visita.id)"
                                    type="button"
                                    class="min-h-[40px] px-3.5 py-1.5 rounded-xl bg-surface-container-high hover:bg-rose-500 hover:text-white text-on-surface text-[10px] font-black uppercase tracking-wider transition-all border border-outline-variant/20 inline-flex items-center gap-1.5"
                                >
                                    <span class="material-symbols-rounded text-sm">logout</span>
                                    Salida
                                </button>
                                <span v-else class="text-[10px] font-bold text-on-surface-variant/40 uppercase">Finalizado</span>
                            </td>
                        </tr>
                        <tr v-if="visitas.length === 0">
                            <td colspan="8" class="p-12 text-center text-on-surface-variant font-bold uppercase text-xs">
                                No hay visitas registradas el día de hoy.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>

        <!-- Modal Registro Rápido de Visita -->
        <Modal :show="isModalOpen" @close="isModalOpen = false" max-width="lg">
            <form @submit.prevent="submitIngreso" class="p-6 md:p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-outline-variant/10 pb-4">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Ingreso en Garita</span>
                        <h3 class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white">
                            Registrar Visitante
                        </h3>
                    </div>
                    <button @click="isModalOpen = false" type="button" class="p-2 text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>

                <!-- Lector Rápido de Cédula PDF417 -->
                <div class="p-4 rounded-2xl bg-primary/10 border border-primary/25 space-y-2">
                    <div class="flex items-center justify-between">
                        <label class="text-[10px] font-black uppercase tracking-wider text-primary flex items-center gap-1.5">
                            <span class="material-symbols-rounded text-base">barcode_scanner</span>
                            Lector Rápido de Cédula (Pistola USB / Código PDF417)
                        </label>
                        <span v-if="scanFeedback" class="text-[10px] font-black text-emerald-500 animate-in fade-in flex items-center gap-1">
                            <span class="material-symbols-rounded text-sm">check_circle</span>
                            {{ scanFeedback }}
                        </span>
                    </div>
                    <input 
                        v-model="barcodeInput" 
                        @input="handleBarcodeScan" 
                        type="text" 
                        placeholder="Dispara con la pistola lectora o pega el código de barras..."
                        class="w-full min-h-[44px] px-3.5 rounded-xl bg-surface dark:bg-[#06080D] border border-primary/30 text-xs font-mono text-on-surface focus:ring-2 focus:ring-primary focus:outline-none"
                    />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-if="!unidades || unidades.length === 0" class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-500 text-xs font-bold flex items-center gap-2 md:col-span-2">
                        <span class="material-symbols-rounded text-base notranslate" translate="no">warning</span>
                        <span>Aún no hay unidades registradas en el conjunto. Ingreso manual deshabilitado.</span>
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Apartamento / Unidad Destino *</label>
                        <select v-model="form.unidad_id" required :disabled="!unidades || unidades.length === 0" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none disabled:opacity-50">
                            <option value="" disabled>{{ (!unidades || unidades.length === 0) ? 'No hay unidades registradas en el conjunto' : 'Seleccione unidad...' }}</option>
                            <option v-for="u in unidades" :key="u.id" :value="u.id">
                                {{ u.nombre }} {{ (u.torre || u.bloque) ? `(${u.torre || u.bloque})` : '' }}
                            </option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Nombre Completo *</label>
                        <input v-model="form.visitante_nombre" type="text" required placeholder="Ej: Pedro Martinez" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Cédula / Documento *</label>
                        <input v-model="form.visitante_documento" type="text" required placeholder="Ej: 1020304050" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Tipo de Visita</label>
                        <select v-model="form.tipo_visita" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                            <option value="personal">Visita Personal</option>
                            <option value="domicilio">Servicio a Domicilio</option>
                            <option value="contratista">Contratista / Mantenimiento</option>
                            <option value="taxi">Taxi / Transporte</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Placa Vehicular (Opcional)</label>
                        <input v-model="form.placa_vehiculo" type="text" placeholder="Ej: ABC-123" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none uppercase" />
                    </div>

                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Observaciones</label>
                        <input v-model="form.observaciones" type="text" placeholder="Notas adicionales de garita..." class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none" />
                    </div>

                    <!-- Citofonía Virtual Interactiva WhatsApp -->
                    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/25 md:col-span-2 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div>
                            <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest flex items-center gap-1">
                                <span class="material-symbols-rounded text-sm">phone_in_talk</span>
                                Citofonía Virtual • Reemplazo Físico
                            </span>
                            <p class="text-xs font-bold text-on-surface dark:text-white mt-0.5">
                                ¿Requiere autorización del residente?
                            </p>
                            <p class="text-[11px] text-on-surface-variant font-medium">
                                Envía mensaje interactivo con botón autorizar a su WhatsApp.
                            </p>
                        </div>
                        <button
                            @click="llamarCitofonia"
                            type="button"
                            :disabled="!form.unidad_id || !form.visitante_nombre"
                            class="min-h-[44px] px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-wider disabled:opacity-40 flex items-center justify-center gap-2 transition-all shadow-sm active:scale-95 shrink-0"
                        >
                            <span class="material-symbols-rounded text-lg">ring_volume</span>
                            <span v-if="citofoniaEstado === 'llamando'">Timbrando...</span>
                            <span v-else>Timbrar Celular</span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/10">
                    <button @click="isModalOpen = false" type="button" class="min-h-[48px] px-5 rounded-xl text-xs font-black uppercase tracking-wider text-on-surface-variant hover:bg-surface-container">
                        Cancelar
                    </button>
                    <button :disabled="form.processing || !unidades || unidades.length === 0" type="submit" class="min-h-[48px] px-6 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-wider shadow-md hover:bg-primary/90 disabled:opacity-50">
                        Confirmar Ingreso
                    </button>
                </div>
            </form>
        </Modal>

        <!-- Modal Canje Rápido de Pase QR / PIN -->
        <Modal :show="isPaseModalOpen" @close="isPaseModalOpen = false" max-width="md">
            <form @submit.prevent="submitPase" class="p-6 md:p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-outline-variant/10 pb-4">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.25em] text-indigo-500">Acceso Pre-autorizado</span>
                        <h3 class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white">
                            Validar Pase QR / PIN
                        </h3>
                    </div>
                    <button @click="isPaseModalOpen = false" type="button" class="p-2 text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-indigo-500">
                            Escanear Código QR o Ingresar PIN de 6 Caracteres *
                        </label>
                        <input 
                            v-model="paseForm.token_o_pin" 
                            type="text" 
                            required 
                            autofocus 
                            placeholder="Pega el token QR o ingresa PIN (Ej: 9X4K2A)..."
                            class="w-full min-h-[52px] px-4 rounded-xl bg-surface-container-low border border-indigo-500/30 text-center font-mono text-base font-black uppercase text-on-surface focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        />
                        <p v-if="paseForm.errors.token_o_pin" class="text-xs font-bold text-rose-500 animate-in shake">
                            {{ paseForm.errors.token_o_pin }}
                        </p>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Nombre del Visitante (Opcional si viene en pase)</label>
                        <input v-model="paseForm.visitante_nombre" type="text" placeholder="Ej: Diana Mendoza" class="w-full min-h-[44px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Placa Vehicular (Opcional)</label>
                        <input v-model="paseForm.placa_vehiculo" type="text" placeholder="Ej: XYZ-789" class="w-full min-h-[44px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-indigo-500 focus:outline-none uppercase" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/10">
                    <button @click="isPaseModalOpen = false" type="button" class="min-h-[48px] px-5 rounded-xl text-xs font-black uppercase tracking-wider text-on-surface-variant hover:bg-surface-container">
                        Cancelar
                    </button>
                    <button :disabled="paseForm.processing" type="submit" class="min-h-[48px] px-6 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-wider shadow-md disabled:opacity-50 flex items-center gap-2">
                        <span v-if="paseForm.processing" class="material-symbols-rounded animate-spin text-sm">progress_activity</span>
                        <span>Validar e Ingresar</span>
                    </button>
                </div>
            </form>
        </Modal>
    </div>
</template>
