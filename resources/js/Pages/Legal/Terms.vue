<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Logo from '@/Components/UI/Logo.vue';
</script>

<template>
    <Head title="Términos y Condiciones — NEXO-PRO" />
    <GuestLayout>
        <div class="max-w-3xl mx-auto w-full px-6 py-20 min-h-screen">
            <div class="mb-12 text-center">
                <Link href="/" class="inline-block mb-8">
                    <Logo width="60px" height="60px" :show-text="false" />
                </Link>
                <h1 class="text-4xl font-black text-primary tracking-tighter uppercase leading-none mb-4">Términos y Condiciones</h1>
                <p class="text-xs text-on-surface-variant/60 font-bold uppercase tracking-[0.2em]">Última actualización: Abril 2026</p>
            </div>

            <div class="prose prose-sm prose-primary prose-headings:font-black prose-headings:uppercase prose-p:text-on-surface-variant prose-a:text-primary max-w-none bg-surface-container-low p-8 md:p-12 rounded-3xl border border-outline-variant/10 shadow-xl">
                <h3>1. Aceptación de los Términos</h3>
                <p>
                    Al acceder y utilizar la plataforma NEXO-PRO, usted acepta sujetarse a estos Términos y Condiciones Generales de Uso. Si usted no está de acuerdo con el contenido legal, por favor absténgase de usar el servicio.
                </p>

                <h3>2. Licencia de Uso</h3>
                <p>
                    La plataforma se otorga bajo un esquema SaaS (Software as a Service). La suscripción pagada otorga a la Copropiedad el derecho intransferible, no exclusivo e internacional para usar el software mediante los navegadores web.
                </p>

                <h3>3. Responsabilidad del Usuario</h3>
                <p>
                    El administrador de la copropiedad es el único responsable de la precisión, legalidad y veracidad de toda la información (incluidos los PQRS y los datos de cartera) cargados en la base de datos de manera voluntaria.
                </p>

                <h3>4. Nivel de Servicio (SLA)</h3>
                <p>
                    Ofrecemos un Uptime garantizado del 99.9%. El mantenimiento preventivo se realizará, en la medida de lo posible, fuera de horarios de carga y bajo anuncio generalizado.
                </p>

                <h3>5. Seguridad y Auditoría</h3>
                <p>
                    Con el fin de garantizar la integridad de los datos, la trazabilidad de las operaciones comerciales y la seguridad técnica ante incidentes, NEXO-PRO realiza un registro automático (Log de Auditoría) de las interacciones críticas. Este registro incluye metadatos técnicos de navegación pero omite estrictamente cualquier dato sensible o privado, cumpliendo con los estándares de Habeas Data y protección de la privacidad.
                </p>
                
                <hr class="my-8 border-outline-variant/20" />
                
                <p class="text-[10px] uppercase font-bold text-on-surface-variant/40 tracking-widest text-center leading-relaxed">
                    Al usar este sistema, usted reconoce que sus interacciones técnicas son registradas para fines de soporte y auditoría forense.
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
