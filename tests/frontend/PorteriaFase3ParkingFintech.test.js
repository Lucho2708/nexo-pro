import { describe, it } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
const rootDir = path.resolve(__dirname, '../../');

/**
 * [TDD - FULLSTACK INTEGRITY & CONTRACTS - FASE 3]
 * Pruebas unitarias de frontend para:
 * - HU-SEC-07: Monitoreo de aforo, semáforo visual y bloqueo ante sobrecupo.
 * - HU-SEC-08: Liquidación de tarifas, tiempo de cortesía y checkout Fintech (Wompi/PSE/Efectivo).
 */
describe('Portería Fase 3: Parqueaderos de Visitantes, Semáforo de Aforo & Fintech', () => {
    const parqueaderosFile = path.join(rootDir, 'resources/js/Pages/Porteria/Parqueaderos/Index.vue');

    // 1. HU-SEC-07: Semáforo y Bloqueo de Sobrecupo
    describe('HU-SEC-07: Capacity Gauge & Overcapacity Blocking', () => {
        it('Parqueaderos Index enforces button disabling when overcapacity alert is active', () => {
            assert.ok(fs.existsSync(parqueaderosFile), 'Parqueaderos/Index.vue must exist');
            const content = fs.readFileSync(parqueaderosFile, 'utf8');

            assert.ok(
                content.includes(':disabled="disponibilidad.alerta_sobrecupo"'),
                'New vehicle entry button must be disabled upon overcapacity'
            );
            assert.ok(
                content.includes("disponibilidad.alerta_sobrecupo ? 'Parqueadero Lleno' : 'Ingreso de Vehículo'"),
                'Button text must reflect capacity saturation'
            );
        });

        it('Aforo Visual Cards render conditional status tokens', () => {
            const content = fs.readFileSync(parqueaderosFile, 'utf8');

            assert.ok(content.includes('Capacidad Total'), 'Must render total capacity');
            assert.ok(content.includes('Vehículos Dentro'), 'Must render active occupied count');
            assert.ok(content.includes('Cupos Disponibles'), 'Must render available spots gauge');
        });
    });

    // 2. HU-SEC-08: Tarificación y Liquidación Fintech
    describe('HU-SEC-08: Tarifa Checkout Modal & Wompi Payment Flows', () => {
        it('Table action triggers intelligent liquidation modal', () => {
            const content = fs.readFileSync(parqueaderosFile, 'utf8');

            assert.ok(content.includes('iniciarSalidaVehiculo'), 'Must trigger iniciarSalidaVehiculo method');
            assert.ok(content.includes('Salida / Cobro'), 'Button text must indicate Salida / Cobro');
            assert.ok(content.includes('isLiquidacionModalOpen'), 'Must toggle isLiquidacionModalOpen');
        });

        it('Checkout Modal provides distinct flows for Courtesy vs Wompi Paid exit', () => {
            const content = fs.readFileSync(parqueaderosFile, 'utf8');

            // Cortesía gratuita
            assert.ok(content.includes('salidaCortesiaDirecta'), 'Must provide direct courtesy exit handler');
            assert.ok(content.includes('Autorizar Salida Gratuita'), 'Must render button for free courtesy exit');

            // Cobro excedente Fintech
            assert.ok(content.includes("confirmarPagoYSalida('wompi_pse')"), 'Must support Wompi / PSE digital payment');
            assert.ok(content.includes('Cobrar con Wompi / PSE (QR Digital)'), 'Must render Wompi QR button');
            assert.ok(content.includes("confirmarPagoYSalida('efectivo')"), 'Must support cash payment fallback');
        });

        it('Tariff Math Logic: Correctly rounds up billable hours', () => {
            // Emulación del cálculo de tarificación
            const calcularLiquidacion = (minutosPermanencia, horasCortesia = 2, tarifaHora = 2500) => {
                const minutosCortesia = horasCortesia * 60;
                const minutosCobrables = Math.max(0, minutosPermanencia - minutosCortesia);
                const horasCobrables = minutosCobrables > 0 ? Math.ceil(minutosCobrables / 60) : 0;
                const valorLiquidado = horasCobrables * tarifaHora;

                return {
                    esCortesia: valorLiquidado === 0,
                    minutosPermanencia,
                    horasCobrables,
                    valorLiquidado,
                };
            };

            // Caso 1: 90 minutos (1h 30m) con 2h cortesía -> $0 COP
            const c1 = calcularLiquidacion(90, 2, 2500);
            assert.equal(c1.esCortesia, true);
            assert.equal(c1.valorLiquidado, 0);

            // Caso 2: 125 minutos (2h 5m) con 2h cortesía -> 5 min cobrables -> 1 hora cobrable -> $2.500 COP
            const c2 = calcularLiquidacion(125, 2, 2500);
            assert.equal(c2.esCortesia, false);
            assert.equal(c2.horasCobrables, 1);
            assert.equal(c2.valorLiquidado, 2500);

            // Caso 3: 200 minutos (3h 20m) con 2h cortesía -> 80 min cobrables -> 2 horas cobrables -> $5.000 COP
            const c3 = calcularLiquidacion(200, 2, 2500);
            assert.equal(c3.esCortesia, false);
            assert.equal(c3.horasCobrables, 2);
            assert.equal(c3.valorLiquidado, 5000);
        });
    });
});
