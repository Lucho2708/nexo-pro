<script setup>
import { ref, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const step = ref(1);
const csvData = ref([]);
const fileName = ref('');
const headers = ref([]);
const mapping = ref({
    torre: '',
    apto: '',
    nombre: '',
    coeficiente: '',
    documento: ''
});

const form = useForm({
    property_name: '',
    nit: '',
    direccion: '',
    ciudad: '',
    assembly_title: '',
    voting_method: 'coeficiente',
    units_data: [],
});

// Parsear CSV manualmente
const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (!file) return;
    
    fileName.value = file.name;
    const reader = new FileReader();
    
    reader.onload = (e) => {
        const text = e.target.result;
        const rows = text.split('\n').filter(row => row.trim() !== '');
        if (rows.length > 0) {
            headers.value = rows[0].split(',').map(h => h.trim());
            csvData.value = rows.slice(1).map(row => row.split(',').map(cell => cell.trim()));
            
            // Auto-mapping sugerido
            headers.value.forEach((h, index) => {
                const lowH = h.toLowerCase();
                if (lowH.includes('torre') || lowH.includes('bloque')) mapping.value.torre = index;
                if (lowH.includes('apto') || lowH.includes('unidad')) mapping.value.apto = index;
                if (lowH.includes('nombre') || lowH.includes('propietario')) mapping.value.nombre = index;
                if (lowH.includes('coeficiente')) mapping.value.coeficiente = index;
                if (lowH.includes('documento') || lowH.includes('cedula') || lowH.includes('nit')) mapping.value.documento = index;
            });
            
            step.value = 2;
        }
    };
    reader.readAsText(file);
};

const processedData = computed(() => {
    if (csvData.value.length === 0) return [];
    return csvData.value.map(row => ({
        torre: row[mapping.value.torre] || 'N/A',
        apto: row[mapping.value.apto] || 'N/A',
        nombre: row[mapping.value.nombre] || 'Anónimo',
        coeficiente: parseFloat(row[mapping.value.coeficiente]) || 0,
        documento: row[mapping.value.documento] || '00000000'
    }));
});

const totalCoeficiente = computed(() => {
    return processedData.value.reduce((acc, curr) => acc + curr.coeficiente, 0);
});

const isMappingValid = computed(() => {
    return mapping.value.torre !== '' && mapping.value.apto !== '' && mapping.value.coeficiente !== '';
});

const nextStep = () => {
    if (step.value === 2 && isMappingValid.value) {
        form.units_data = processedData.value;
        step.value = 3;
    } else if (step.value === 1 && form.property_name && form.nit && form.direccion && form.ciudad && form.assembly_title) {
        step.value = 2;
    }
};

const prevStep = () => {
    if (step.value > 1) step.value--;
};

const submit = () => {
    form.post(route('admin.asambleas.standalone.store'));
};

const steps = [
    { id: 1, name: 'Configuración', icon: 'settings' },
    { id: 2, name: 'Carga & Mapeo', icon: 'cloud_upload' },
    { id: 3, name: 'Revisión', icon: 'table_chart' },
    { id: 4, name: 'Finalizar', icon: 'rocket_launch' },
];
</script>

