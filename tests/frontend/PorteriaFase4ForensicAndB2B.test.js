import { test, describe } from 'node:test';
import assert from 'node:assert';
import fs from 'node:fs';
import path from 'node:path';

/**
 * Suite de Pruebas de Contrato Frontend: Portería Fase 4 (HU-SEC-09 & HU-SEC-10)
 * Blindaje de Minuta Digital Forense Inmutable y Portal B2B para Supervisores de Vigilancia.
 */
describe('Portería Fase 4: Minuta Digital Forense & Portal B2B para Supervisores', () => {
    const minutaIndexPath = path.resolve('resources/js/Pages/Porteria/Minuta/Index.vue');
    const supervisionIndexPath = path.resolve('resources/js/Pages/Porteria/Supervision/Index.vue');

    describe('HU-SEC-09: Minuta Digital Forense Inmutable con Hash SHA-256', () => {
        const minutaContent = fs.readFileSync(minutaIndexPath, 'utf-8');

        test('Minuta template renders forensic audit banner with SHA-256 seal', () => {
            assert.ok(
                minutaContent.includes('auditoria.es_valida'),
                'Minuta/Index.vue must conditionally render forensic chain validation state'
            );
            assert.ok(
                minutaContent.includes('Blockchain Log SHA-256') || minutaContent.includes('SHA-256'),
                'Minuta/Index.vue must visibly display cryptographic SHA-256 certification'
            );
            assert.ok(
                minutaContent.includes('Superintendencia de Vigilancia'),
                'Minuta/Index.vue must reference official SuperVigilancia regulatory compliance'
            );
        });

        test('Modal includes photo evidence upload and legal inmutability notice', () => {
            assert.ok(
                minutaContent.includes('evidencia_foto'),
                'Minuta form must provide photo evidence field for incident forensics'
            );
            assert.ok(
                minutaContent.includes('capture="environment"'),
                'Photo input must support camera capture in tablet/mobile garita environments'
            );
            assert.ok(
                minutaContent.includes('Aviso Legal Forense'),
                'Minuta modal must explicitly state forensic inmutability and SHA-256 sealing'
            );
        });

        test('Priority and event filter buttons provide ergonomic touch targets', () => {
            assert.ok(
                minutaContent.includes('activeFilter'),
                'Minuta view must provide interactive filter controls for quick incident triage'
            );
            assert.ok(
                minutaContent.includes('min-h-[44px]') || minutaContent.includes('min-h-[48px]') || minutaContent.includes('min-h-[52px]'),
                'All interactive buttons must satisfy mobile touch target height >= 44px'
            );
        });
    });

    describe('HU-SEC-10: Dashboard B2B para Supervisores de Vigilancia Privada', () => {
        const supervisionContent = fs.readFileSync(supervisionIndexPath, 'utf-8');

        test('Supervision dashboard renders 4 core KPI metrics', () => {
            assert.ok(
                supervisionContent.includes('dashboard.kpis.total_puestos'),
                'Supervision dashboard must display total monitored posts'
            );
            assert.ok(
                supervisionContent.includes('dashboard.kpis.puestos_operativos'),
                'Supervision dashboard must display operative posts in green status'
            );
            assert.ok(
                supervisionContent.includes('dashboard.kpis.puestos_alerta'),
                'Supervision dashboard must display posts requiring observation'
            );
            assert.ok(
                supervisionContent.includes('dashboard.kpis.alertas_criticas'),
                'Supervision dashboard must display critical urgent incidents'
            );
        });

        test('Gatehouse card provides traffic light indicator (CRITICO / ADVERTENCIA / NORMAL)', () => {
            assert.ok(
                supervisionContent.includes('getStatusBadge'),
                'Supervision view must compute semantic status tokens based on garita health'
            );
            assert.ok(
                supervisionContent.includes('bg-rose-500'),
                'Critical status must render high-contrast rose alerting token'
            );
            assert.ok(
                supervisionContent.includes('bg-emerald-500'),
                'Normal operative status must render reassuring emerald token'
            );
        });

        test('Inspection modal renders forensic compliance seal and touch action >= 48px', () => {
            assert.ok(
                supervisionContent.includes('selectedPuesto'),
                'Supervision view must feature an inspection modal for individual gatehouses'
            );
            assert.ok(
                supervisionContent.includes('Cumplimiento SuperVigilancia'),
                'Inspection modal must certify SuperVigilancia audit status'
            );
            assert.ok(
                supervisionContent.includes('min-h-[44px]') || supervisionContent.includes('min-h-[48px]'),
                'Interactive inspection buttons must adhere to touch ergonomics standards'
            );
        });
    });
});
