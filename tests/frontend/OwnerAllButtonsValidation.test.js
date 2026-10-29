import { describe, it } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

const projectRoot = path.resolve('/home/lsilva/Documentos/Laravel/nexo-pro');

function readComponent(relPath) {
    const fullPath = path.join(projectRoot, relPath);
    return fs.readFileSync(fullPath, 'utf8');
}

describe('Owner Ecosystem Full-Stack Buttons, Links & Forms Verification', () => {

    describe('OwnerLayout.vue Navigation & Shell', () => {
        const layoutSrc = readComponent('resources/js/Layouts/OwnerLayout.vue');

        it('V-01: Logo vincula a route("owner.dashboard")', () => {
            assert.ok(layoutSrc.includes(':href="route(\'owner.dashboard\')"'), 'El logo debe enlazar al dashboard');
        });

        it('V-02: Barra lateral de escritorio contiene todas las rutas canónicas del propietario', () => {
            assert.ok(layoutSrc.includes("href: route('owner.dashboard')"), 'Debe incluir dashboard');
            assert.ok(layoutSrc.includes("href: route('owner.payments')"), 'Debe incluir pagos');
            assert.ok(layoutSrc.includes("href: route('reservas.index')"), 'Debe incluir reservas');
            assert.ok(layoutSrc.includes("href: route('pqrs.index')"), 'Debe incluir pqrs');
        });

        it('V-03: Botón de colapsar/expandir barra lateral invoca alternancia reactiva', () => {
            assert.ok(layoutSrc.includes('@click="isSidebarExpanded = !isSidebarExpanded"'), 'Debe alternar isSidebarExpanded');
        });

        it('V-04: Botón de avatar de usuario alterna menú desplegable', () => {
            assert.ok(layoutSrc.includes('@click="showUserMenu = !showUserMenu"'), 'Debe alternar showUserMenu');
        });

        it('V-05: Menú de usuario contiene accesos a Perfil, Soporte y Cerrar Sesión', () => {
            assert.ok(layoutSrc.includes(':href="route(\'profile.edit\')"'), 'Debe enlazar a edición de perfil');
            assert.ok(layoutSrc.includes(':href="route(\'support.index\')"'), 'Debe enlazar a soporte');
            assert.ok(layoutSrc.includes('@click="logout"'), 'Debe invocar logout');
        });

        it('V-06: Dock móvil inferior provee los 4 módulos esenciales con touch targets ergonómicos', () => {
            assert.ok(layoutSrc.includes('v-for="item in mobileNavItems"'), 'Dock móvil itera los items principales');
            assert.ok(layoutSrc.includes('@click="isMobileMenuOpen = true"'), 'Botón Más abre el drawer móvil');
            assert.ok(layoutSrc.includes('@click="isMobileMenuOpen = false"'), 'Botón Cerrar cierra el drawer móvil');
        });

        it('V-07: Conmutador de copropiedad multi-inmueble llama a switchCopropiedad', () => {
            assert.ok(layoutSrc.includes('switchCopropiedad(prop.id)'), 'Debe permitir cambiar entre conjuntos residenciales');
        });
    });

    describe('Dashboard.vue Buttons & Dynamic CTAs', () => {
        const dashboardSrc = readComponent('resources/js/Pages/Owner/Dashboard.vue');

        it('V-08: Botón de pago en encabezado se muestra cuando no está al día y llama a handlePayment', () => {
            assert.ok(dashboardSrc.includes('v-if="!isPazYSalvo"'), 'Condicional según saldo');
            assert.ok(dashboardSrc.includes('@click="handlePayment"'), 'Llama a handlePayment');
        });

        it('V-09: Botón de Paz y Salvo vigente genera descarga oficial en 1 clic', () => {
            assert.ok(dashboardSrc.includes('@click="downloadCertificate"'), 'Llama a downloadCertificate');
        });

        it('V-10: Los 4 accesos rápidos enlazan a sus módulos correspondientes', () => {
            assert.ok(dashboardSrc.includes(':href="route(\'owner.payments\')"'), 'Enlace a pagos');
            assert.ok(dashboardSrc.includes(':href="route(\'reservas.index\')"'), 'Enlace a reservas');
            assert.ok(dashboardSrc.includes(':href="route(\'pqrs.index\')"'), 'Enlace a PQRS');
            assert.ok(dashboardSrc.includes('@click="downloadCertificate"'), 'Descarga de Paz y Salvo');
        });

        it('V-11: Comunicados abren modal con detalle oficial', () => {
            assert.ok(dashboardSrc.includes('@click="selectedAnnouncement = item"'), 'Abre modal con el comunicado');
            assert.ok(dashboardSrc.includes('@click="selectedAnnouncement = null"'), 'Cierra modal de comunicado');
        });

        it('V-12: Eventos de Asamblea en vivo ofrecen enlace directo', () => {
            assert.ok(dashboardSrc.includes(':href="route(\'asambleas.show\', asamblea.id)"'), 'Enlace a la asamblea en vivo');
        });

        it('V-13: Modal de selección de pasarela vincula selección de método', () => {
            assert.ok(dashboardSrc.includes('@select="processPayment"'), 'Procesa pago con pasarela seleccionada');
            assert.ok(dashboardSrc.includes('@close="showMethodModal = false"'), 'Permite cerrar modal de pasarela');
        });
    });

    describe('Payments/Index.vue Buttons & Interactions', () => {
        const paymentsSrc = readComponent('resources/js/Pages/Owner/Payments/Index.vue');

        it('V-14: Botón de pago global en encabezado invoca handlePayment()', () => {
            assert.ok(paymentsSrc.includes('@click="handlePayment()"'), 'Llama a pago global');
        });

        it('V-15: Botón individual de pago por unidad vincula la unidad correspondiente', () => {
            assert.ok(paymentsSrc.includes('@click="handlePayment(unidad)"'), 'Paga unidad específica');
        });

        it('V-16: Píldoras de filtrado de transacciones alternan tipo de movimiento', () => {
            assert.ok(paymentsSrc.includes('@click="filterType = filter.key as any"'), 'Cambia el filtro reactivo');
        });

        it('V-17: Buscador de transacciones actualiza reactivamente el modelo searchQuery', () => {
            assert.ok(paymentsSrc.includes('v-model="searchQuery"'), 'Binds searchQuery');
        });

        it('V-18: Clic en transacción abre modal con comprobante digital detallado', () => {
            assert.ok(paymentsSrc.includes('@click="selectedTx = tx"'), 'Abre recibo detallado');
            assert.ok(paymentsSrc.includes('@click="printReceipt"'), 'Ofrece botón para imprimir comprobante');
            assert.ok(paymentsSrc.includes('@click="selectedTx = null"'), 'Cierra comprobante');
        });
    });

    describe('Reservas/Index.vue Wizard, Form & Actions', () => {
        const reservasSrc = readComponent('resources/js/Pages/Owner/Reservas/Index.vue');

        it('V-19: Clic en tarjeta de zona común activa el paso 2 del wizard', () => {
            assert.ok(reservasSrc.includes('@click="selectZona(zona)"'), 'Selecciona zona e inicia wizard');
        });

        it('V-20: Botón de retorno desde paso 2 regresa al paso 1', () => {
            assert.ok(reservasSrc.includes('@click="step = 1"'), 'Regresa a selección de espacio');
        });

        it('V-21: Campo de fecha vincula form.fecha', () => {
            assert.ok(reservasSrc.includes('v-model="form.fecha"'), 'Binds form.fecha en DatePicker');
        });

        it('V-22: Franjas horarias se deshabilitan si no hay fecha seleccionada y activan paso 3', () => {
            assert.ok(reservasSrc.includes(':disabled="!form.fecha"'), 'Bloquea franjas sin fecha');
            assert.ok(reservasSrc.includes('@click="selectSlot(slot)"'), 'Selecciona franja y pasa a paso 3');
        });

        it('V-23: Botón de retorno desde paso 3 regresa al paso 2', () => {
            assert.ok(reservasSrc.includes('@click="step = 2"'), 'Regresa a selección de horario');
        });

        it('V-24: Botón final de confirmación ejecuta submit con protección anti-doble clic', () => {
            assert.ok(reservasSrc.includes('@click="submit"'), 'Invoca envío de reserva');
            assert.ok(reservasSrc.includes(':loading="form.processing"'), 'Muestra estado loading');
            assert.ok(reservasSrc.includes(':disabled="form.processing"'), 'Bloquea doble clic');
        });

        it('V-25: Acción de cancelar reserva solicita confirmación antes de ejecutar', () => {
            assert.ok(reservasSrc.includes('@click="confirmCancel(res)"'), 'Abre modal de confirmación');
            assert.ok(reservasSrc.includes('@click="executeCancel"'), 'Ejecuta cancelación');
            assert.ok(reservasSrc.includes('@click="cancellingReserva = null"'), 'Permite descartar cancelación');
        });
    });

    describe('Pqrs/Index.vue Form, Filters & Actions', () => {
        const pqrsSrc = readComponent('resources/js/Pages/Owner/Pqrs/Index.vue');

        it('V-26: Botón de nueva PQRS en encabezado y empty state abre el modal', () => {
            assert.ok(pqrsSrc.includes('@click="showCreateModal = true"'), 'Abre modal de radicación');
        });

        it('V-27: Filtros de estado y categoría alternan reactivamente', () => {
            assert.ok(pqrsSrc.includes('@click="activeStatusFilter = st.key"'), 'Filtro por estado');
            assert.ok(pqrsSrc.includes('@click="activeTypeFilter = cat.key"'), 'Filtro por categoría');
        });

        it('V-28: Buscador vincula searchQuery reactivamente', () => {
            assert.ok(pqrsSrc.includes('v-model="searchQuery"'), 'Binds searchQuery');
        });

        it('V-29: Clic en tarjeta de radicado expande y colapsa la conversación', () => {
            assert.ok(pqrsSrc.includes('@click="toggleExpand(item.id)"'), 'Alterna visibilidad del radicado');
        });

        it('V-30: Botón de descarga de PDF invoca downloadPdf(item.id)', () => {
            assert.ok(pqrsSrc.includes('@click="downloadPdf(item.id)"'), 'Descarga el certificado en PDF');
        });

        it('V-31: Botón de reabrir caso cerrado ejecuta reopenTicket(item.id)', () => {
            assert.ok(pqrsSrc.includes('@click="reopenTicket(item.id)"'), 'Reabre radicado cerrado');
        });

        it('V-32: Formulario modal contiene directiva @submit.prevent="submitPqrs"', () => {
            assert.ok(pqrsSrc.includes('@submit.prevent="submitPqrs"'), 'Previene recarga y ejecuta submitPqrs');
        });

        it('V-33: Todos los campos del formulario de PQRS tienen contrato v-model bidireccional', () => {
            assert.ok(pqrsSrc.includes('v-model="form.unidad_id"'), 'Binds unidad_id');
            assert.ok(pqrsSrc.includes('v-model="form.tipo"'), 'Binds tipo');
            assert.ok(pqrsSrc.includes('v-model="form.prioridad"'), 'Binds prioridad');
            assert.ok(pqrsSrc.includes('v-model="form.asunto"'), 'Binds asunto');
            assert.ok(pqrsSrc.includes('v-model="form.mensaje"'), 'Binds mensaje');
        });

        it('V-34: Botón de envío del formulario está blindado contra doble envío con form.processing', () => {
            assert.ok(pqrsSrc.includes(':disabled="form.processing"'), 'Bloquea envío duplicado en vuelo');
        });
    });

    describe('UnitSpecsCard.vue Multi-Unit Switching', () => {
        const specsSrc = readComponent('resources/js/Components/Owner/UnitSpecsCard.vue');

        it('V-35: Si el usuario tiene múltiples inmuebles, cada pestaña asigna selectedUnitId', () => {
            assert.ok(specsSrc.includes('@click="selectedUnitId = u.id"'), 'Alterna el inmueble seleccionado');
        });
    });
});
