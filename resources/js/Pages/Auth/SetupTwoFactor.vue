<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { Head, useForm, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import QrcodeVue from 'qrcode.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Card from '@/Components/UI/Card.vue';
import Alert from '@/Components/UI/Alert.vue';
import Logo from '@/Components/UI/Logo.vue';

const page = usePage();
const user = computed(() => page.props.auth.user);

const enabling = ref(false);
const confirming = ref(false);
const qrCode = ref('');
const recoveryCodes = ref<string[]>([]);
const setupStep = ref(1); // 1: Intro, 2: Scan & Confirm, 3: Success/Recovery

const confirmationForm = useForm({
    code: '',
});

const enableTwoFactorAuthentication = () => {
    enabling.value = true;

    axios.post('/user/two-factor-authentication')
        .then(() => {
            return Promise.all([
                showQrCode(),
                showRecoveryCodes(),
            ]);
        })
        .then(() => {
            enabling.value = false;
            setupStep.value = 2;
        })
        .catch(error => {
            console.error('Error enabling 2FA:', error);
            enabling.value = false;
        });
};

const showQrCode = () => {
    return axios.get('/user/two-factor-qr-code')
        .then(response => {
            qrCode.value = response.data.svg;
        });
};

const showRecoveryCodes = () => {
    return axios.get('/user/two-factor-recovery-codes')
        .then(response => {
            recoveryCodes.value = response.data;
        });
};

const confirmTwoFactorAuthentication = () => {
    confirming.value = true;

    confirmationForm.post('/user/confirmed-two-factor-authentication', {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            confirming.value = false;
            setupStep.value = 3;
        },
        onError: () => {
            confirming.value = false;
        }
    });
};

const finishSetup = () => {
    const role = user.value?.role;
    let home = route('dashboard');

    if (role === 'super_admin') {
         home = route('superadmin.dashboard');
    } else if (role === 'owner') {
         home = route('owner.dashboard');
    }

    router.visit(home);
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <Head title="Configuración de Seguridad" />

    <GuestLayout>
        <div class="max-w-2xl mx-auto w-full px-6 py-12 flex flex-col justify-center min-h-screen">
            
            <!-- Header -->
            <div class="text-center mb-10 space-y-4">
                <div class="flex justify-center mb-4">
                    <Logo width="64px" height="64px" :show-text="false" />
                </div>
                <h1 class="text-3xl font-black text-primary tracking-tight uppercase">Protege tu Cuenta</h1>
                <p class="text-sm text-on-surface-variant/70 font-medium">
                    NEXO-PRO te ofrece Autenticación de Dos Factores (2FA) para mayor seguridad de tu cuenta.
                </p>
            </div>

            <Card class="!p-8 md:!p-12 shadow-2xl border-primary/10 overflow-hidden relative">
                <!-- Decorative element -->
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-primary/5 rounded-full blur-3xl"></div>

                <Transition mode="out-in" 
                    enter-active-class="transition duration-300 ease-out"
                    enter-from-class="opacity-0 translate-y-4"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition duration-200 ease-in"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 -translate-y-4"
                >
                    <!-- STEP 1: WELCOME & START -->
                    <div v-if="setupStep === 1" :key="1" class="text-center space-y-6">
                        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                            <span class="material-symbols-outlined text-4xl text-primary">security</span>
                        </div>
                        <div class="space-y-4">
                            <h2 class="text-xl font-bold text-on-surface">¿Qué es el 2FA?</h2>
                            <p class="text-sm text-on-surface-variant">
                                Es una capa extra de seguridad. Además de tu contraseña, necesitarás un código generado en tu celular para ingresar. Esto evita accesos no autorizados incluso si alguien descubre tu clave.
                            </p>
                        </div>
                        <div class="pt-6 flex flex-col gap-4">
                            <Button variant="primary" size="lg" class="w-full" :loading="enabling" @click="enableTwoFactorAuthentication">
                                Configurar ahora
                            </Button>
                            <button @click="finishSetup" class="text-sm font-bold text-primary/80 hover:text-primary transition-colors uppercase tracking-widest">
                                Omitir
                            </button>
                            <button @click="logout" class="text-[10px] font-bold text-on-surface-variant/30 uppercase tracking-[0.2em] hover:text-on-surface-variant transition-colors">
                                Cerrar sesión
                            </button>
                        </div>
                    </div>

                    <!-- STEP 2: SCAN & CONFIRM -->
                    <div v-else-if="setupStep === 2" :key="2" class="space-y-8">
                        <div class="text-center space-y-2">
                            <h2 class="text-xl font-bold text-on-surface">Escanea el código QR</h2>
                            <p class="text-sm text-on-surface-variant">Usa Google Authenticator, Authy o tu app favorita.</p>
                        </div>

                        <div class="flex justify-center p-4 bg-white rounded-2xl border border-outline-variant/30 w-fit mx-auto shadow-inner">
                            <div v-html="qrCode" class="w-48 h-48 flex items-center justify-center"></div>
                        </div>

                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-on-surface-variant/60 uppercase tracking-[0.2em] pl-1">
                                Ingresa el código de 6 dígitos
                            </label>
                            <div class="flex flex-col gap-4">
                                <Input 
                                    v-model="confirmationForm.code" 
                                    type="text" 
                                    placeholder="000 000" 
                                    icon="pin" 
                                    maxlength="6"
                                    autofocus
                                    :error="confirmationForm.errors.code"
                                    @keyup.enter="confirmTwoFactorAuthentication"
                                />
                                <Button variant="secondary" size="lg" class="w-full" :loading="confirming" @click="confirmTwoFactorAuthentication">
                                    Verificar y activar
                                </Button>
                            </div>
                        </div>
                    </div>

                    <!-- STEP 3: SUCCESS & RECOVERY -->
                    <div v-else :key="3" class="space-y-8">
                        <div class="text-center space-y-4">
                            <div class="w-16 h-16 bg-secondary/10 rounded-full flex items-center justify-center mx-auto">
                                <span class="material-symbols-outlined text-3xl text-secondary">verified</span>
                            </div>
                            <h2 class="text-xl font-bold text-on-surface">¡Seguridad activada!</h2>
                            <p class="text-sm text-on-surface-variant">
                                Tu cuenta ahora está protegida. Guarda estos códigos de recuperación en un lugar seguro. Los necesitarás si pierdes tu celular.
                            </p>
                        </div>

                        <div class="grid grid-cols-2 gap-2 bg-surface-container-low p-4 rounded-xl border border-outline-variant/20 font-mono text-xs">
                            <div v-for="code in recoveryCodes" :key="code" class="p-1 text-center select-all">
                                {{ code }}
                            </div>
                        </div>

                        <div class="pt-4">
                            <Button variant="primary" size="lg" class="w-full shadow-lg shadow-primary/20" @click="finishSetup">
                                Ir al Dashboard
                            </Button>
                        </div>
                    </div>
                </Transition>
            </Card>

            <p class="mt-8 text-center text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest leading-relaxed">
                NEXO-PRO — Secure Multi-Tenant Architecture<br>
                Compliance Phase 1: Identity & Access Management
            </p>
        </div>
    </GuestLayout>
</template>

<style scoped>
:deep(svg) {
    width: 100%;
    height: 100%;
}
</style>
