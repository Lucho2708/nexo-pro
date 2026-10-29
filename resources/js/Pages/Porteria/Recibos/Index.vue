<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import SecurityLayout from '@/Layouts/SecurityLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Modal from '@/Components/UI/Modal.vue';

defineOptions({ layout: SecurityLayout });

const props = defineProps<{
    recibos: any[];
    unidades: any[];
}>();

const isModalOpen = ref(false);

const form = useForm({
    unidad_id: '',
    tipo_servicio: 'acueducto',
    empresa_emisora: '',
    periodo_facturado: '',
});

const submitRecibo = () => {
    form.post(route('porteria.recibos.store'), {
        onSuccess: () => {
            form.reset();
            isModalOpen.value = false;
        },
    });
};
</script>

<template>
    <Head title="Recibos Públicos • Portería" />

    <div class="space-y-6">
        <!-- Banner Informativo: Copropiedad Sin Propiedades Registradas -->
        <div v-if="!unidades || unidades.length === 0" class="p-4 rounded-2xl bg-indigo-500/10 border border-indigo-500/25 flex items-start gap-3">
            <span class="material-symbols-rounded text-indigo-500 text-xl shrink-0 notranslate" translate="no">domain_disabled</span>
            <div class="space-y-1">
                <h4 class="text-xs font-black uppercase tracking-wider text-indigo-500">Aún no se cuenta con registro de propiedades</h4>
                <p class="text-xs text-on-surface-variant font-medium leading-relaxed">
                    Esta copropiedad no tiene apartamentos, torres o unidades creadas en el sistema. La radicación de recibos en casillero requiere asociar una propiedad válida. Contacte a la administración para poblar el censo de inmuebles.
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-indigo-500">Gestión de Casillero y Servicios</span>
                <h2 class="text-2xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-0.5">
                    Llegada de Recibos Públicos
                </h2>
            </div>

            <button
                @click="isModalOpen = true"
                type="button"
                class="min-h-[52px] px-6 py-3.5 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-indigo-600/25 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
            >
                <span class="material-symbols-rounded text-xl">receipt_long</span>
                Radicar Recibo en Casillero
            </button>
        </div>

        <Card class="overflow-hidden border border-outline-variant/15">
            <div class="p-6 border-b border-outline-variant/10 flex items-center justify-between">
                <h3 class="text-sm font-black uppercase tracking-tight text-on-surface dark:text-white flex items-center gap-2">
                    <span class="material-symbols-rounded text-indigo-500">markunread_mailbox</span>
                    Facturas Pendientes en Casillero
                </h3>
                <span class="text-xs font-bold text-on-surface-variant">{{ recibos.length }} recibos</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-surface-container-high/50 dark:bg-white/5 border-b border-outline-variant/10 text-[10px] font-black uppercase tracking-widest text-on-surface-variant">
                        <tr>
                            <th class="p-4">Apartamento</th>
                            <th class="p-4">Servicio Público</th>
                            <th class="p-4">Empresa Emisora</th>
                            <th class="p-4">Periodo Facturado</th>
                            <th class="p-4">Fecha Radicado</th>
                            <th class="p-4">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant/10">
                        <tr v-for="recibo in recibos" :key="recibo.id" class="hover:bg-surface-container/30 transition-colors">
                            <td class="p-4 font-black text-on-surface dark:text-white uppercase">
                                {{ recibo.unidad?.nombre || 'Unidad N/A' }} {{ (recibo.unidad?.torre || recibo.unidad?.bloque) ? `(${recibo.unidad?.torre || recibo.unidad?.bloque})` : '' }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-lg bg-indigo-500/10 text-indigo-500 font-black uppercase text-[10px]">
                                    {{ recibo.tipo_servicio }}
                                </span>
                            </td>
                            <td class="p-4 text-on-surface-variant">{{ recibo.empresa_emisora || 'Oficial' }}</td>
                            <td class="p-4 font-bold">{{ recibo.periodo_facturado || 'Mes Actual' }}</td>
                            <td class="p-4 text-on-surface-variant text-[11px]">
                                {{ new Date(recibo.created_at).toLocaleDateString('es-CO') }}
                            </td>
                            <td class="p-4">
                                <span class="px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-amber-500/10 text-amber-500 border border-amber-500/20">
                                    {{ recibo.estado }}
                                </span>
                            </td>
                        </tr>
                        <tr v-if="recibos.length === 0">
                            <td colspan="6" class="p-12 text-center text-on-surface-variant font-bold uppercase text-xs">
                                No hay recibos pendientes en casillero.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>

        <!-- Modal Radicar Recibo -->
        <Modal :show="isModalOpen" @close="isModalOpen = false" max-width="md">
            <form @submit.prevent="submitRecibo" class="p-6 md:p-8 space-y-6">
                <div class="flex items-center justify-between border-b border-outline-variant/10 pb-4">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-[0.25em] text-indigo-500">Radicación en Casillero</span>
                        <h3 class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white">
                            Llegada de Recibo Público
                        </h3>
                    </div>
                    <button @click="isModalOpen = false" type="button" class="p-2 text-on-surface-variant hover:text-on-surface">
                        <span class="material-symbols-rounded">close</span>
                    </button>
                </div>

                <div class="space-y-4">
                    <div v-if="!unidades || unidades.length === 0" class="p-3.5 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-400 text-xs font-bold flex items-center gap-2">
                        <span class="material-symbols-rounded text-base notranslate" translate="no">warning</span>
                        <span>Aún no hay unidades registradas en el conjunto. Radicación deshabilitada.</span>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Apartamento / Unidad *</label>
                        <select v-model="form.unidad_id" required :disabled="!unidades || unidades.length === 0" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-indigo-500 focus:outline-none disabled:opacity-50">
                            <option value="" disabled>{{ (!unidades || unidades.length === 0) ? 'No hay unidades registradas en el conjunto' : 'Seleccione unidad...' }}</option>
                            <option v-for="u in unidades" :key="u.id" :value="u.id">
                                {{ u.nombre }} {{ (u.torre || u.bloque) ? `(${u.torre || u.bloque})` : '' }}
                            </option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Tipo de Servicio *</label>
                        <select v-model="form.tipo_servicio" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                            <option value="acueducto">Acueducto & Alcantarillado</option>
                            <option value="energia">Energía Eléctrica</option>
                            <option value="gas">Gas Natural</option>
                            <option value="telecomunicaciones">Internet / TV / Telefonía</option>
                            <option value="otros">Impuesto Predial / Otros</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Empresa Emisora</label>
                        <input v-model="form.empresa_emisora" type="text" placeholder="Ej: Enel Codensa / Acueducto" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Periodo Facturado</label>
                        <input v-model="form.periodo_facturado" type="text" placeholder="Ej: Octubre 2026" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-indigo-500 focus:outline-none" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/10">
                    <button @click="isModalOpen = false" type="button" class="min-h-[48px] px-5 rounded-xl text-xs font-black uppercase tracking-wider text-on-surface-variant hover:bg-surface-container">
                        Cancelar
                    </button>
                    <button :disabled="form.processing || !unidades || unidades.length === 0" type="submit" class="min-h-[48px] px-6 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-wider shadow-md disabled:opacity-50">
                        Notificar y Guardar
                    </button>
                </div>
            </form>
        </Modal>
    </div>
</template>
