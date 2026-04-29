<script setup lang="ts">
import { ref, nextTick } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Card from '@/Components/UI/Card.vue';
import Logo from '@/Components/UI/Logo.vue';

const recovery = ref(false);

const form = useForm({
    code: '',
    recovery_code: '',
});

const recoveryCodeInput = ref<HTMLInputElement | null>(null);
const codeInput = ref<HTMLInputElement | null>(null);

const toggleRecovery = async () => {
    recovery.value = ! recovery.value;

    await nextTick();

    if (recovery.value) {
        recoveryCodeInput.value?.focus();
        form.code = '';
    } else {
        codeInput.value?.focus();
        form.recovery_code = '';
    }
};

const submit = () => {
    form.post('/two-factor-challenge');
};
</script>

<template>
    <Head title="Verificación de Dos Pasos" />

    <GuestLayout>
        <div class="max-w-md mx-auto w-full px-6 py-12 flex flex-col justify-center min-h-screen">
            
            <div class="text-center mb-10 space-y-4">
                <div class="flex justify-center mb-4">
                    <Logo width="64px" height="64px" :show-text="false" />
                </div>
                <h1 class="text-2xl font-black text-primary tracking-tight uppercase">Verificación</h1>
                <p class="text-sm text-on-surface-variant/70 font-medium">
                    {{ recovery 
                        ? 'Ingresa uno de tus códigos de recuperación de emergencia.' 
                        : 'Ingresa el código generado por tu aplicación de autenticación.' }}
                </p>
            </div>

            <Card class="!p-8 shadow-xl">
                <form @submit.prevent="submit" class="space-y-6">
                    <div v-if="! recovery">
                        <Input 
                            ref="codeInput"
                            v-model="form.code" 
                            label="Código de Autenticación"
                            type="text" 
                            inputmode="numeric"
                            placeholder="000 000" 
                            icon="pin" 
                            maxlength="6"
                            autofocus
                            :error="form.errors.code"
                        />
                    </div>

                    <div v-else>
                        <Input 
                            ref="recoveryCodeInput"
                            v-model="form.recovery_code" 
                            label="Código de Recuperación"
                            type="text" 
                            placeholder="XXXXX-XXXXX" 
                            icon="key" 
                            :error="form.errors.recovery_code"
                        />
                    </div>

                    <div class="flex flex-col gap-4">
                        <Button variant="primary" size="lg" class="w-full shadow-lg shadow-primary/10" :loading="form.processing">
                            Verificar Acceso
                        </Button>
                        
                        <button 
                            type="button"
                            @click="toggleRecovery"
                            class="text-xs font-bold text-primary hover:underline underline-offset-4"
                        >
                            {{ recovery ? 'Usar código de autenticación' : 'Usar un código de recuperación' }}
                        </button>
                    </div>
                </form>
            </Card>

            <p class="mt-8 text-center text-[10px] text-on-surface-variant/40 font-bold uppercase tracking-widest leading-relaxed">
                NEXO-PRO — Secure Gateway<br>
                Two-Factor Challenge Component
            </p>
        </div>
    </GuestLayout>
</template>
