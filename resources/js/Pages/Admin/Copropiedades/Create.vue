<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineOptions({ layout: AuthenticatedLayout });

const form = useForm({
    nit: '',
    nombre: '',
    direccion: '',
    ciudad: '',
    plan: 'pro',
    unidades_totales: 0,
    torres: 0,
});

const submit = () => {
    form.post(route('admin.copropiedades.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Añadir Nuevo Conjunto" />

    <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <h1 class="text-3xl font-black text-primary tracking-tight">AÑADIR NUEVO CONJUNTO</h1>
                <p class="text-on-surface-variant font-medium mt-1">Expande tu gestión registrando una nueva copropiedad en PH360.</p>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Card: Información Básica -->
                <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/10 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-symbols-outlined text-primary p-2 bg-primary-container rounded-xl">corporate_fare</span>
                        <h2 class="text-lg font-bold">Información General</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label for="nombre" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nombre del Conjunto / Edificio</label>
                            <input 
                                id="nombre"
                                v-model="form.nombre"
                                type="text"
                                class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all"
                                placeholder="Ej: Residencial Las Palmeras"
                                required
                            />
                            <div v-if="form.errors.nombre" class="text-error text-[10px] font-bold uppercase mt-1">{{ form.errors.nombre }}</div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="nit" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">NIT</label>
                            <input 
                                id="nit"
                                v-model="form.nit"
                                type="text"
                                class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all"
                                placeholder="900.XXX.XXX-X"
                                required
                            />
                            <div v-if="form.errors.nit" class="text-error text-[10px] font-bold uppercase mt-1">{{ form.errors.nit }}</div>
                        </div>
                    </div>
                </div>

                <!-- Card: Ubicación y Operación -->
                <div class="bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/10 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-symbols-outlined text-secondary p-2 bg-secondary-container rounded-xl">location_on</span>
                        <h2 class="text-lg font-bold">Ubicación y Datos</h2>
                    </div>

                    <div class="space-y-4">
                        <div class="space-y-1.5">
                            <label for="direccion" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Dirección</label>
                            <input 
                                id="direccion"
                                v-model="form.direccion"
                                type="text"
                                class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all"
                                placeholder="Calle 123 #45-67"
                                required
                            />
                            <div v-if="form.errors.direccion" class="text-error text-[10px] font-bold uppercase mt-1">{{ form.errors.direccion }}</div>
                        </div>

                        <div class="space-y-1.5">
                            <label for="ciudad" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Ciudad</label>
                            <input 
                                id="ciudad"
                                v-model="form.ciudad"
                                type="text"
                                class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all"
                                placeholder="Ej: Medellín"
                                required
                            />
                            <div v-if="form.errors.ciudad" class="text-error text-[10px] font-bold uppercase mt-1">{{ form.errors.ciudad }}</div>
                        </div>
                    </div>
                </div>

                <!-- Card: Configuración del Sistema -->
                <div class="md:col-span-2 bg-surface-container-lowest p-8 rounded-3xl border border-outline-variant/10 shadow-sm space-y-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="material-symbols-outlined text-primary p-2 bg-primary-container rounded-xl">settings</span>
                        <h2 class="text-lg font-bold">Configuración de la Copropiedad</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="space-y-1.5">
                            <label for="unidades" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Unidades Totales</label>
                            <input 
                                id="unidades"
                                v-model="form.unidades_totales"
                                type="number"
                                class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all"
                                required
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label for="torres" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Nº de Torres / Bloques</label>
                            <input 
                                id="torres"
                                v-model="form.torres"
                                type="number"
                                class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all"
                                required
                            />
                        </div>

                        <div class="space-y-1.5">
                            <label for="plan" class="text-xs font-bold text-on-surface-variant uppercase tracking-wider">Plan Seleccionado</label>
                            <select 
                                id="plan"
                                v-model="form.plan"
                                class="w-full bg-surface-container-low border-outline-variant/20 rounded-2xl p-4 text-sm focus:ring-2 focus:ring-primary/20 transition-all"
                            >
                                <option value="basic">BASIC (Hasta 50 unidades)</option>
                                <option value="pro">PRO (Hasta 200 unidades)</option>
                                <option value="enterprise">ENTERPRISE (Ilimitado)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div class="md:col-span-2 flex items-center justify-end gap-4 mt-4">
                    <button 
                        type="button"
                        class="px-8 py-4 text-sm font-bold text-on-surface-variant hover:bg-surface-container-high rounded-2xl transition-all"
                    >
                        CANCELAR
                    </button>
                    <button 
                        type="submit"
                        :disabled="form.processing"
                        class="px-10 py-4 bg-primary text-on-primary text-sm font-black rounded-2xl shadow-lg shadow-primary/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2 disabled:opacity-50"
                    >
                        <span class="material-symbols-outlined text-sm">save</span>
                        GUARDAR Y ACTIVAR
                    </button>
                </div>
            </form>
        </div>
</template>
