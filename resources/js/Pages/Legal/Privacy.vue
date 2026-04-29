<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Logo from '@/Components/UI/Logo.vue';
</script>

<template>
    <Head title="Política de Privacidad — NEXO-PRO" />
    <GuestLayout>
        <div class="max-w-3xl mx-auto w-full px-6 py-20 min-h-screen">
            <div class="mb-12 text-center">
                <Link href="/" class="inline-block mb-8">
                    <Logo width="60px" height="60px" :show-text="false" />
                </Link>
                <h1 class="text-4xl font-black text-primary tracking-tighter uppercase leading-none mb-4">Política de Tratamiento de Datos</h1>
                <p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-[0.2em]">Última actualización: Abril 2026</p>
            </div>

            <div class="prose prose-sm prose-primary prose-headings:font-black prose-headings:uppercase prose-p:text-on-surface-variant prose-a:text-primary max-w-none bg-surface-container-low p-8 md:p-12 rounded-3xl border border-outline-variant/10 shadow-xl">
                <h3>1. Introducción y Marco Legal</h3>
                <p>
                    De conformidad con la Ley 1581 de 2012 y sus decretos reglamentarios (Colombia), la presente Política de Tratamiento de Datos establece las finalidades, medidas y procedimientos mediante los cuales NEXO-PRO captura, almacena y procesa información personal.
                </p>

                <h3>2. Responsable del Tratamiento</h3>
                <p>
                    [AQUÍ DEBES INGRESAR LA RAZÓN SOCIAL DE LA EMPRESA, NIT, DIRECCIÓN, CORREO ELECTRÓNICO Y TELÉFONO DEL OFICIAL DE PRIVACIDAD]
                </p>

                <h3>3. Finalidad de la Recolección</h3>
                <p>
                    Los datos personales proporcionados serán utilizados exclusivamente para la gestión de acceso a la plataforma Multi-tenant, facturación automatizada, notificaciones de novedades y seguridad transaccional, asegurando un uso estrictamente vinculado al servicio tecnológico prestado.
                </p>

                <h3>4. Derechos de los Titulares</h3>
                <p>
                    Todo titular de la información tiene derecho a conocer, actualizar, rectificar y suprimir sus datos personales (Habeas Data). Este derecho se podrá ejercer a través del correo [CORREO_SOPORTE].
                </p>

                <h3>5. Uso de Cookies</h3>
                <p>
                    Cumpliendo con la guía de la Superintendencia de Industria y Comercio (SIC), utilizamos cookies técnicas absolutamente necesarias. Las analíticas solo se instalan previo consentimiento expreso en nuestro portal.
                </p>
                
                <hr class="my-8 border-outline-variant/20" />
                
                <p class="text-xs italic text-center">
                    **AVISO:** Este es un documento plantilla generado para estructura base. Debe ser reemplazado por los lineamientos de tu abogado o equipo legal corporativo.
                </p>
            </div>
            
            <div class="mt-12 text-center">
                <Link href="/" class="text-xs text-primary font-black uppercase tracking-widest hover:underline underline-offset-4">
                    ← Volver al inicio
                </Link>
            </div>
        </div>
    </GuestLayout>
</template>
