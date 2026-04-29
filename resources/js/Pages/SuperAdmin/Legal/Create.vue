<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/UI/Card.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Select from '@/Components/UI/Select.vue';
import Checkbox from '@/Components/UI/Checkbox.vue';

defineOptions({ layout: AuthenticatedLayout });

const form = useForm({
    type: 'terms',
    title: '',
    body: '',
    version: '1.0.0',
    activate: true,
});

const submit = () => {
    form.post(route('superadmin.legal.store'));
};

const types = [
    { value: 'terms', label: 'Términos y Condiciones' },
    { value: 'privacy', label: 'Política de Privacidad' },
    { value: 'cookies', label: 'Política de Cookies' },
];
</script>

<template>
    <Head title="Nuevo Documento Legal" />

    <div class="max-w-5xl mx-auto p-4 md:p-8 space-y-6">
        <div class="flex items-center gap-4">
            <Link :href="route('superadmin.legal.index')">
                <Button variant="secondary" icon="arrow_back" size="sm" class="!rounded-full" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold text-slate-900 dark:text-white font-display">Nuevo Documento Legal</h1>
                <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Configuración de Cumplimiento Normativo</p>
            </div>
        </div>

        <Card variant="flat" class="p-6 md:p-10 border-slate-200 dark:border-slate-700">
            <form @submit.prevent="submit" class="space-y-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <Select 
                        v-model="form.type"
                        :options="types"
                        label="Tipo de Documento"
                        :error="form.errors.type"
                        icon="gavel"
                    />
                    <Input 
                        v-model="form.version"
                        label="Versión del Documento"
                        placeholder="Ej: 1.0.0"
                        :error="form.errors.version"
                        icon="schema"
                    />
                </div>

                <Input 
                    v-model="form.title"
                    label="Título Público"
                    placeholder="Ej: Términos y Condiciones Generales 2026"
                    :error="form.errors.title"
                    icon="title"
                />

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest pl-1">
                        Cuerpo del Documento (Contenido Legal)
                    </label>
                    <textarea 
                        v-model="form.body"
                        class="w-full min-h-[400px] rounded-2xl border-2 border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900 focus:ring-4 focus:ring-brand-500/10 focus:border-brand-500 transition-all p-6 text-slate-700 dark:text-slate-200 font-sans leading-relaxed"
                        placeholder="Escribe aquí el texto legal completo..."
                    ></textarea>
                    <p v-if="form.errors.body" class="text-[10px] font-bold text-danger uppercase tracking-widest pl-1">{{ form.errors.body }}</p>
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pt-6 border-t border-slate-100 dark:border-slate-800">
                    <div class="flex items-center gap-2">
                        <Checkbox v-model="form.activate" id="activate-checkbox" />
                        <label for="activate-checkbox" class="text-sm font-medium text-slate-600 dark:text-slate-400 cursor-pointer">
                            Activar inmediatamente (esta versión reemplazará a la anterior de forma global)
                        </label>
                    </div>
                    
                    <Button 
                        type="submit" 
                        variant="primary" 
                        :loading="form.processing"
                        class="w-full md:w-auto"
                        icon="save"
                    >
                        Publicar Documento
                    </Button>
                </div>
            </form>
        </Card>
    </div>
</template>