<template>
    <AuthenticatedLayout title="Onboarding Asamblea Standalone">
        <div class="py-12 bg-gray-50/50 min-h-screen">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-10">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase italic">
                        Wizard: <span class="text-indigo-600">Asamblea Standalone</span>
                    </h1>
                    <p class="text-gray-500 text-[10px] font-black uppercase tracking-[0.3em] mt-2 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-600"></span>
                        Configuración de evento y cápsula de quórum
                    </p>
                </div>
                
                <!-- Stepper -->
                <nav aria-label="Progress" class="mb-12">
                    <ol role="list" class="flex items-center justify-between w-full">
                        <li v-for="(s, sIdx) in steps" :key="s.name" class="relative flex-1 flex flex-col items-center">
                            <div class="flex flex-col items-center group">
                                <span :class="[
                                    step >= s.id ? 'bg-indigo-600' : 'bg-gray-200',
                                    'h-12 w-12 rounded-full flex items-center justify-center transition-all duration-500 relative z-10'
                                ]">
                                    <span class="material-symbols-rounded" :class="step >= s.id ? 'text-white' : 'text-gray-500'">{{ s.icon }}</span>
                                </span>
                                <span class="mt-2 text-xs font-bold uppercase tracking-wider" :class="step >= s.id ? 'text-indigo-600' : 'text-gray-500'">
                                    {{ s.name }}
                                </span>
                            </div>
                            <!-- Line connector -->
                            <div v-if="sIdx !== steps.length - 1" 
                                 class="absolute top-6 left-[50%] w-full h-0.5 bg-gray-200 -z-0"
                                 :class="{'bg-indigo-600': step > s.id}"></div>
                        </li>
                    </ol>
                </nav>

                <!-- Card Principal -->
                <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100">
                    
                    <!-- PASO 1: CONFIGURACIÓN -->
                    <div v-if="step === 1" class="p-10 animate-fade-in">
                        <div class="max-w-2xl mx-auto space-y-8">
                            <div class="text-center">
                                <span class="material-symbols-rounded text-indigo-600 text-5xl mb-4">settings</span>
                                <h3 class="text-2xl font-bold text-gray-900">Datos del Evento</h3>
                                <p class="text-gray-500 mt-2">Defina el nombre del conjunto y el tipo de votación para esta asamblea.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nombre del Conjunto / Copropiedad</label>
                                    <input v-model="form.property_name" type="text" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Ej: Condominio Las Palmas">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">NIT / Identificación Tributaria</label>
                                    <input v-model="form.nit" type="text" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="900.000.000-1">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Ciudad</label>
                                    <input v-model="form.ciudad" type="text" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Ej: Bogotá">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Dirección Física</label>
                                    <input v-model="form.direccion" type="text" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Calle 123 # 45-67">
                                </div>
                                <div class="col-span-2">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Título de la Asamblea</label>
                                    <input v-model="form.assembly_title" type="text" class="w-full px-5 py-4 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all" placeholder="Ej: Asamblea Ordinaria 2026">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Método de Votación</label>
                                    <div class="grid grid-cols-2 gap-4">
                                        <button @click="form.voting_method = 'coeficiente'" :class="form.voting_method === 'coeficiente' ? 'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200' : 'bg-gray-50 border-transparent'" class="p-6 rounded-2xl border text-left transition-all">
                                            <span class="block font-bold text-gray-900">Por Coeficiente</span>
                                            <span class="text-xs text-gray-500 mt-1">El voto pesa según el área de la unidad.</span>
                                        </button>
                                        <button @click="form.voting_method = 'unidad'" :class="form.voting_method === 'unidad' ? 'ring-2 ring-indigo-500 bg-indigo-50 border-indigo-200' : 'bg-gray-50 border-transparent'" class="p-6 rounded-2xl border text-left transition-all">
                                            <span class="block font-bold text-gray-900">Por Unidad</span>
                                            <span class="text-xs text-gray-500 mt-1">Cada unidad equivale a un voto igual.</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 2: CARGA Y MAPEO -->
                    <div v-if="step === 2" class="p-10 animate-fade-in">
                        <div class="space-y-8">
                            <div class="text-center">
                                <span class="material-symbols-rounded text-indigo-600 text-5xl mb-4">cloud_upload</span>
                                <h3 class="text-2xl font-bold text-gray-900">Importación de Unidades</h3>
                                <p class="text-gray-500 mt-2">Cargue su archivo CSV y mapee las columnas correspondientes.</p>
                            </div>

                            <div v-if="csvData.length === 0" class="max-w-xl mx-auto">
                                <label class="flex flex-col items-center justify-center w-full h-64 border-2 border-gray-300 border-dashed rounded-3xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <span class="material-symbols-rounded text-gray-400 text-5xl mb-4">cloud_upload</span>
                                        <p class="mb-2 text-sm text-gray-500 font-bold">Haga clic para cargar o arrastre el CSV</p>
                                        <p class="text-xs text-gray-400">Solo archivos .csv (UTF-8)</p>
                                    </div>
                                    <input type="file" class="hidden" accept=".csv" @change="handleFileUpload" />
                                </label>
                            </div>

                            <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-10">
                                <!-- Panel de Mapeo -->
                                <div class="col-span-1 space-y-6">
                                    <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                        <span class="material-symbols-rounded text-indigo-500">sync_alt</span> Mapeo de Columnas
                                    </h4>
                                    <div class="space-y-4">
                                        <div v-for="(label, key) in {torre: 'Torre/Bloque', apto: 'Apartamento/Unidad', nombre: 'Nombre Propietario', coeficiente: 'Coeficiente (%)', documento: 'Documento (ID)'}" :key="key">
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">{{ label }}</label>
                                            <select v-model="mapping[key]" class="w-full px-4 py-3 rounded-xl bg-gray-50 border-gray-200 text-sm focus:ring-indigo-500">
                                                <option value="">Seleccionar columna...</option>
                                                <option v-for="(h, idx) in headers" :key="idx" :value="idx">{{ h }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Vista Previa de Datos -->
                                <div class="col-span-2">
                                    <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <span class="material-symbols-rounded text-indigo-500">table_chart</span> Vista Previa (Primeras 5 filas)
                                    </h4>
                                    <div class="overflow-hidden border border-gray-200 rounded-2xl">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Torre</th>
                                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Apto</th>
                                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nombre</th>
                                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-500 uppercase">Coef %</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <tr v-for="(row, idx) in processedData.slice(0, 5)" :key="idx">
                                                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">{{ row.torre }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">{{ row.apto }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600">{{ row.nombre }}</td>
                                                    <td class="px-4 py-3 text-sm font-bold text-indigo-600">{{ row.coeficiente }}%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-4 p-4 bg-indigo-50 rounded-2xl flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-rounded text-indigo-600">check_circle</span>
                                            <span class="text-sm font-bold text-indigo-900">{{ csvData.length }} unidades detectadas</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-xs text-indigo-500 block">Suma de Coeficientes</span>
                                            <span class="text-lg font-black text-indigo-600">{{ totalCoeficiente.toFixed(4) }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- PASO 3: REVISIÓN FINAL -->
                    <div v-if="step === 3" class="p-10 animate-fade-in text-center">
                        <div class="max-w-md mx-auto space-y-6">
                            <div class="inline-flex p-4 rounded-3xl bg-emerald-50 border border-emerald-100">
                                <span class="material-symbols-rounded text-emerald-600 text-5xl">rocket_launch</span>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900">¡Todo Listo para el Lanzamiento!</h3>
                            <p class="text-gray-500">Se creará la cápsula aislada para <b>{{ form.property_name }}</b>. Una vez guardado, se generará el link de acceso para los propietarios.</p>
                            
                            <div class="bg-gray-50 p-6 rounded-3xl space-y-3 text-left border border-gray-100">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Evento:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ form.assembly_title }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Unidades:</span>
                                    <span class="text-sm font-bold text-gray-900">{{ form.units_data.length }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-500">Sistema de Voto:</span>
                                    <span class="text-sm font-bold text-gray-900 capitalize">{{ form.voting_method }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer de Navegación -->
                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-between items-center">
                        <button v-if="step > 1" @click="prevStep" class="flex items-center gap-2 px-6 py-3 text-gray-600 font-bold hover:text-indigo-600 transition-colors">
                            <span class="material-symbols-rounded">chevron_left</span> Atrás
                        </button>
                        <div v-else></div>

                        <button v-if="step < 3" @click="nextStep" :disabled="step === 2 && !isMappingValid" class="flex items-center gap-2 px-8 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold shadow-lg shadow-indigo-500/20 disabled:opacity-50 transition-all">
                            Siguiente <span class="material-symbols-rounded">chevron_right</span>
                        </button>

                        <button v-if="step === 3" @click="submit" :disabled="form.processing" class="flex items-center gap-2 px-10 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black shadow-lg shadow-emerald-500/20 transition-all">
                            CREAR CÁPSULA AHORA <span class="material-symbols-rounded">rocket_launch</span>
                        </button>
                    </div>
                </div>

                <!-- Alertas de Validación -->
                <div v-if="step === 2 && Math.abs(totalCoeficiente - 100) > 0.01 && form.voting_method === 'coeficiente'" class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-start gap-3">
                    <span class="material-symbols-rounded text-amber-600">warning</span>
                    <div>
                        <p class="text-sm font-bold text-amber-900">Atención con los Coeficientes</p>
                        <p class="text-sm text-amber-700">La suma actual es de {{ totalCoeficiente.toFixed(2) }}%. Usualmente debería ser 100%. Por favor verifique sus datos.</p>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.animate-fade-in {
    animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
