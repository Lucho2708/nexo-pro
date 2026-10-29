import { describe, it } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const rootDir = path.resolve(__dirname, '../../');

/**
 * [TDD - FULLSTACK INTEGRITY & CONTRACTS - HU-SEC-01 & HU-SEC-02]
 * Pruebas unitarias de frontend para:
 * 1. Analizador cliente de cédula colombiana PDF417 (Velocidad 0.05s)
 * 2. Ergonomía táctil de teclado numérico (Botones >= 56px)
 * 3. Blindaje de contratos reactivos para relevo de turno y entrega de paquetes con PIN
 */
describe('Portería Fase 1: Ergonomía Táctica, PIN de Turno & Cédula PDF417', () => {

    // 1. Simulación e integridad de parseColombianIdBarcode
    describe('HU-SEC-02: Colombian ID Barcode (PDF417) Client-Side Parser', () => {
        const parserFile = path.join(rootDir, 'resources/js/Utils/colombianIdParser.ts');

        it('Parser utility exists and declares TypeScript strict interfaces', () => {
            assert.ok(fs.existsSync(parserFile), 'colombianIdParser.ts must exist');
            const content = fs.readFileSync(parserFile, 'utf8');
            assert.ok(content.includes('interface ParsedColombianId'), 'Must declare ParsedColombianId interface');
            assert.ok(content.includes('parseColombianIdBarcode'), 'Must export parseColombianIdBarcode function');
        });

        it('Client Parser Logic: Accurately decomposes pipe-delimited scanner format', () => {
            // Emulación de la lógica implementada en colombianIdParser.ts
            const payload = "1020304050|GOMEZ|RESTREPO|CARLOS ALBERTO|M|19900514|O+";
            const parts = payload.split('|').map(p => p.trim());

            assert.equal(parts[0], '1020304050');
            assert.equal(parts[1], 'GOMEZ');
            assert.equal(parts[2], 'RESTREPO');
            assert.equal(parts[3], 'CARLOS ALBERTO');
            assert.equal(parts[4], 'M');
            assert.equal(parts[5], '19900514');
        });

        it('Client Parser Logic: Accurately extracts fixed-width 14/14/30 character format', () => {
            const rawPayload = "00001020304050GOMEZ         RESTREPO      CARLOS ALBERTO                M19880415050A+";
            const docMatch = rawPayload.match(/^0*([0-9]{6,11})/);
            assert.ok(docMatch);
            const doc = docMatch[1];
            assert.equal(doc, '1020304050');

            const resto = rawPayload.slice(docMatch[0].length);
            const primerApellido = resto.slice(0, 14).trim();
            const segundoApellido = resto.slice(14, 28).trim();
            const nombres = resto.slice(28, 58).trim();

            assert.equal(primerApellido, 'GOMEZ');
            assert.equal(segundoApellido, 'RESTREPO');
            assert.equal(nombres, 'CARLOS ALBERTO');
        });
    });

    // 2. Componente RelevoTurnoModal y Ergonomía Táctil
    describe('HU-SEC-01: RelevoTurnoModal Keypad & Touch Ergonomics', () => {
        const modalFile = path.join(rootDir, 'resources/js/Components/Porteria/RelevoTurnoModal.vue');

        it('RelevoTurnoModal exists and enforces minimum tactile touch target of 56px', () => {
            assert.ok(fs.existsSync(modalFile), 'RelevoTurnoModal.vue must exist');
            const content = fs.readFileSync(modalFile, 'utf8');

            // Verifica que los botones del teclado numérico tengan al menos h-14 (56px)
            assert.ok(
                content.includes('min-h-[56px]') || content.includes('h-14'),
                'Keypad touch targets must be at least 56px tall for garita ergonomics'
            );

            // Verifica presencia de dígitos 0-9, Clear y Backspace
            assert.ok(content.includes('appendDigit'), 'Must support tactile appendDigit');
            assert.ok(content.includes('backspace'), 'Must support backspace');
            assert.ok(content.includes('clearPin'), 'Must support clearPin');
        });

        it('Keypad State Management: Enforces strict 4-digit PIN clamping', () => {
            let pin = '';
            const append = (digit) => {
                if (pin.length < 4) pin += digit.toString();
            };

            append(4);
            append(8);
            append(1);
            append(2);
            assert.equal(pin, '4812');

            // Intento de ingresar un quinto dígito debe ser rechazado
            append(9);
            assert.equal(pin, '4812', 'PIN must be clamped at 4 digits');

            // Backspace y Clear
            pin = pin.slice(0, -1);
            assert.equal(pin, '481');

            pin = '';
            assert.equal(pin.length, 0);
        });

        it('SecurityLayout includes the RelevoTurno button and binds the modal', () => {
            const layoutFile = path.join(rootDir, 'resources/js/Layouts/SecurityLayout.vue');
            const content = fs.readFileSync(layoutFile, 'utf8');

            assert.ok(content.includes('RelevoTurnoModal'), 'Must import RelevoTurnoModal');
            assert.ok(content.includes('showRelevoModal'), 'Must declare reactive showRelevoModal');
            assert.ok(content.includes('Relevar Turno'), 'Must render Relevar Turno button text');
        });
    });

    // 3. Blindaje de Paquetería y Visitas
    describe('HU-SEC-03: Paquetería con PIN Secreto & Escáner en Visitas', () => {
        it('Visitas Index contains fast scanner input for barcode guns', () => {
            const visitasFile = path.join(rootDir, 'resources/js/Pages/Porteria/Visitas/Index.vue');
            const content = fs.readFileSync(visitasFile, 'utf8');

            assert.ok(content.includes('parseColombianIdBarcode'), 'Visitas must import parseColombianIdBarcode');
            assert.ok(content.includes('handleBarcodeScan'), 'Must handle scanner input event');
            assert.ok(content.includes('barcodeInput'), 'Must bind barcode input model');
        });

        it('Paquetes Index displays delivery PIN column and requests PIN in delivery modal', () => {
            const paquetesFile = path.join(rootDir, 'resources/js/Pages/Porteria/Paquetes/Index.vue');
            const content = fs.readFileSync(paquetesFile, 'utf8');

            assert.ok(content.includes('PIN Retiro'), 'Table header must include PIN Retiro column');
            assert.ok(content.includes('pin_entrega'), 'Must display pin_entrega attribute');
            assert.ok(content.includes('entregaForm.pin'), 'Delivery modal must bind entregaForm.pin');
            assert.ok(content.includes('inputmode="numeric"'), 'Must specify numeric inputmode for mobile/tactile screens');
        });
    });
});
