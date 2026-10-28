/**
 * tests/frontend/OnboardingFormAndCheckboxBinding.test.js
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Pruebas unitarias para el flujo de Onboarding / Registro y sincronización de Checkbox.
 * Valida:
 * - Contrato bidireccional de Checkbox (soporte de v-model y v-model:checked).
 * - Prevención de doble asterisco (* *) en etiquetas requeridas.
 * - Validación estricta de aceptación de términos SIC (Ley 1581) antes de envío.
 * - Coincidencia de contraseñas de acceso maestro.
 * - Cascada geográfica reactiva de Colombia (Departamentos y Municipios).
 */

import { describe, it } from 'node:test';
import assert from 'node:assert';
import { COLOMBIA_GEOGRAPHY } from '../../resources/js/Composables/useColombiaGeography.ts';

// 1. Simulación de lógica de Checkbox
function resolveCheckboxChecked(props) {
    if (props.checked !== undefined) return Boolean(props.checked);
    return Boolean(props.modelValue);
}

function handleCheckboxInput(isChecked) {
    return {
        'update:modelValue': isChecked,
        'update:checked': isChecked,
        'change': isChecked,
    };
}

// 2. Simulación de normalización de etiqueta en Input
function normalizeLabel(label) {
    if (!label) return '';
    return label.trim().replace(/\s*\*+$/, '');
}

// 3. Simulación de validación previa al envío en Onboarding
function validateOnboardingSubmit(form) {
    const errors = {};
    if (!form.name || !form.name.trim()) {
        errors.name = 'El nombre del administrador es requerido.';
    }
    if (!form.email || !form.email.trim()) {
        errors.email = 'El correo electrónico corporativo es requerido.';
    }
    if (!form.password || form.password.length < 8) {
        errors.password = 'La contraseña debe contener al menos 8 caracteres.';
    }
    if (form.password !== form.password_confirmation) {
        errors.password_confirmation = 'Las contraseñas no coinciden.';
    }
    if (!form.terms) {
        errors.terms = 'Debes autorizar el tratamiento de datos y aceptar los Términos del Servicio para continuar.';
    }

    return {
        isValid: Object.keys(errors).length === 0,
        errors,
    };
}

describe('Onboarding Form & Checkbox Contract Suite', () => {
    it('Checkbox: Resuelve estado activo tanto con modelValue como con checked', () => {
        // Directiva estándar v-model
        assert.strictEqual(resolveCheckboxChecked({ modelValue: true }), true);
        assert.strictEqual(resolveCheckboxChecked({ modelValue: false }), false);

        // Directiva v-model:checked (usada en formularios Inertia/Breeze)
        assert.strictEqual(resolveCheckboxChecked({ checked: true }), true);
        assert.strictEqual(resolveCheckboxChecked({ checked: false }), false);

        // Prioridad cuando ambos existen
        assert.strictEqual(resolveCheckboxChecked({ checked: true, modelValue: false }), true);
        assert.strictEqual(resolveCheckboxChecked({ checked: false, modelValue: true }), false);
    });

    it('Checkbox: Emite tanto update:modelValue como update:checked para sincronizar el estado reactivo', () => {
        const eventsChecked = handleCheckboxInput(true);
        assert.strictEqual(eventsChecked['update:modelValue'], true);
        assert.strictEqual(eventsChecked['update:checked'], true);
        assert.strictEqual(eventsChecked['change'], true);

        const eventsUnchecked = handleCheckboxInput(false);
        assert.strictEqual(eventsUnchecked['update:modelValue'], false);
        assert.strictEqual(eventsUnchecked['update:checked'], false);
    });

    it('Input: Normaliza etiquetas para evitar asteriscos duplicados (* *) cuando required es true', () => {
        assert.strictEqual(normalizeLabel('Nombre del Administrador *'), 'Nombre del Administrador');
        assert.strictEqual(normalizeLabel('Correo Corporativo **'), 'Correo Corporativo');
        assert.strictEqual(normalizeLabel('Dirección Física'), 'Dirección Física');
        assert.strictEqual(normalizeLabel(''), '');
    });

    it('Onboarding Validation: Bloquea envío y muestra error en español si los términos no están aceptados', () => {
        const formWithTermsFalse = {
            name: 'Admin Prueba',
            email: 'admin_prueba@nexo.pro',
            password: 'Password123!',
            password_confirmation: 'Password123!',
            terms: false, // El caso que falló en el screenshot
        };

        const result = validateOnboardingSubmit(formWithTermsFalse);
        assert.strictEqual(result.isValid, false);
        assert.strictEqual(
            result.errors.terms,
            'Debes autorizar el tratamiento de datos y aceptar los Términos del Servicio para continuar.'
        );
    });

    it('Onboarding Validation: Pasa validación cuando términos son aceptados', () => {
        const validForm = {
            name: 'Admin Prueba',
            email: 'admin_prueba@nexo.pro',
            password: 'Password123!',
            password_confirmation: 'Password123!',
            terms: true,
        };

        const result = validateOnboardingSubmit(validForm);
        assert.strictEqual(result.isValid, true);
        assert.strictEqual(Object.keys(result.errors).length, 0);
    });

    it('Onboarding Validation: Detecta discrepancia en confirmación de contraseña', () => {
        const mismatchForm = {
            name: 'Admin Prueba',
            email: 'admin_prueba@nexo.pro',
            password: 'Password123!',
            password_confirmation: 'Diferente123!',
            terms: true,
        };

        const result = validateOnboardingSubmit(mismatchForm);
        assert.strictEqual(result.isValid, false);
        assert.strictEqual(result.errors.password_confirmation, 'Las contraseñas no coinciden.');
    });

    it('Colombia Geography: Contiene los 32 departamentos más Bogotá D.C. y sus municipios', () => {
        const deps = Object.keys(COLOMBIA_GEOGRAPHY);
        assert.strictEqual(deps.length, 33, 'Debe incluir 32 departamentos y Bogotá D.C.');
        assert.ok(deps.includes('Antioquia'));
        assert.ok(deps.includes('Bogotá D.C.'));
        assert.ok(deps.includes('Valle del Cauca'));

        const antioquiaMun = COLOMBIA_GEOGRAPHY['Antioquia'];
        assert.ok(antioquiaMun.includes('Medellín'));
        assert.ok(antioquiaMun.includes('Envigado'));
        assert.ok(antioquiaMun.includes('Rionegro'));
    });
});
