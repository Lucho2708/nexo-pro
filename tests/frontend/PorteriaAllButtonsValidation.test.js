import { test, describe } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

describe('Portería & Seguridad Front-End: Button-by-Button Functionality & Contracts Audit', () => {
    const rootDir = process.cwd();

    test('1. SecurityLayout.vue: Header, Mobile Nav, Relevo Modal & Logout buttons are fully functional', () => {
        const layoutPath = path.join(rootDir, 'resources/js/Layouts/SecurityLayout.vue');
        const content = fs.readFileSync(layoutPath, 'utf8');

        // Nav routes
        assert.ok(content.includes("'porteria.dashboard'"), 'Dashboard link exists');
        assert.ok(content.includes("'porteria.visitas.index'"), 'Visitas link exists');
        assert.ok(content.includes("'porteria.recibos.index'"), 'Recibos link exists');
        assert.ok(content.includes("'porteria.paquetes.index'"), 'Paquetes link exists');
        assert.ok(content.includes("'porteria.parqueaderos.index'"), 'Parqueaderos link exists');
        assert.ok(content.includes("'porteria.minuta.index'"), 'Minuta link exists');
        assert.ok(content.includes("'porteria.supervision'"), 'Supervisión B2B link exists');

        // Relevar turno button
        assert.ok(content.includes('showRelevoModal = true'), 'Relevar turno button opens modal');

        // Logout button
        assert.ok(content.includes('@click="logout"'), 'Logout button has click handler');
        assert.ok(content.includes("router.post(route('logout'))"), 'Logout handler dispatches POST logout');
    });

    test('2. RelevoTurnoModal.vue: Tactile keypad 0-9, Clear, Backspace & Confirm buttons are bound correctly', () => {
        const modalPath = path.join(rootDir, 'resources/js/Components/Porteria/RelevoTurnoModal.vue');
        const content = fs.readFileSync(modalPath, 'utf8');

        // Keypad buttons
        assert.ok(content.includes('@click="appendDigit(digit)"'), 'Digits 1-9 trigger appendDigit');
        assert.ok(content.includes('@click="appendDigit(0)"'), 'Digit 0 triggers appendDigit');
        assert.ok(content.includes('@click="clearPin"'), 'Clear button triggers clearPin');
        assert.ok(content.includes('@click="backspace"'), 'Backspace button triggers backspace');

        // Modal action buttons
        assert.ok(content.includes('@click="closeModal"'), 'Cancel button triggers closeModal');
        assert.ok(content.includes('@click="submitRelevo"'), 'Confirm button triggers submitRelevo');
        assert.ok(content.includes(':disabled="!isPinComplete || form.processing"'), 'Confirm button blocks double submit or incomplete PIN');
        assert.ok(content.includes("route('porteria.relevar-turno')"), 'Relevo submits to porteria.relevar-turno');
    });

    test('3. Visitas/Index.vue: Registration, QR scan, Citofonia WhatsApp & Exit buttons are functional', () => {
        const visitasPath = path.join(rootDir, 'resources/js/Pages/Porteria/Visitas/Index.vue');
        const content = fs.readFileSync(visitasPath, 'utf8');

        // Header action buttons
        assert.ok(content.includes('isPaseModalOpen = true'), 'Validar Pase button opens pase modal');
        assert.ok(content.includes('isModalOpen = true'), 'Registrar Visita button opens registration modal');

        // Table action button
        assert.ok(content.includes('marcarSalida(visita.id)'), 'Marcar Salida button triggers exit handler');
        assert.ok(content.includes("route('porteria.visitas.salida'"), 'Marcar Salida patches salida route');

        // Citofonia Virtual interactive button
        assert.ok(content.includes('@click="llamarCitofonia"'), 'Timbrar Celular button triggers llamarCitofonia');
        assert.ok(content.includes("route('porteria.citofonia.solicitar')"), 'Citofonía requests solicitation endpoint');

        // Form submit buttons
        assert.ok(content.includes('submitIngreso'), 'Ingreso form submits via submitIngreso');
        assert.ok(content.includes(':disabled="form.processing"'), 'Submit ingreso has anti-double-click protection');
        assert.ok(content.includes('submitPase'), 'Pase modal submits via submitPase');
        assert.ok(content.includes(':disabled="paseForm.processing"'), 'Submit pase has anti-double-click protection');
    });

    test('4. Paquetes/Index.vue: Encomienda reception & PIN verification delivery buttons are functional', () => {
        const paquetesPath = path.join(rootDir, 'resources/js/Pages/Porteria/Paquetes/Index.vue');
        const content = fs.readFileSync(paquetesPath, 'utf8');

        // Reception button
        assert.ok(content.includes('isModalOpen = true'), 'Recibir Nueva Encomienda button opens modal');
        assert.ok(content.includes('submitPaquete'), 'Reception form submits via submitPaquete');
        assert.ok(content.includes(':disabled="form.processing"'), 'Reception form has anti-double-click protection');

        // Delivery modal button
        assert.ok(content.includes('abrirEntregaModal(paquete)'), 'Table Entregar button opens modal with package data');
        assert.ok(content.includes('confirmarEntrega'), 'Entrega form submits via confirmarEntrega');
        assert.ok(content.includes(':disabled="entregaForm.processing"'), 'Entrega form has anti-double-click protection');
        assert.ok(content.includes("route('porteria.paquetes.entregar'"), 'Entrega patches package delivery route');
    });

    test('5. Recibos/Index.vue: Utility bill logging modal and submission buttons are functional', () => {
        const recibosPath = path.join(rootDir, 'resources/js/Pages/Porteria/Recibos/Index.vue');
        const content = fs.readFileSync(recibosPath, 'utf8');

        // Reception button
        assert.ok(content.includes('isModalOpen = true'), 'Radicar Recibo button opens modal');
        assert.ok(content.includes('submitRecibo'), 'Recibo form submits via submitRecibo');
        assert.ok(content.includes(':disabled="form.processing"'), 'Recibo form has anti-double-click protection');
        assert.ok(content.includes("route('porteria.recibos.store')"), 'Recibo submits to porteria.recibos.store');
    });

    test('6. Parqueaderos/Index.vue: Vehicle check-in, liquidation, Wompi checkout & cash payment buttons are functional', () => {
        const parqueaderosPath = path.join(rootDir, 'resources/js/Pages/Porteria/Parqueaderos/Index.vue');
        const content = fs.readFileSync(parqueaderosPath, 'utf8');

        // Check-in button
        assert.ok(content.includes('isModalOpen = true'), 'Ingreso de Vehículo button opens modal');
        assert.ok(content.includes(':disabled="disponibilidad.alerta_sobrecupo"'), 'Ingreso button blocks entry if parking is full');
        assert.ok(content.includes('submitIngresoVehiculo'), 'Ingreso form submits via submitIngresoVehiculo');

        // Liquidation & Exit table button
        assert.ok(content.includes('iniciarSalidaVehiculo(reg)'), 'Salida / Cobro button triggers liquidation calculation');
        assert.ok(content.includes("route('porteria.parqueaderos.liquidar'"), 'Liquidar calls calculation endpoint');

        // Checkout action buttons
        assert.ok(content.includes('salidaCortesiaDirecta'), 'Autorizar Salida Gratuita button executes direct exit');
        assert.ok(content.includes("confirmarPagoYSalida('wompi_pse')"), 'Cobrar con Wompi button invokes digital checkout');
        assert.ok(content.includes("confirmarPagoYSalida('efectivo')"), 'Registrar Pago en Efectivo button invokes cash checkout');
        assert.ok(content.includes(':disabled="isProcessing"'), 'Checkout buttons have anti-double-click protection');
    });

    test('7. Minuta/Index.vue: Forensic novelty registration, photo evidence & timeline filter buttons are functional', () => {
        const minutaPath = path.join(rootDir, 'resources/js/Pages/Porteria/Minuta/Index.vue');
        const content = fs.readFileSync(minutaPath, 'utf8');

        // Registration button
        assert.ok(content.includes('isModalOpen = true'), 'Registrar Novedad Forense opens modal');

        // Filter buttons
        assert.ok(content.includes("activeFilter = 'todos'"), 'Filter Todos button functions');
        assert.ok(content.includes("activeFilter = 'urgente'"), 'Filter Urgente button functions');
        assert.ok(content.includes("activeFilter = 'ronda'"), 'Filter Ronda button functions');
        assert.ok(content.includes("activeFilter = 'cambio_turno'"), 'Filter Cambio Turno button functions');

        // Photo evidence buttons
        assert.ok(content.includes('clearFoto'), 'Quitar Foto button functions');
        assert.ok(content.includes('submitMinuta'), 'Submit form executes submitMinuta');
        assert.ok(content.includes(':disabled="form.processing"'), 'Submit minuta has anti-double-click protection');
        assert.ok(content.includes("route('porteria.minuta.store')"), 'Minuta submits to porteria.minuta.store');
    });

    test('8. Supervision/Index.vue: B2B audit, guardhouse inspection modal & status filter buttons are functional', () => {
        const supervisionPath = path.join(rootDir, 'resources/js/Pages/Porteria/Supervision/Index.vue');
        const content = fs.readFileSync(supervisionPath, 'utf8');

        // Status filters
        assert.ok(content.includes("filterStatus = 'TODOS'"), 'Filter Todos button functions');
        assert.ok(content.includes("filterStatus = 'CRITICO'"), 'Filter Crítico button functions');
        assert.ok(content.includes("filterStatus = 'ADVERTENCIA'"), 'Filter Advertencia button functions');
        assert.ok(content.includes("filterStatus = 'NORMAL'"), 'Filter Normal button functions');

        // Audit & inspection buttons
        assert.ok(content.includes('selectedPuesto = puesto'), 'Auditar Garita button opens inspection modal');
        assert.ok(content.includes("route('porteria.minuta.index')"), 'Ver Bitácora Completa link functions');
        assert.ok(content.includes('selectedPuesto = null'), 'Cerrar Inspección button functions');
    });

    test('9. All button elements across the Portería module declare explicit type attributes', () => {
        const files = [
            'resources/js/Layouts/SecurityLayout.vue',
            'resources/js/Components/Porteria/RelevoTurnoModal.vue',
            'resources/js/Pages/Porteria/Dashboard.vue',
            'resources/js/Pages/Porteria/Visitas/Index.vue',
            'resources/js/Pages/Porteria/Paquetes/Index.vue',
            'resources/js/Pages/Porteria/Recibos/Index.vue',
            'resources/js/Pages/Porteria/Parqueaderos/Index.vue',
            'resources/js/Pages/Porteria/Minuta/Index.vue',
            'resources/js/Pages/Porteria/Supervision/Index.vue',
        ];

        for (const file of files) {
            const filePath = path.join(rootDir, file);
            const content = fs.readFileSync(filePath, 'utf8');
            const buttonRegex = /<button\b([^>]*)>/g;
            let match;
            while ((match = buttonRegex.exec(content)) !== null) {
                const buttonAttrs = match[1];
                assert.ok(
                    buttonAttrs.includes('type="button"') || buttonAttrs.includes('type="submit"') || buttonAttrs.includes('type="reset"'),
                    `File ${file} has a <button> without explicit type attribute: ${match[0]}`
                );
            }
        }
    });
});
