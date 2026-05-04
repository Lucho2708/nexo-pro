<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Badge from '@/Components/UI/Badge.vue';
import Table from '@/Components/UI/Table.vue';
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

const tableColumns = [
    { key: 'document', label: 'DOCUMENTO', sortable: true, sortField: 'title' },
    { key: 'version', label: 'VERSIÓN', sortable: true, sortField: 'version' },
    { key: 'status', label: 'ESTADO', sortable: true, sortField: 'is_active' },
    { key: 'date', label: 'FECHA', sortable: true, sortField: 'created_at' },
    { key: 'actions', label: 'ACCIONES', sortable: false },
];

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

    <div class="space-y-6 max-w-7xl mx-auto p-4 md:p-8 animate-in fade-in duration-700">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-on-surface dark:text-white uppercase tracking-tighter italic leading-none">Gestión <span class="text-primary italic">Legal</span></h1>
                <p class="text-[10px] font-bold text-on-surface-variant/40 dark:text-white/20 uppercase tracking-[0.3em] mt-3 italic">Administra los documentos legales y el cumplimiento normativo</p>
            </div>
            <Link :href="route('superadmin.legal.create')">
                <Button variant="primary" icon="add" class="!rounded-2xl shadow-xl shadow-primary/20">
                    Nuevo Documento
                </Button>
            </Link>
        </div>

        <Card content-class="!p-0 !rounded-[3.5rem] overflow-hidden" class="border border-outline-variant/10 dark:border-white/5 shadow-3xl dark:bg-[#0b0e14]">
            <Table :columns="tableColumns" :data="documents">
                <template #cell-document="{ row }">
                    <div class="flex flex-col py-2">
                        <span class="font-black text-xs text-on-surface dark:text-white uppercase tracking-tighter italic">{{ row.title }}</span>
                        <div class="flex gap-2 mt-1">
                            <Badge :variant="getBadgeColor(row.type)" class="!px-3 !py-1 uppercase !text-[8px] font-black tracking-widest border-2">
                                {{ getTypeName(row.type) }}
                            </Badge>
                        </div>
                    </div>
                </template>
                
                <template #cell-version="{ row }">
                    <span class="font-mono text-sm font-black text-primary italic">v{{ row.version }}</span>
                </template>
                
                <template #cell-status="{ row }">
                    <Badge :variant="row.is_active ? 'success' : 'error'" class="!px-4 !py-1 uppercase !text-[9px] font-black tracking-widest">
                        {{ row.is_active ? 'Activo' : 'Inactivo' }}
                    </Badge>
                </template>
                
                <template #cell-date="{ row }">
                    <span class="text-[10px] font-bold text-on-surface-variant dark:text-white/40 uppercase tracking-widest italic">
                        {{ new Date(row.created_at).toLocaleDateString() }}
                    </span>
                </template>
                
                <template #cell-actions="{ row }">
                    <div class="flex justify-end pr-4">
                        <Button 
                            @click="toggleStatus(row.id)"
                            :variant="row.is_active ? 'error' : 'success'"
                            class="!rounded-xl !h-10 uppercase tracking-widest !text-[9px] font-black"
                            :icon="row.is_active ? 'block' : 'check_circle'"
                        >
                            {{ row.is_active ? 'Desactivar' : 'Activar' }}
                        </Button>
                    </div>
                </template>
            </Table>
        </Card>
    </div>
</template>
