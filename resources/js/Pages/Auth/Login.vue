<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Button from '@/Components/UI/Button.vue';
import Input from '@/Components/UI/Input.vue';
import Alert from '@/Components/UI/Alert.vue';
import Logo from '@/Components/UI/Logo.vue';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Acceso Seguro — NEXO-PRO" />

    <GuestLayout>
        <div class="min-h-screen w-full flex items-center justify-center p-6 md:p-12 relative overflow-hidden bg-surface">
            <!-- Decorative Gradients -->
            <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-primary/20 blur-[120px] pointer-events-none"></div>
            <div class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-secondary/20 blur-[120px] pointer-events-none"></div>

            <div class="max-w-md w-full relative z-10">
                <!-- Premium Card Wrapping Form -->
                <div class="premium-card p-10 md:p-12 space-y-10">
                    <!-- Branding & Header -->
                    <div class="text-center space-y-4">
                        <div class="flex items-center justify-center mb-6 scale-110">
                            <Logo width="100px" height="100px" :show-text="false" />
                        </div>
                        <div class="space-y-2">
                            <h1 class="text-3xl font-extrabold text-on-surface tracking-tight">Bienvenido a NEXO-PRO</h1>
                            <p class="text-sm text-on-surface-variant font-medium">Inicia sesión en tu cuenta para gestionar tus propiedades.</p>
                        </div>
                    </div>

                    <!-- Error Handling -->
                    <Alert v-if="Object.keys(form.errors).length > 0" variant="error" title="Error de acceso" class="mb-6">
                        Las credenciales ingresadas no son correctas o el usuario no existe.
                    </Alert>

                    <!-- Login Form -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-5">
                            <Input 
                                v-model="form.email"
                                label="Correo Electrónico"
                                type="email"
                                placeholder="nombre@ejemplo.com"
                                icon="alternate_email"
                                required
                                autofocus
                                :error="form.errors.email"
                                class="min-h-[44px]"
                            />

                            <div class="space-y-2">
                                <Input 
                                    v-model="form.password"
                                    label="Contraseña"
                                    type="password"
                                    placeholder="••••••••"
                                    icon="lock"
                                    required
                                    :error="form.errors.password"
                                    class="min-h-[44px]"
                                />
                                <div class="flex justify-end pt-1">
                                    <Link href="#" class="text-xs font-bold text-primary hover:text-primary/80 transition-colors">¿Olvidaste tu contraseña?</Link>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-2">
                            <div class="relative flex items-center justify-center">
                                <input 
                                    v-model="form.remember" 
                                    type="checkbox" 
                                    id="remember"
                                    class="peer w-5 h-5 rounded-md border-2 border-outline-variant text-primary focus:ring-primary/20 transition-all cursor-pointer appearance-none checked:bg-primary checked:border-primary"
                                />
                                <span class="material-symbols-rounded text-white text-[14px] absolute pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                            </div>
                            <label for="remember" class="text-sm font-medium text-on-surface-variant cursor-pointer select-none">Mantener sesión activa</label>
                        </div>

                        <div class="space-y-6 pt-4">
                            <Button 
                                type="submit" 
                                variant="primary" 
                                size="lg" 
                                class="w-full !rounded-xl !py-4 shadow-lg shadow-primary/20 hover:shadow-primary/40 font-bold"
                                :loading="form.processing"
                                icon="login"
                            >
                                Ingresar al Sistema
                            </Button>

                            <div class="text-center">
                                <p class="text-sm text-on-surface-variant font-medium">
                                    ¿Aún no eres parte? 
                                    <Link :href="route('register')" class="text-primary font-bold ml-1 hover:underline underline-offset-4">Regístrate ahora</Link>
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer Context -->
                <div class="pt-10 flex justify-center items-center gap-8 opacity-40">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm">verified_user</span>
                        <span class="text-xs font-bold uppercase tracking-widest">Seguro</span>
                    </div>
                    <div class="w-1.5 h-1.5 rounded-full bg-on-surface-variant/30"></div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-rounded text-sm">shield_with_heart</span>
                        <span class="text-xs font-bold uppercase tracking-widest">Privado</span>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
.material-symbols-rounded {
    font-variation-settings: 'opsz' 20, 'wght' 600;
}
</style>
