<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import SecurityLayout from '@/Layouts/SecurityLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Modal from '@/Components/UI/Modal.vue';

defineOptions({ layout: SecurityLayout });

const props = defineProps<{
    minutas: any[];
}>();

const isModalOpen = ref(false);

const form = useForm({
    tipo_evento: 'novedad',
    prioridad: 'informativo',
    descripcion: '',
});

const submitMinuta = () => {
    form.post(route('porteria.minuta.store'), {
        onSuccess: () => {
            form.reset();
            isModalOpen.value = false;
        },
    });
};
</script>

<template>
    <Head title="Minuta de Turno • Portería" />

    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Libro de Novedades Digital</span>
                <h2 class="text-2xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-0.5">
                    Minuta de Seguridad
                </h2>
            </div>

            <button
                @click="isModalOpen = true"
                type="button"
                class="min-h-[52px] px-6 py-3.5 rounded-2xl bg-primary text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-primary/25 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2"
            >
                <span class="material-symbols-rounded text-xl">edit_note</span>
                Registrar Novedad de Turno
            </button>
        </div>

        <!-- Timeline of Events -->
        <Card class="p-6 md:p-8 space-y-6 border border-outline-variant/15">
            <h3 class="text-sm font-black uppercase tracking-tight text-on-surface dark:text-white flex items-center gap-2">
                <span class="material-symbols-rounded text-primary">history_edu</span>
                Bitácora Cronológica de Turno
            </h3>

            <div class="space-y-4">
                <div 
                    v-for="minuta in minutas" 
                    :key="minuta.id" 
                    class="p-5 rounded-2xl border transition-all"
                    :class="[
                        minuta.prioridad === 'urgente' 
                            ? 'bg-rose-500/5 border-rose-500/30' 
                            : (minuta.prioridad === 'advertencia' ? 'bg-amber-500/5 border-amber-500/30' : 'bg-surface-container-low border-outline-variant/15')
                    ]"
                >
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-outline-variant/10 pb-3">
                        <div class="flex items-center gap-2.5">
                            <span 
                                class="px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider"
                                :class="[
                                    minuta.prioridad === 'urgente' ? 'bg-rose-500 text-white' : (minuta.prioridad === 'advertencia' ? 'bg-amber-500 text-slate-950' : 'bg-primary/10 text-primary')
                                ]"
                            >
                                {{ minuta.prioridad }}
                            </span>
                            <span class="text-xs font-black uppercase tracking-wider text-on-surface dark:text-white">
                                {{ minuta.tipo_evento.replace('_', ' ') }}
                            </span>
                        </div>
                        <div class="text-[11px] font-bold text-on-surface-variant flex items-center gap-2">
                            <span>Guarda: {{ minuta.guarda?.name || 'Guarda' }}</span>
                            <span>•</span>
                            <span>{{ new Date(minuta.created_at).toLocaleString('es-CO', { dateStyle: 'short', timeStyle: 'short' }) }}</span>
                        </div>
                    </div>
                    <p class="text-xs text-on-surface dark:text-white/90 font-medium mt-3 whitespace-pre-wrap leading-relaxed">
                        {{ minuta.descripcion }}
                    </p>
                </div>

                <div v-if="minutas.length === 0" class="p-12 text-center text-on-surface-variant font-bold uppercase text-xs">
                    No se han registrado novedades en la minuta durante el turno.
                </div>
            </div>
        </Card>

        <!-- Modal Registrar Minuta -->
        <Modal :show="isModalOpen" @close="isModalOpen = false" max-width="md">
            <form @submit.prevent="submitMinuta" class="p-6 md:p-8 space-y-6">
                <div>
                    <span class="text-[10px] font-black uppercase tracking-[0.25em] text-primary">Novedad de Seguridad</span>
                    <h3 class="text-xl font-black uppercase tracking-tight text-on-surface dark:text-white mt-1">
                        Anotación en Minuta
                    </h3>
                </div>

                <div class="space-y-4">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Tipo de Evento *</label>
                        <select v-model="form.tipo_evento" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                            <option value="cambio_turno">Cambio de Turno / Relevo</option>
                            <option value="novedad">Novedad General</option>
                            <option value="ronda">Ronda Perimetral</option>
                            <option value="incidente">Incidente de Seguridad</option>
                            <option value="aviso">Aviso Administrativo</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Prioridad</label>
                        <select v-model="form.prioridad" class="w-full min-h-[48px] px-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none">
                            <option value="informativo">Informativo</option>
                            <option value="advertencia">Advertencia / Atención</option>
                            <option value="urgente">Urgente / Crítico</option>
                        </select>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black uppercase tracking-wider text-on-surface-variant">Descripción Detallada *</label>
                        <textarea v-model="form.descripcion" required rows="4" placeholder="Describa la novedad, estado de equipos de garita, llaves entregadas, etc." class="w-full p-4 rounded-xl bg-surface-container-low border border-outline-variant/20 text-xs font-bold text-on-surface focus:ring-2 focus:ring-primary focus:outline-none"></textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button @click="isModalOpen = false" type="button" class="min-h-[44px] px-4 rounded-xl text-xs font-black uppercase tracking-wider text-on-surface-variant hover:bg-surface-container">
                        Cancelar
                    </button>
                    <button :disabled="form.processing" type="submit" class="min-h-[44px] px-6 rounded-xl bg-primary text-white text-xs font-black uppercase tracking-wider shadow-md disabled:opacity-50">
                        Guardar Novedad
                    </button>
                </div>
            </form>
        </Modal>
    </div>
</template>
