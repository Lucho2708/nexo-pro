<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import { useToast } from '@/Composables/useToast';

defineOptions({ layout: AuthenticatedLayout });

const props = defineProps<{
    documents: Array<{
        id: string;
        type: string;
        title: string;
        version: string;
        is_active: boolean;
        created_at: string;
    }>;
}>();

const toast = useToast();

const toggleStatus = (id: string) => {
    router.patch(route('superadmin.legal.toggle', id), {}, {
        preserveScroll: true,
        onSuccess: () => toast.success('Estado actualizado correctamente'),
    });
};

const getBadgeColor = (type: string) => {
    switch (type) {
        case 'terms': return 'info';
        case 'privacy': return 'success';
        case 'cookies': return 'warning';
        default: return 'info';
    }
};

const getTypeName = (type: string) => {
    switch (type) {
        case 'terms': return 'Términos y Condiciones';
        case 'privacy': return 'Política de Privacidad';
        case 'cookies': return 'Política de Cookies';
        default: return type;
    }
};
</script>

<template>
    <Head title="Gestión Legal" />

    <div class="space-y-6 max-w-7xl mx-auto p-4 md:p-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Gestión Legal y Habeas Data</h1>
                <p class="text-slate-500 dark:text-slate-400">Administra los documentos legales y el cumplimiento normativo de la plataforma.</p>
            </div>
            <Link :href="route('superadmin.legal.create')">
                <Button variant="primary" icon="add">
                    Nuevo Documento
                </Button>
            </Link>
        </div>

        <Card variant="flat" class="overflow-hidden border-slate-200 dark:border-slate-700">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Documento</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Versión</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        <tr v-for="doc in documents" :key="doc.id" class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-slate-900 dark:text-white">{{ doc.title }}</span>
                                    <div class="flex gap-2 mt-1">
                                        <Badge :variant="getBadgeColor(doc.type)" size="sm" class="uppercase text-[10px]">
                                            {{ getTypeName(doc.type) }}
                                        </Badge>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm text-slate-600 dark:text-slate-400">v{{ doc.version }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <Badge :variant="doc.is_active ? 'success' : 'danger'" size="sm">
                                    {{ doc.is_active ? 'Activo' : 'Inactivo' }}
                                </Badge>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500 dark:text-slate-400">
                                {{ new Date(doc.created_at).toLocaleDateString() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <Button 
                                    @click="toggleStatus(doc.id)"
                                    :variant="doc.is_active ? 'secondary' : 'primary'"
                                    size="sm"
                                    class="!px-3"
                                >
                                    {{ doc.is_active ? 'Desactivar' : 'Activar' }}
                                </Button>
                            </td>
                        </tr>
                        <tr v-if="documents.length === 0">
                            <td colspan="5" class="px-6 py-12 text-center text-slate-500 dark:text-slate-400">
                                No hay documentos legales registrados. Comienza creando uno nuevo.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </Card>
    </div>
</template>
