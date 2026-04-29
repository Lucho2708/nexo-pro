<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Modal from '@/Components/UI/Modal.vue';
import Badge from '@/Components/UI/Badge.vue';
import Tooltip from '@/Components/UI/Tooltip.vue';

const props = defineProps<{
    asambleas: any[];
}>();

const showCreateModal = ref(false);

const form = useForm({
    titulo: '',
    fecha: '',
    hora_inicio: '',
    quorum_esperado: 51,
});

const submit = () => {
    form.post(route('admin.asambleas.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            form.reset();
        }
    });
};

const getStatusTheme = (status: string) => {
    switch (status) {
        case 'in_progress': return { label: 'EN VIVO', variant: 'success', icon: 'sensors' };
        case 'finished': return { label: 'FINALIZADA', variant: 'neutral', icon: 'task_alt' };
        default: return { label: 'PROGRAMADA', variant: 'info', icon: 'calendar_today' };
    }
};

const formatDate = (dateStr: string) => {
    return new Date(dateStr).toLocaleDateString('es-ES', { 
        weekday: 'long', 
        day: 'numeric', 
        month: 'long' 
    }).toUpperCase();
};
</script>

<template>
    <Head title="Gestión de Asambleas" />

    <AuthenticatedLayout>
        <div class="space-y-10">
            <!-- Header Premium -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 bg-surface-container-low p-8 rounded-[2.5rem] border border-outline-variant/5 shadow-sm">
                <div>
                    <h1 class="text-4xl font-black text-on-surface tracking-tighter flex items-center gap-4">
                        ASAMBLEAS <span class="text-primary italic">VIRTUALES</span>
                    </h1>
                    <p class="text-on-surface-variant/60 text-[10px] font-black uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-primary"></span>
                        Gestión de quórum y votaciones en tiempo real
                    </p>
                </div>
                <Button @click="showCreateModal = true" variant="primary" icon="add_circle" class="!rounded-2xl !py-4 shadow-xl shadow-primary/20 hover:scale-105 transition-all">
                    NUEVA ASAMBLEA
                </Button>
            </div>

            <!-- Empty State -->
            <div v-if="asambleas.length === 0" class="flex flex-col items-center justify-center py-32 bg-surface-container-lowest rounded-[4rem] border-2 border-dashed border-outline-variant/20">
                <div class="w-24 h-24 bg-primary/5 rounded-[2rem] flex items-center justify-center mb-8 rotate-3 group-hover:rotate-0 transition-transform">
                    <span class="material-symbols-rounded text-5xl text-primary/30">video_chat</span>
                </div>
                <h3 class="text-2xl font-black text-on-surface uppercase tracking-tight">Sin eventos programados</h3>
                <p class="text-on-surface-variant/50 max-w-sm text-center mt-3 text-sm font-medium">Digitaliza las asambleas de tu copropiedad. Votaciones seguras, quórum automático y reportes inmediatos.</p>
                <Button @click="showCreateModal = true" variant="outline" class="mt-10 !rounded-xl">EMPEZAR AHORA</Button>
            </div>

            <!-- Grid de Asambleas -->
            <div v-else class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <Card v-for="asamblea in asambleas" :key="asamblea.id" class="!p-0 !rounded-[3rem] overflow-hidden border border-outline-variant/10 hover:border-primary/30 transition-all duration-500 shadow-lg hover:shadow-2xl flex flex-col group">
                    <div class="p-10 flex-1">
                        <div class="flex justify-between items-start mb-8">
                            <Badge :variant="getStatusTheme(asamblea.status).variant" class="!px-4 !py-2 !rounded-xl text-[9px] font-black tracking-widest italic">
                                <span class="material-symbols-rounded text-xs mr-2">{{ getStatusTheme(asamblea.status).icon }}</span>
                                {{ getStatusTheme(asamblea.status).label }}
                            </Badge>
                            
                            <div class="flex items-center gap-2">
                                <span class="text-[9px] font-black text-on-surface-variant/30 uppercase">ID EVENTO</span>
                                <span class="bg-surface-container-high px-3 py-1 rounded-lg text-[10px] font-bold text-on-surface">#{{ asamblea.id }}</span>
                            </div>
                        </div>

                        <h3 class="text-2xl font-black text-on-surface group-hover:text-primary transition-colors leading-tight mb-8 uppercase tracking-tighter">
                            {{ asamblea.titulo }}
                        </h3>

                        <div class="grid grid-cols-2 gap-6">
                            <div class="bg-surface-container-lowest p-5 rounded-[2rem] border border-outline-variant/5">
                                <span class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest block mb-2">CALENDARIO</span>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center">
                                        <span class="material-symbols-rounded text-primary text-xl">event</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black text-on-surface leading-none">{{ formatDate(asamblea.fecha) }}</span>
                                        <span class="text-[9px] font-bold text-on-surface-variant italic mt-1">{{ new Date(asamblea.fecha).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-surface-container-lowest p-5 rounded-[2rem] border border-outline-variant/5">
                                <span class="text-[8px] font-black text-on-surface-variant/40 uppercase tracking-widest block mb-2">ESTADÍSTICAS</span>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-secondary/5 flex items-center justify-center">
                                        <span class="material-symbols-rounded text-secondary text-xl">ballot</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-black text-on-surface leading-none">{{ asamblea.preguntas_count }} PREGUNTAS</span>
                                        <span class="text-[9px] font-bold text-on-surface-variant italic mt-1">Quórum req: {{ asamblea.settings?.quorum_esperado }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer de Acciones -->
                    <div class="bg-surface-container-high/30 backdrop-blur-xl border-t border-outline-variant/5 p-8 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                             <div class="flex -space-x-3">
                                <div v-for="i in 3" :key="i" class="w-9 h-9 rounded-2xl border-4 border-surface-container bg-slate-200 flex items-center justify-center text-[10px] font-black text-on-surface/50 overflow-hidden ring-1 ring-black/5">
                                    <img :src="`https://api.dicebear.com/7.x/avataaars/svg?seed=${i}`" class="w-full h-full object-cover" />
                                </div>
                                <div class="w-9 h-9 rounded-2xl border-4 border-surface-container bg-primary text-white flex items-center justify-center text-[9px] font-black shadow-lg ring-1 ring-primary/20">
                                    +0
                                </div>
                            </div>
                            <span class="text-[9px] font-black text-on-surface-variant/50 uppercase tracking-widest">CONEXIONES ACTIVAS</span>
                        </div>

                        <div class="flex gap-3">
                            <!-- Toggle Status -->
                            <Tooltip :text="asamblea.status === 'in_progress' ? 'Finalizar Asamblea' : 'Activar Asamblea'">
                                <Link :href="route('admin.asambleas.toggle', asamblea.id)" method="patch" as="button" class="w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg active:scale-95"
                                    :class="asamblea.status === 'in_progress' ? 'bg-error/10 text-error hover:bg-error hover:text-white shadow-error/10' : 'bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white shadow-emerald-500/10'">
                                    <span class="material-symbols-rounded text-xl">{{ asamblea.status === 'in_progress' ? 'stop_circle' : 'play_circle' }}</span>
                                </Link>
                            </Tooltip>

                            <!-- Ingresar como Moderador -->
                            <Tooltip text="Entrar como Moderador">
                                <Link v-if="asamblea.status === 'in_progress'" :href="route('asambleas.show', asamblea.id)" 
                                    class="bg-primary text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xl shadow-primary/30">
                                    <span class="material-symbols-rounded text-xl">login</span>
                                </Link>
                            </Tooltip>

                            <!-- Reporte / Auditoría -->
                            <Tooltip v-if="asamblea.status === 'finished'" text="Descargar Auditoría">
                                <a :href="route('asambleas.report', asamblea.id)" class="bg-secondary text-white w-12 h-12 rounded-2xl flex items-center justify-center hover:scale-110 active:scale-95 transition-all shadow-xl shadow-secondary/30">
                                    <span class="material-symbols-rounded text-xl">picture_as_pdf</span>
                                </a>
                            </Tooltip>

                            <!-- Eliminar -->
                            <Tooltip v-if="asamblea.status !== 'in_progress'" text="Eliminar Evento">
                                <Link :href="route('admin.asambleas.destroy', asamblea.id)" method="delete" as="button" class="bg-surface-container-highest text-on-surface-variant/40 hover:text-error w-12 h-12 rounded-2xl flex items-center justify-center transition-all">
                                    <span class="material-symbols-rounded text-xl">delete</span>
                                </Link>
                            </Tooltip>
                        </div>
                    </div>
                </Card>
            </div>
        </div>

        <!-- Modal Crear Premium -->
        <Modal :show="showCreateModal" @close="showCreateModal = false" max-width="lg" title="Programar Asamblea">
            <div class="relative">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl"></div>
                
                <form @submit.prevent="submit" class="space-y-8 mt-4">
                    <div class="space-y-3">
                        <label class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1">NOMBRE DEL EVENTO</label>
                        <input v-model="form.titulo" type="text" class="w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface placeholder:text-on-surface-variant/20 shadow-inner" placeholder="Ej: ASAMBLEA ORDINARIA 2026" required />
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <label class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1">FECHA PROGRAMADA</label>
                            <input v-model="form.fecha" type="date" class="w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface shadow-inner" required />
                        </div>
                        <div class="space-y-3">
                            <label class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1">HORA DE INICIO</label>
                            <input v-model="form.hora_inicio" type="time" class="w-full bg-surface-container-high border-none rounded-2xl px-6 py-5 focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface shadow-inner" required />
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-end">
                            <label class="text-[9px] font-black uppercase tracking-[0.2em] text-on-surface-variant/50 ml-1">QUÓRUM MÍNIMO EXIGIDO</label>
                            <span class="text-2xl font-black text-primary">{{ form.quorum_esperado }}%</span>
                        </div>
                        <div class="relative py-2">
                             <input v-model="form.quorum_esperado" type="range" min="0" max="100" class="w-full h-2 bg-surface-container-high rounded-full appearance-none cursor-pointer accent-primary" />
                        </div>
                    </div>

                    <div class="pt-8 flex gap-4">
                        <Button @click="showCreateModal = false" type="button" variant="outline" class="flex-1 !rounded-[1.5rem] !py-5 uppercase text-[10px] tracking-widest font-black">CANCELAR</Button>
                        <Button type="submit" variant="primary" class="flex-1 !rounded-[1.5rem] !py-5 uppercase text-[10px] tracking-widest font-black shadow-xl shadow-primary/20" :loading="form.processing">PROGRAMAR EVENTO</Button>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<style scoped>
input[type="range"]::-webkit-slider-thumb {
    appearance: none;
    width: 24px;
    height: 24px;
    background: white;
    border: 4px solid var(--md-sys-color-primary);
    border-radius: 50%;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    transition: all 0.2s;
}

input[type="range"]::-webkit-slider-thumb:hover {
    transform: scale(1.1);
}
</style>
