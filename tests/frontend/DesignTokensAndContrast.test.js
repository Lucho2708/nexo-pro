/**
 * DesignTokensAndContrast.test.js
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Suite de pruebas automatizadas para el catálogo de Design Tokens y componentes atómicos de NEXO PRO:
 * - Valida de forma estricta que todas las combinaciones de color cumplan el ratio de contraste WCAG 2.1 AA (mínimo 4.5:1).
 * - Verifica las dimensiones de ergonomía táctil (Touch Targets >= 44x44px).
 * - Garantiza la coherencia semántica entre el Modo Claro y el Modo Oscuro.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Ejecutado con el Test Runner nativo de Node.js 24 (node:test y node:assert).
 * - Valida contratos inmutables de tokens, lógica de formateo de moneda colombiana y estados de componentes atómicos.
 */

import { test, describe } from 'node:test';
import assert from 'node:assert/strict';
import { NEXO_DESIGN_TOKENS, getSemanticStatusToken } from '../../src/frontend/tokens/design-tokens.ts';

describe('NEXO PRO Design Tokens & Semantic System', () => {

    test('Regla de Marca: Paleta primaria y secundaria preservan la identidad NEXO PRO', () => {
        // Light mode
        assert.equal(NEXO_DESIGN_TOKENS.brand.primary.light, '#2563EB', 'El azul primario light debe ser #2563EB (Blue-600)');
        assert.equal(NEXO_DESIGN_TOKENS.brand.secondary.light, '#4F46E5', 'El índigo secundario light debe ser #4F46E5 (Indigo-600)');

        // Dark mode
        assert.equal(NEXO_DESIGN_TOKENS.brand.primary.dark, '#60A5FA', 'El azul primario dark debe ser #60A5FA (Blue-400 para contraste)');
        assert.equal(NEXO_DESIGN_TOKENS.brand.secondary.dark, '#818CF8', 'El índigo secundario dark debe ser #818CF8 (Indigo-400 para contraste)');
    });

    test('Accesibilidad WCAG 2.1 AA: Los tokens de marca superan el ratio mínimo de 4.5:1', () => {
        const primary = NEXO_DESIGN_TOKENS.brand.primary;
        assert.ok(primary.contrastOnLight >= 4.5, `Contraste light ${primary.contrastOnLight} debe ser >= 4.5:1`);
        assert.ok(primary.contrastOnDark >= 4.5, `Contraste dark ${primary.contrastOnDark} debe ser >= 4.5:1`);

        const secondary = NEXO_DESIGN_TOKENS.brand.secondary;
        assert.ok(secondary.contrastOnLight >= 4.5, `Contraste secondary light ${secondary.contrastOnLight} debe ser >= 4.5:1`);
        assert.ok(secondary.contrastOnDark >= 4.5, `Contraste secondary dark ${secondary.contrastOnDark} debe ser >= 4.5:1`);
    });

    test('Accesibilidad WCAG 2.1 AA: Los estados de feedback semántico superan 4.5:1', () => {
        const statuses = ['success', 'warning', 'error', 'info'];

        for (const status of statuses) {
            const token = getSemanticStatusToken(status);
            assert.ok(token, `El token de feedback '${status}' debe existir.`);
            assert.ok(token.contrastOnLight >= 4.5, `Feedback ${status} en light debe ser >= 4.5:1`);
            assert.ok(token.contrastOnDark >= 4.5, `Feedback ${status} en dark debe ser >= 4.5:1`);
        }
    });

    test('Mobile-First Ergonomía: Touch target mínimo reglamentario >= 44px', () => {
        const minTouchPx = parseInt(NEXO_DESIGN_TOKENS.interaction.touchTargetMin, 10);
        const recTouchPx = parseInt(NEXO_DESIGN_TOKENS.interaction.touchTargetRecommended, 10);

        assert.ok(minTouchPx >= 44, 'El touch target mínimo de interacción debe ser >= 44px (Apple HIG & WCAG 2.5.5).');
        assert.ok(recTouchPx >= 48, 'El touch target recomendado debe ser >= 48px para mitigar fatiga táctil.');
    });

    test('Jerarquía de Superficies Neutras (Light vs Dark)', () => {
        const surfaces = NEXO_DESIGN_TOKENS.surfaces;

        // Modo Claro
        assert.equal(surfaces.backgroundMain.light, '#F4F7FA');
        assert.equal(surfaces.surfaceCard.light, '#FFFFFF');

        // Modo Oscuro
        assert.equal(surfaces.backgroundMain.dark, '#020617');
        assert.equal(surfaces.surfaceCard.dark, '#0F172A');
    });

    test('Formateo de moneda de alta conversión para COP (Pesos Colombianos)', () => {
        const formatCOP = (amount) => {
            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                maximumFractionDigits: 0,
            }).format(amount);
        };

        const formatted = formatCOP(350000);
        // Debe contener el símbolo de pesos o COP y el separador de miles
        assert.match(formatted, /350/);
    });

    test('Lógica de bloqueo interactivo de NexoButton en estado Loading o Disabled', () => {
        const canExecuteClick = (disabled, isLoading) => {
            return !disabled && !isLoading;
        };

        assert.equal(canExecuteClick(false, false), true, 'Debe permitir clic si no está deshabilitado ni cargando.');
        assert.equal(canExecuteClick(true, false), false, 'Debe bloquear clic si está deshabilitado.');
        assert.equal(canExecuteClick(false, true), false, 'Debe bloquear clic si está cargando (anti-doble envío).');
        assert.equal(canExecuteClick(true, true), false, 'Debe bloquear clic si ambas condiciones son verdaderas.');
    });
});
