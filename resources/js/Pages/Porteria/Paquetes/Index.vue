<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import SecurityLayout from '@/Layouts/SecurityLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Modal from '@/Components/UI/Modal.vue';

defineOptions({ layout: SecurityLayout });

const props = defineProps<{
    paquetes: any[];
    unidades: any[];
}>();

const isModalOpen = ref(false);
const isEntregaModalOpen = ref(false);
const selectedPaquete = ref<any>(null);

const form = useForm({
    unidad_id: '',
    empresa_mensajeria: 'Servientrega',
    codigo_guia: '',
    descripcion: '',
});

const entregaForm = useForm({
    entregado_a_nombre: '',
    pin: '',
});

const submitPaquete = () => {
    form.post(route('porteria.paquetes.store'), {
        onSuccess: () => {
            form.reset();
            isModalOpen.value = false;
        },
    });
};

const abrirEntregaModal = (paquete: any) => {
    selectedPaquete.value = paquete;
    entregaForm.entregado_a_nombre = '';
    entregaForm.pin = '';
    entregaForm.clearErrors();
    isEntregaModalOpen.value = true;
};

const confirmarEntrega = () => {
    if (!selectedPaquete.value) return;
    entregaForm.patch(route('porteria.paquetes.entregar', selectedPaquete.value.id), {
        onSuccess: () => {
            isEntregaModalOpen.value = false;
            selectedPaquete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Paquetería & Encomiendas • Portería" />

    <div class="space-y-6">
        <!-- Banner Informativo: Copropiedad Sin Propiedades Registradas -->
        <div v-if="!unidades || unidades.length === 0" class="p-4 rounded-2xl bg-amber-500/10 border border-amber-500/25 flex items-start gap-3">
            <span class="material-symbols-rounded text-amber-500 text-xl shrink-0 notranslate" translate="no">domain_disabled</span>
            <div class="space-y-1">
                <h4 class="text-xs font-black uppercase tracking-wider text-amber-500">Aún no se cuenta con registro de propiedades</h4>
                <p class="text-xs text-on-surface-variant font-medium leading-relaxed">
                    Esta copropiedad no tiene apartamentos, torres o unidades creadas en el sistema. La recepción de paquetes requiere asociar una propiedad válida. Contacte a la administración para poblar el censo de inmuebles.
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-amber-500">Gestión de Correspondencia</span>
                <h2 class="text-2xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-0.5">
                    Recepción de Paquetería
                </h2>
            </div>

            <button
                @click="isModalOpen = true"
                type="button"
                class="min-h-[52px] px-6 py-3.5 rounded-2xl bg-amber-500 hover:bg-amber-600 text-slate-950 font-black text-xs uppercase tracking-wider shadow-lg shadow-amber-500/25 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
            >
                <span class="material-symbols-rounded text-xl">package_2</span>
                Recibir Nueva Encomienda
            </button>
        </div>

        <Card class="overflow-hidden border border-outline-variant/15">
            <div class="p-6 border-b border-outline-variant/10 flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-tight text-on-surface dark:text-white flex items-center gap-2">
                    <span class="material-symbols-rounded text-amber-500">inventory_2</span>
                    Paquetes Registrados en Garita
                </h3>
                <span class="text-xs font-bold text-on-surface-variant">{{ paquetes.length }} paquetes</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-surface-container-high/50 dark:bg-white/5 border-b border-outline-variant/10 text-[10px] font-black uppercase tracking-widest text-on-surface-variant">
                        <tr>
                            <th class="p-4">Apartamento</th>
                            <th class="p-4">Empresa</th>
                            <th class="p-4">Número de Guía</th>
                            <th class="p-4">PIN Retiro</th>
                            <th class="p-4">Descripción</th>
                            <th class="p-4">Fecha Llegada</th>
                            <th class="p-4">Estado</th>
                            <th class="p-4 text-right">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        <tr v-for="paquete in paquetes" :key="paquete.id" class="hover:bg-surface-container/30 transition-colors">
                            <td class="p-4 font-black text-on-surface dark:text-white uppercase">
                                {{ paquete.unidad?.nombre || 'Unidad N/A' }} {{ (paquete.unidad?.torre || paquete.unidad?.bloque) ? `(${paquete.unidad?.torre || paquete.unidad?.bloque})` : '' }}
                            </td>
                            <td class="p-4 font-bold text-amber-500 uppercase">{{ paquete.empresa_mensajeria }}</td>
                            <td class="p-4 font-mono font-bold">{{ paquete.codigo_guia || 'Sin Guía' }}</td>
                            <td class="p-4">
                                <span class="px-2 py-1 rounded-lg bg-surface-container-high font-mono font-black text-[11px] text-primary border border-primary/20">
                                    {{ paquete.pin_entrega || '••••' }}
                                </span>
                            </td>
                            <td class="p-4 text-on-surface-variant">{{ paquete.descripcion || 'Paquete sellado' }}</td>
                            <td class="p-4 text-on-surface-variant text-[11px]">
                                {{ new Date(paquete.created_at).toLocaleTimeString('es-CO', { hour: '2-digit', minute: '2-digit' }) }}
                            </td>
                            <td class="p-4">
                                <span 
                                    class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider"
                                    :class="paquete.estado === 'entregado' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-amber-500/10 text-amber-500 border border-amber-500/20'"
                                >
                                    {{ paquete.estado }}
                                </span>
                            </td>
                            <td class="p-4 text-right">
                                <button
                                    v-if="paquete.estado !== 'entregado'"
                                    @click="abrirEntregaModal(paquete)"
                                    type="button"
                                    class="min-h-[40px] px-3.5 py-1.5 rounded-xl bg-emerald-500 text-white hover:bg-emerald-600 text-[10px] font-black uppercase tracking-wider transition-all shadow-sm inline-flex items-center gap-1.5"
                                >
                                    <span class="material-symbols-rounded text-sm">check_circle</span>
                                    Entregar
                                </button>
                                <span v-else class="text-[10px] font-bold text-on-surface-variant/40 uppercase">Entregado</span>
                            </td>
                        </tr>
                        <tr v-if="paquetes.length === 0">
                            <td colspan="7" class="p-12 text-center text-on-surface-variant font-bold uppercase text-xs">
                                No hay paquetes pendientes en portería.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>

        <!-- Modal Recibir Paquete -->
        <Modal :show="isModalOpen" @close="isModalOpen = false" max-width="md">
            <form @submit.prevent="submitPaquete" class="p-6 md:p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-outline-variant/10 pb-4">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.25em] text-amber-500">Llegada a Garita</span>
                        <h3 class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white">
                            Recibir Encomienda
                        </h3>
                    </div>
                    <button @click="isModalOpen = false" type="button" class="p-2 text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <div v-if="!unidades || unidades.length === 0" class="p-3.5 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-500 text-xs font-bold flex items-center gap-2">
                        <span class="material-symbols-rounded text-base notranslate" translate="no">warning</span>
                        <span>Aún no hay unidades registradas en el conjunto. Recepción deshabilitada.</span>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Apartamento / Unidad *</label>
                        <select v-model="form.unidad_id" required :disabled="!unidades || unidades.length === 0" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-amber-500 focus:outline-none disabled:opacity-50">
                            <option value="" disabled>{{ (!unidades || unidades.length === 0) ? 'No hay unidades registradas en el conjunto' : 'Seleccione unidad...' }}</option>
                            <option v-for="u in unidades" :key="u.id" :value="u.id">
                                {{ u.nombre }} {{ (u.torre || u.bloque) ? `(${u.torre || u.bloque})` : '' }}
                            </option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Empresa de Mensajería *</label>
                        <input v-model="form.empresa_mensajeria" type="text" required placeholder="Ej: Servientrega, Coordinadora, Amazon, MercadoLibre" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-amber-500 focus:outline-none" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Código de Guía (Opcional)</label>
                        <input v-model="form.codigo_guia" type="text" placeholder="Ej: 99482104" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-amber-500 focus:outline-none uppercase font-mono" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Detalle / Descripción</label>
                        <input v-model="form.descripcion" type="text" placeholder="Ej: Caja mediana, Sobre manila..." class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-amber-500 focus:outline-none" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/10">
                    <button @click="isModalOpen = false" type="button" class="min-h-[48px] px-5 rounded-xl text-xs font-black uppercase tracking-wider text-on-surface-variant hover:bg-surface-container">
                        Cancelar
                    </button>
                    <button :disabled="form.processing || !unidades || unidades.length === 0" type="submit" class="min-h-[48px] px-6 rounded-xl bg-amber-500 hover:bg-amber-600 text-slate-950 text-xs font-black uppercase tracking-wider shadow-md disabled:opacity-50">
                        Guardar y Notificar
                    </button>
                </div>
            </form>
        </Modal>

        <!-- Modal Confirmación de Entrega -->
        <Modal :show="isEntregaModalOpen" @close="isEntregaModalOpen = false" max-width="sm">
            <form @submit.prevent="confirmarEntrega" class="p-6 md:p-8 space-y-6">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.25em] text-emerald-500">Entrega de Paquete</span>
                    <h3 class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-1">
                        Confirmar Retiro
                    </h3>
                    <p class="text-xs text-on-surface-variant mt-1">
                        Paquete de <strong class="text-on-surface dark:text-white">{{ selectedPaquete?.empresa_mensajeria }}</strong> para {{ selectedPaquete?.unidad?.nombre }}.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Nombre de quien retira *</label>
                        <input v-model="entregaForm.entregado_a_nombre" type="text" required placeholder="Ej: Diana Mendoza" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-emerald-500 focus:outline-none" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-primary">PIN Secreto de Retiro (4 dígitos)</label>
                        <input 
                            v-model="entregaForm.pin" 
                            type="password" 
                            maxlength="4" 
                            inputmode="numeric" 
                            placeholder="••••" 
                            class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-primary/30 text-center font-mono text-lg font-black tracking-[0.3em] text-on-surface focus:ring-2 focus:ring-primary focus:outline-none" 
                        />
                        <p v-if="entregaForm.errors.pin" class="text-[11px] font-bold text-rose-500 animate-in shake">
                            {{ entregaForm.errors.pin }}
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button @click="isEntregaModalOpen = false" type="button" class="min-h-[44px] px-4 rounded-xl text-xs font-black uppercase tracking-wider text-on-surface-variant hover:bg-surface-container">
                        Cancelar
                    </button>
                    <button :disabled="entregaForm.processing" type="submit" class="min-h-[44px] px-6 rounded-xl bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-black uppercase tracking-wider shadow-md disabled:opacity-50">
                        Confirmar Entrega
                    </button>
                </div>
            </form>
        </Modal>
    </div>
</template>
