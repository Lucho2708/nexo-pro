<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import SecurityLayout from '@/Layouts/SecurityLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Modal from '@/Components/UI/Modal.vue';

defineOptions({ layout: SecurityLayout });

const props = defineProps<{
    visitas: any[];
    unidades: any[];
}>();

const isModalOpen = ref(false);

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
        <!-- Header Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Control de Acceso Peatonal & Vehicular</span>
                <h2 class="text-2xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-0.5">
                    Registro de Visitantes
                </h2>
            </div>

            <button
                @click="isModalOpen = true"
                type="button"
                class="min-h-[52px] px-6 py-3.5 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/25 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
            >
                <span class="material-symbols-rounded text-xl">person_add</span>
                Registrar Nuevo Ingreso
            </button>
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
                                    {{ visita.unidad?.nombre || 'Unidad N/A' }}
                                </span>
                            </td>
                            <td class="p-4 font-bold uppercase text-[10px] text-on-surface-variant">{{ visita.tipo_visita }}</td>
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5 md:col-span-2">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Apartamento / Unidad Destino *</label>
                        <select v-model="form.unidad_id" required class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                            <option value="" disabled>Seleccione unidad...</option>
                            <option v-for="u in unidades" :key="u.id" :value="u.id">
                                {{ u.nombre }} {{ u.bloque ? `(${u.bloque})` : '' }}
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
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-outline-variant/10">
                    <button @click="isModalOpen = false" type="button" class="min-h-[48px] px-5 rounded-xl text-xs font-black uppercase tracking-wider text-on-surface-variant hover:bg-surface-container">
                        Cancelar
                    </button>
                    <button :disabled="form.processing" type="submit" class="min-h-[48px] px-6 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-wider shadow-md hover:bg-primary/90 disabled:opacity-50">
                        Confirmar Ingreso
                    </button>
                </div>
            </form>
        </Modal>
    </div>
</template>
