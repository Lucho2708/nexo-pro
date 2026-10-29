import { describe, it } from 'node:test';
import assert from 'node:assert/strict';

/**
 * Lógica pura del ecosistema de propietarios para pruebas unitarias de contrato
 */

export function resolveOwnerFinancialStatus(totalSaldo) {
    const isPazYSalvo = totalSaldo <= 0;
    return {
        isPazYSalvo,
        statusLabel: isPazYSalvo ? 'Paz y Salvo' : 'Saldo Pendiente',
        badgeVariant: isPazYSalvo ? 'success' : 'danger',
        ctaAction: isPazYSalvo ? 'certificate' : 'pay',
        ctaLabel: isPazYSalvo ? 'Ver Certificado' : 'Pagar Administración',
    };
}

export function formatCopCurrency(amount) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        maximumFractionDigits: 0
    }).format(amount || 0);
}

export function filterPqrsList(pqrsList, { status = 'all', type = 'all', search = '' }) {
    if (!Array.isArray(pqrsList)) return [];
    return pqrsList.filter(item => {
        const matchesStatus = status === 'all' || item.estado === status;
        const matchesType = type === 'all' || item.tipo === type;
        const matchesSearch = !search.trim() || 
            item.asunto?.toLowerCase().includes(search.toLowerCase()) ||
            String(item.id).includes(search);
        return matchesStatus && matchesType && matchesSearch;
    });
}

export function calculateReservationSlotTime(slot) {
    if (!slot || !slot.includes(':')) return { hora_inicio: '', hora_fin: '' };
    const [h, m] = slot.split(':');
    const startHour = parseInt(h, 10);
    const endHour = (startHour + 1) % 24;
    return {
        hora_inicio: `${String(startHour).padStart(2, '0')}:${m}`,
        hora_fin: `${String(endHour).padStart(2, '0')}:${m}`,
        duration_hours: 1
    };
}

export function resolveZoneFeeLabel(costo) {
    const numericCosto = parseFloat(costo) || 0;
    if (numericCosto <= 0) return 'Gratuito';
    return formatCopCurrency(numericCosto);
}

export function selectActiveUnitSpecs(unidades, selectedId) {
    if (!Array.isArray(unidades) || unidades.length === 0) return null;
    if (!selectedId) return unidades[0];
    return unidades.find(u => u.id === selectedId) || unidades[0];
}

describe('Owner Ecosystem Premium UX & Business Rules', () => {
    describe('Financial Balance & Paz y Salvo Status', () => {
        it('Happy Path: Saldo 0 genera estado Paz y Salvo con CTA de certificado', () => {
            const status = resolveOwnerFinancialStatus(0);
            assert.equal(status.isPazYSalvo, true);
            assert.equal(status.statusLabel, 'Paz y Salvo');
            assert.equal(status.badgeVariant, 'success');
            assert.equal(status.ctaAction, 'certificate');
        });

        it('Happy Path: Saldo positivo genera estado de Mora con CTA de pago', () => {
            const status = resolveOwnerFinancialStatus(185000);
            assert.equal(status.isPazYSalvo, false);
            assert.equal(status.statusLabel, 'Saldo Pendiente');
            assert.equal(status.badgeVariant, 'danger');
            assert.equal(status.ctaAction, 'pay');
        });

        it('Edge Case: Saldo negativo (a favor) se considera Paz y Salvo', () => {
            const status = resolveOwnerFinancialStatus(-25000);
            assert.equal(status.isPazYSalvo, true);
            assert.equal(status.statusLabel, 'Paz y Salvo');
        });
    });

    describe('Currency and Tariff Formatting', () => {
        it('Happy Path: Formatea peso colombiano COP sin decimales', () => {
            const formatted = formatCopCurrency(250000);
            assert.ok(formatted.includes('250.000') || formatted.includes('250,000') || formatted.includes('250'));
            assert.ok(formatted.includes('$'));
        });

        it('Happy Path: Identifica zona común gratuita cuando costo es 0', () => {
            assert.equal(resolveZoneFeeLabel(0), 'Gratuito');
            assert.equal(resolveZoneFeeLabel('0.00'), 'Gratuito');
        });

        it('Happy Path: Formatea tarifa cuando la zona tiene costo', () => {
            const fee = resolveZoneFeeLabel(50000);
            assert.ok(fee.includes('50.000') || fee.includes('50,000') || fee.includes('50'));
            assert.ok(fee.includes('$'));
        });
    });

    describe('Reservations Slot Computation', () => {
        it('Happy Path: Calcula hora fin sumando exactamente 1 hora', () => {
            const slot = calculateReservationSlotTime('14:00');
            assert.equal(slot.hora_inicio, '14:00');
            assert.equal(slot.hora_fin, '15:00');
            assert.equal(slot.duration_hours, 1);
        });

        it('Edge Case: Trata slots nulos o malformados defensivamente', () => {
            const slot = calculateReservationSlotTime('');
            assert.equal(slot.hora_inicio, '');
            assert.equal(slot.hora_fin, '');
        });
    });

    describe('Multi-Unit Selection for Owner', () => {
        const mockUnits = [
            { id: 'u-1', nombre: '101', torre: 'Torre A', saldo_actual: 0 },
            { id: 'u-2', nombre: '102', torre: 'Torre A', saldo_actual: 150000 },
        ];

        it('Happy Path: Selecciona la primera unidad por defecto si no hay id', () => {
            const active = selectActiveUnitSpecs(mockUnits, null);
            assert.equal(active.id, 'u-1');
            assert.equal(active.nombre, '101');
        });

        it('Happy Path: Cambia reactivamente a la segunda unidad si el usuario la selecciona', () => {
            const active = selectActiveUnitSpecs(mockUnits, 'u-2');
            assert.equal(active.id, 'u-2');
            assert.equal(active.nombre, '102');
            assert.equal(active.saldo_actual, 150000);
        });

        it('Edge Case: Maneja array de unidades vacío sin explotar', () => {
            const active = selectActiveUnitSpecs([], 'u-1');
            assert.equal(active, null);
        });
    });

    describe('PQRS Search and Filter Engine', () => {
        const mockPqrs = [
            { id: 101, asunto: 'Filtración en balcón', estado: 'abierto', tipo: 'reclamo' },
            { id: 102, asunto: 'Copia del reglamento', estado: 'cerrado', tipo: 'peticion' },
            { id: 103, asunto: 'Ruido excesivo piso 3', estado: 'en_proceso', tipo: 'queja' },
        ];

        it('Happy Path: Filtra por estado correctamente', () => {
            const abiertos = filterPqrsList(mockPqrs, { status: 'abierto' });
            assert.equal(abiertos.length, 1);
            assert.equal(abiertos[0].id, 101);
        });

        it('Happy Path: Filtra por búsqueda de texto en asunto', () => {
            const results = filterPqrsList(mockPqrs, { search: 'reglamento' });
            assert.equal(results.length, 1);
            assert.equal(results[0].id, 102);
        });

        it('Happy Path: Filtra por ID de radicado', () => {
            const results = filterPqrsList(mockPqrs, { search: '103' });
            assert.equal(results.length, 1);
            assert.equal(results[0].id, 103);
        });
    });
});
