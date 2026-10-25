import { describe, it } from 'node:test';
import assert from 'node:assert/strict';

/**
 * Normaliza el nombre de la unidad residencial eliminando duplicaciones
 * accidentales como "Torre Torre A - 401".
 */
export function formatUnitDisplayName(torre, nombre) {
    if (!torre || !torre.trim()) return nombre;
    const cleanTorre = torre.trim();
    const prefix = /^torre\b/i.test(cleanTorre) ? '' : 'Torre ';
    return `${prefix}${cleanTorre} - ${nombre}`;
}

/**
 * Deriva la acción o insignia contextual del encabezado de propietario
 * garantizando cero duplicación de iconos de navegación global (campana y avatar).
 */
export function resolveOwnerHeaderContext(totalSaldo) {
    if (totalSaldo > 0) {
        return {
            type: 'cta',
            label: 'Pagar Expensas',
            action: 'handlePayment',
            variant: 'primary',
            badgeClass: 'bg-primary text-white'
        };
    }
    return {
        type: 'badge',
        label: 'Al Día / Paz y Salvo',
        action: null,
        variant: 'success',
        badgeClass: 'bg-emerald-500/10 text-emerald-600'
    };
}

/**
 * Genera la URL del avatar de usuario con color de marca determinista (Nexo Blue)
 * evitando fondos aleatorios ('background=random') que distorsionan la identidad visual.
 */
export function buildDeterministicAvatarUrl(userName, brandBg = '2563eb') {
    const encoded = encodeURIComponent(userName || 'Usuario');
    return `https://ui-avatars.com/api/?name=${encoded}&background=${brandBg}&color=fff&bold=true`;
}

describe('Owner Portal Polish & Anti-Duplication Unit Tests', () => {
    describe('formatUnitDisplayName', () => {
        it('Happy Path: Corrige "Torre A" para evitar duplicar prefijo "Torre Torre A"', () => {
            const formatted = formatUnitDisplayName('Torre A', '401');
            assert.equal(formatted, 'Torre A - 401');
            assert.ok(!formatted.startsWith('Torre Torre'));
        });

        it('Happy Path: Añade prefijo "Torre " si solo viene la letra/número (ej. "B")', () => {
            const formatted = formatUnitDisplayName('B', '202');
            assert.equal(formatted, 'Torre B - 202');
        });

        it('Edge Case: Maneja torre null o vacía retornando solo el identificador del inmueble', () => {
            assert.equal(formatUnitDisplayName(null, 'Casa 15'), 'Casa 15');
            assert.equal(formatUnitDisplayName('   ', 'Lote 4'), 'Lote 4');
        });
    });

    describe('resolveOwnerHeaderContext', () => {
        it('Happy Path: Saldo pendiente genera CTA de pago en 1 clic', () => {
            const context = resolveOwnerHeaderContext(189000);
            assert.equal(context.type, 'cta');
            assert.equal(context.label, 'Pagar Expensas');
            assert.equal(context.variant, 'primary');
        });

        it('Happy Path: Saldo en cero genera insignia de Paz y Salvo', () => {
            const context = resolveOwnerHeaderContext(0);
            assert.equal(context.type, 'badge');
            assert.equal(context.label, 'Al Día / Paz y Salvo');
            assert.equal(context.variant, 'success');
        });
    });

    describe('buildDeterministicAvatarUrl', () => {
        it('Happy Path: Emplea color de marca NEXO PRO (#2563EB) y tipografía bold', () => {
            const url = buildDeterministicAvatarUrl('Propietario Torre A 401');
            assert.ok(url.includes('background=2563eb'));
            assert.ok(url.includes('color=fff'));
            assert.ok(!url.includes('background=random'));
        });

        it('Edge Case: Tolera nombres nulos o vacíos con fallback accesible', () => {
            const url = buildDeterministicAvatarUrl('');
            assert.ok(url.includes('name=Usuario'));
        });
    });
});
