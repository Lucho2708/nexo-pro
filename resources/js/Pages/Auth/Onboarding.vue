<script setup lang="ts">
import { ref, computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Checkbox from '@/Components/UI/Checkbox.vue';
import Alert from '@/Components/UI/Alert.vue';
import Card from '@/Components/UI/Card.vue';
import Logo from '@/Components/UI/Logo.vue';
import Modal from '@/Components/UI/Modal.vue';
import LegalContent from '@/Components/Legal/LegalContent.vue';

const showLegalModal = ref(false);
const legalModalType = ref<'privacy' | 'terms'>('privacy');

const openLegalModal = (type: 'privacy' | 'terms') => {
    legalModalType.value = type;
    showLegalModal.value = true;
};

const acceptLegalModal = () => {
    form.terms = true;
    showLegalModal.value = false;
};

const step = ref(1);
const totalSteps = 3;

const form = useForm({
    // Step 1: Property Info
    nit: '',
    nombre_copropiedad: '',
    direccion: '',
    ciudad: '',
    
    // Step 2: Operation
    plan: 'pro',
    unidades_totales: null as number | null,
    torres: null as number | null,
    
    // Step 3: Admin Account
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const showPassword = ref(false);
const showPasswordConfirm = ref(false);

const nextStep = () => {
    if (step.value < totalSteps) step.value++;
};

const prevStep = () => {
    if (step.value > 1) step.value--;
};

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

const stepInfo = computed(() => {
    switch (step.value) {
        case 1: return { title: 'Identificación', subtitle: 'Datos legales del conjunto' };
        case 2: return { title: 'Configuración', subtitle: 'Capacidad y plan de servicio' };
        case 3: return { title: 'Acceso', subtitle: 'Tu cuenta de administrador' };
        default: return { title: '', subtitle: '' };
    }
});
</script>

<template>
    <Head title="Registro de Copropiedad — NEXO-PRO" />

    <GuestLayout>
        <div class="max-w-4xl mx-auto w-full px-6 py-12 md:py-20 min-h-screen flex flex-col justify-center">
            
            <!-- Branding Area -->
            <div class="text-center mb-12 space-y-3">
                <div class="flex justify-center mb-6">
                    <Logo width="80px" height="80px" :show-text="false" />
                </div>
                <h1 class="text-4xl font-black text-primary tracking-tighter uppercase leading-none">Únete a NEXO-PRO</h1>
                <p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-[0.2em]">Estás a pocos pasos de digitalizar tu administración</p>
            </div>

            <!-- Enhanced Stepper -->
            <div class="mb-12 max-w-lg mx-auto w-full">
                <div class="flex items-center justify-between mb-6">
                    <div v-for="i in totalSteps" :key="i" class="flex items-center flex-1 last:flex-none">
                        <div 
                            class="w-10 h-10 rounded-2xl flex items-center justify-center text-xs font-black transition-all duration-500 border-2"
                            :class="[
                                i < step ? 'bg-secondary border-secondary text-on-secondary shadow-lg' : 
                                i === step ? 'bg-primary border-primary text-on-primary shadow-xl scale-110' : 
                                'bg-surface-container-low border-outline-variant/20 text-on-surface-variant/40'
                            ]"
                        >
                            <span v-if="i < step" class="material-symbols-outlined text-xl">check</span>
                            <span v-else>{{ i }}</span>
                        </div>
                        <div v-if="i < totalSteps" class="flex-1 h-0.5 mx-2 bg-outline-variant/10 rounded-full overflow-hidden">
                            <div 
                                class="h-full bg-secondary transition-all duration-700" 
                                :style="{ width: i < step ? '100%' : '0%' }"
                            ></div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <h3 class="text-sm font-black text-primary uppercase tracking-widest leading-none">{{ stepInfo.title }}</h3>
                    <p class="text-[10px] text-on-surface-variant/50 font-bold uppercase tracking-widest mt-1.5">{{ stepInfo.subtitle }}</p>
                </div>
            </div>

            <!-- Error Handling -->
            <Alert v-if="Object.keys(form.errors).length > 0" variant="error" title="Atención" class="mb-8">
                Por favor revisa los campos marcados en rojo para continuar.
            </Alert>

            <!-- Wizard Form -->
            <form @submit.prevent="submit" class="relative">
                <Transition
                    enter-active-class="transition duration-500 ease-out"
                    enter-from-class="opacity-0 translate-x-12"
                    enter-to-class="opacity-100 translate-x-0"
                    leave-active-class="transition duration-300 ease-in absolute w-full"
                    leave-from-class="opacity-100 translate-x-0"
                    leave-to-class="opacity-0 -translate-x-12"
                    mode="out-in"
                >
                    <!-- STEP 1: PROPERTY INFO -->
                    <div v-if="step === 1" :key="1">
                        <Card class="!p-8 md:!p-12">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                <Input v-model="form.nit" label="NIT de la Copropiedad" placeholder="900.XXX.XXX-X" icon="badge" required :error="form.errors.nit" />
                                <Input v-model="form.nombre_copropiedad" label="Nombre del Edificio / Conjunto" placeholder="Ej: Residencial El Sol" icon="corporate_fare" required :error="form.errors.nombre_copropiedad" />
                                <Input v-model="form.direccion" label="Dirección Física" placeholder="Calle 123 # 45-67" icon="location_on" required :error="form.errors.direccion" />
                                <Input v-model="form.ciudad" label="Ciudad / Municipio" placeholder="Ej: Medellín" icon="map" required :error="form.errors.ciudad" />
                            </div>
                        </Card>
                    </div>

                    <!-- STEP 2: OPERATION -->
                    <div v-else-if="step === 2" :key="2">
                        <Card class="!p-8 md:!p-12">
                            <div class="space-y-10">
                                <div class="space-y-4">
                                    <label class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-[0.2em] pl-1">Selecciona un Plan de Servicio</label>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div 
                                            v-for="p in ['basic', 'pro', 'enterprise']" 
                                            :key="p"
                                            @click="form.plan = p"
                                            class="p-6 rounded-[2rem] border-2 cursor-pointer transition-all duration-300 group relative overflow-hidden"
                                            :class="form.plan === p ? 'border-primary bg-primary/5 shadow-xl scale-[1.02]' : 'border-outline-variant/10 hover:border-primary/30'"
                                        >
                                            <div v-if="form.plan === p" class="absolute top-4 right-4">
                                                <span class="material-symbols-outlined text-primary text-xl">check_circle</span>
                                            </div>
                                            <p class="text-xs font-black uppercase tracking-widest text-primary mb-1">{{ p }}</p>
                                            <p class="text-[10px] font-bold text-on-surface-variant/60">
                                                {{ p === 'basic' ? 'Hasta 50 unidades' : p === 'pro' ? 'Hasta 200 unidades' : 'Unidades ilimitadas' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-4 border-t border-outline-variant/5">
                                    <Input v-model="form.unidades_totales" type="number" label="Unidades Totales" placeholder="0" icon="apartment" required :error="form.errors.unidades_totales" />
                                    <Input v-model="form.torres" type="number" label="Número de Torres / Bloques" placeholder="0" icon="domain" required :error="form.errors.torres" />
                                </div>
                            </div>
                        </Card>
                    </div>

                    <!-- STEP 3: ADMIN ACCOUNT -->
                    <div v-else :key="3">
                        <Card class="!p-8 md:!p-12">
                            <div class="space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <Input v-model="form.name" label="Nombre del Administrador" placeholder="Nombre completo" icon="person" required :error="form.errors.name" />
                                    <Input v-model="form.email" type="email" label="Correo Electrónico Corporativo" placeholder="admin@conjunto.com" icon="alternate_email" required :error="form.errors.email" />
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <div class="relative">
                                        <Input v-model="form.password" :type="showPassword ? 'text' : 'password'" label="Contraseña de Acceso" placeholder="••••••••" icon="lock" required :error="form.errors.password" />
                                        <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-6 z-20 text-on-surface-variant/40 hover:text-primary">
                                            <span class="material-symbols-outlined text-xl">{{ showPassword ? 'visibility_off' : 'visibility' }}</span>
                                        </button>
                                    </div>
                                    <div class="relative">
                                        <Input v-model="form.password_confirmation" :type="showPasswordConfirm ? 'text' : 'password'" label="Confirmar Contraseña" placeholder="••••••••" icon="verified_user" required />
                                        <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="absolute right-4 top-6 z-20 text-on-surface-variant/40 hover:text-primary">
                                            <span class="material-symbols-outlined text-xl">{{ showPasswordConfirm ? 'visibility_off' : 'visibility' }}</span>
                                        </button>
                                    </div>
                                </div>
                                <div class="pt-4 border-t border-outline-variant/5">
                                    <Checkbox 
                                        v-model="form.terms" 
                                        required 
                                        :error="form.errors.terms"
                                    >
                                        <template #description>
                                            Autorizo de forma previa, expresa e informada a NEXO-PRO para el tratamiento de mis datos personales según la <button type="button" @click.prevent.stop="openLegalModal('privacy')" class="text-primary font-bold hover:underline">Política de Privacidad</button> y la Ley 1581 de 2012, y acepto los <button type="button" @click.prevent.stop="openLegalModal('terms')" class="text-primary font-bold hover:underline">Términos y Condiciones</button>.
                                        </template>
                                    </Checkbox>
                                </div>
                            </div>
                        </Card>
                    </div>
                </Transition>

                <!-- Navigation Controls -->
                <div class="mt-12 flex flex-col sm:flex-row justify-between items-center gap-4 px-2">
                    <Button 
                        v-if="step > 1" 
                        variant="ghost" 
                        icon="arrow_back" 
                        @click="prevStep"
                    >
                        Volver
                    </Button>
                    <div v-else></div>

                    <div class="flex gap-4 w-full sm:w-auto">
                        <Button 
                            v-if="step < totalSteps" 
                            type="button" 
                            variant="primary" 
                            size="lg" 
                            class="w-full sm:w-auto !px-12 shadow-xl shadow-primary/20" 
                            icon="arrow_forward"
                            @click="nextStep"
                        >
                            Continuar
                        </Button>
                        <Button 
                            v-else 
                            type="submit" 
                            variant="secondary" 
                            size="lg" 
                            class="w-full sm:w-auto !px-12 shadow-xl shadow-secondary/20" 
                            icon="how_to_reg"
                            :loading="form.processing"
                        >
                            Finalizar Registro
                        </Button>
                    </div>
                </div>
            </form>

            <!-- Bottom Link -->
            <div class="mt-12 text-center">
                <p class="text-[10px] text-on-surface-variant/50 font-bold uppercase tracking-widest">
                    ¿Ya gestionas con nosotros? 
                    <Link :href="route('login')" class="text-primary font-black ml-1 hover:underline underline-offset-4">Inicia Sesión</Link>
                </p>
            </div>
        </div>
    </GuestLayout>

    <!-- Legal Modal -->
    <Modal v-if="showLegalModal" @close="showLegalModal = false" maxWidth="4xl">
        <div class="px-6 py-6 md:px-8 md:py-8 border-b border-outline-variant/10 flex justify-between items-center sticky top-0 bg-surface/80 backdrop-blur-xl z-20">
            <h3 class="text-sm font-black text-on-surface uppercase tracking-widest">
                Información Legal
            </h3>
            <button @click="showLegalModal = false" class="p-2 rounded-xl text-on-surface-variant hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
        
        <div class="p-6 md:p-8 max-h-[60vh] overflow-y-auto custom-scrollbar bg-surface">
            <LegalContent :type="legalModalType" />
        </div>
        
        <div class="px-6 py-4 md:px-8 bg-surface-container-low border-t border-outline-variant/10 flex justify-end gap-3">
            <Button variant="ghost" @click="showLegalModal = false">Cerrar Visualización</Button>
            <Button variant="primary" @click="acceptLegalModal" icon="check_circle">He leído y Acepto</Button>
        </div>
    </Modal>
</template>

<style scoped>
.material-symbols-outlined {
    font-variation-settings: 'opsz' 24;
}
</style>
