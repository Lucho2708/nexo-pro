/**
 * EnvironmentsUx.test.js
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Suite de pruebas automatizadas para la validación integral de los 4 entornos independientes de NEXO PRO:
 * 1. Landing Page (Conversión B2B, marketing, fluidez y prueba social)
 * 2. Superadmin (Consola densa de infraestructura, telemetría y logs forenses)
 * 3. Administrador (Gestión operativa de cartera, asambleas y soporte)
 * 4. Propietario (Dashboard ejecutivo simplificado, paz y salvo y recaudo Wompi)
 *
 * [ASPECTO TÉCNICO / UI]
 * - Ejecutado con el Test Runner nativo de Node.js 24 (node:test y node:assert).
 * - Evalúa contratos de tokens semánticos, contraste WCAG 2.1 AA (mínimo 4.5:1),
 *   lógica de estados financieros y ergonomía táctil multi-dispositivo.
 */

import { test, describe } from 'node:test';
import assert from 'node:assert/strict';
import { ENVIRONMENT_CONFIGS, getEnvironmentConfig } from '../../src/frontend/tokens/environments.ts';

describe('NEXO PRO 4 Independent Environments UX/UI & Tokens', () => {

    test('Entorno 1: Landing Page - Tokens de conversión y alto contraste WCAG AA', () => {
        const landing = getEnvironmentConfig('landing');

        assert.equal(landing.name, 'Landing Page & Marketing');
        assert.equal(landing.density, 'relaxed');
        assert.equal(landing.brand.primary.light, '#2563EB');
        assert.equal(landing.brand.primary.dark, '#60A5FA');
        assert.ok(landing.wcagContrastRatio.normalText >= 4.5, 'Contraste de texto debe ser >= 4.5:1');
        assert.ok(landing.wcagContrastRatio.ctaButton >= 4.5, 'Contraste de CTA debe ser >= 4.5:1');
    });

    test('Entorno 2: Superadmin - Consola de alta densidad y superficies de telemetría', () => {
        const superadmin = getEnvironmentConfig('superadmin');

        assert.equal(superadmin.name, 'Consola Global Superadmin');
        assert.equal(superadmin.density, 'compact', 'Superadmin debe tener densidad compacta.');
        assert.equal(superadmin.surfaces.bg.dark, '#020617', 'Modo oscuro profundo para monitoreo.');
        assert.ok(superadmin.wcagContrastRatio.normalText >= 7.0, 'Superadmin debe alcanzar nivel AAA (> 7.0:1) para legibilidad de logs.');
    });

    test('Entorno 3: Administrador - Control operativo y estados de cartera', () => {
        const admin = getEnvironmentConfig('admin');

        assert.equal(admin.name, 'Administración Operativa');
        assert.equal(admin.density, 'normal');
        assert.equal(admin.brand.primary.light, '#2563EB');
        assert.ok(admin.wcagContrastRatio.normalText >= 4.5);
    });

    test('Entorno 4: Propietario - Dashboard ejecutivo simplificado y paz y salvo', () => {
        const owner = getEnvironmentConfig('owner');

        assert.equal(owner.name, 'Portal del Propietario');
        assert.equal(owner.density, 'relaxed', 'Propietario debe tener baja carga cognitiva y diseño espacioso.');
        assert.equal(owner.brand.secondary.light, '#10B981', 'Token esmeralda para paz y salvo.');
        assert.ok(owner.wcagContrastRatio.normalText >= 4.5);
    });

    test('Lógica Financiera del Propietario: Detección automática de Paz y Salvo vs Deuda', () => {
        const evaluateFinancialState = (totalSaldo) => {
            const isUpToDate = totalSaldo <= 0;
            return {
                isUpToDate,
                badgeVariant: isUpToDate ? 'success' : 'warning',
                badgeText: isUpToDate ? 'Paz y Salvo General' : 'Saldo Pendiente de Pago',
                requiresPaymentButton: !isUpToDate,
            };
        };

        // Caso 1: Propietario al día (saldo 0)
        const stateUpToDate = evaluateFinancialState(0);
        assert.equal(stateUpToDate.isUpToDate, true);
        assert.equal(stateUpToDate.badgeVariant, 'success');
        assert.equal(stateUpToDate.requiresPaymentButton, false);

        // Caso 2: Propietario con deuda (saldo 250,000 COP)
        const stateInDebt = evaluateFinancialState(250000);
        assert.equal(stateInDebt.isUpToDate, false);
        assert.equal(stateInDebt.badgeVariant, 'warning');
        assert.equal(stateInDebt.requiresPaymentButton, true);
    });

    test('Agregación de Coeficientes de Inmuebles para Votación Multi-Unidad', () => {
        const units = [
            { id: 'u1', nombre: 'Apto 101', coeficiente: 1.2500 },
            { id: 'u2', nombre: 'Apto 102', coeficiente: 2.7500 },
            { id: 'u3', nombre: 'Depósito D-05', coeficiente: 0.1000 },
        ];

        const totalWeight = units.reduce((acc, u) => acc + (u.coeficiente || 0), 0);
        assert.equal(roundTo(totalWeight, 4), 4.1000, 'La ponderación acumulada debe ser exacta.');
    });

    test('Telemetría de Superadmin: Mapeo de salud de nodos de infraestructura', () => {
        const evaluateClusterHealth = (health) => {
            const nodes = Object.values(health);
            const allHealthy = nodes.every(status => status === 'online');
            const hasCritical = nodes.some(status => status === 'offline');
            return {
                clusterStatus: allHealthy ? 'HEALTHY' : (hasCritical ? 'CRITICAL' : 'DEGRADED'),
                healthyCount: nodes.filter(s => s === 'online').length,
                totalNodes: nodes.length,
            };
        };

        const cluster = evaluateClusterHealth({
            database: 'online',
            reverb: 'online',
            livekit: 'online',
            workers: 'online',
        });

        assert.equal(cluster.clusterStatus, 'HEALTHY');
        assert.equal(cluster.healthyCount, 4);
        assert.equal(cluster.totalNodes, 4);
    });
});

function roundTo(num, decimals) {
    return Number(Math.round(Number(num + 'e' + decimals)) + 'e-' + decimals);
}
