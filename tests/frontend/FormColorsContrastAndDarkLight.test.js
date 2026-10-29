import { describe, it } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

const projectRoot = path.resolve('/home/lsilva/Documentos/Laravel/nexo-pro');

function readComponent(relPath) {
    const fullPath = path.join(projectRoot, relPath);
    return fs.readFileSync(fullPath, 'utf8');
}

describe('Form Modals Color Contrast, WCAG 2.1 AA & Dark/Light Mode Integrity', () => {

    describe('Modal.vue Container Theme & Responsiveness', () => {
        const modalSrc = readComponent('resources/js/Components/UI/Modal.vue');

        it('C-01: Modal Container soporta modo claro y oscuro sin forzar fondo blanco fijo', () => {
            assert.ok(modalSrc.includes('bg-white dark:bg-[#0B132B]') || modalSrc.includes('bg-white dark:bg-slate-900'), 
                'Modal debe alternar bg-white en claro y dark:bg-[#0B132B] o dark:bg-slate-900 en oscuro');
            assert.ok(!modalSrc.includes('.bg-white { background-color: rgba(255, 255, 255, 0.95);'),
                'No debe tener override scoped que fuerce fondo blanco en modo oscuro');
        });

        it('C-02: Modal scrollbar cuenta con estilos para modo oscuro', () => {
            assert.ok(modalSrc.includes(':global(.dark) .custom-scrollbar::-webkit-scrollbar-thumb'),
                'Scrollbar debe definir thumb para modo oscuro');
        });

        it('C-03: CloseButton de Modal.vue cuenta con contraste visible en ambos modos', () => {
            assert.ok(modalSrc.includes('text-slate-600 dark:text-slate-300'),
                'CloseButton debe tener text-slate-600 en light y dark:text-slate-300 en dark');
        });
    });

    describe('Paquetes/Index.vue Modal Form Contrast & Single Close Button', () => {
        const paquetesSrc = readComponent('resources/js/Pages/Porteria/Paquetes/Index.vue');

        it('C-04: Título de Encomienda tiene alto contraste (text-slate-900 dark:text-white)', () => {
            assert.ok(paquetesSrc.includes('text-slate-900 dark:text-white'),
                'El título del modal debe usar text-slate-900 en light y dark:text-white en dark');
        });

        it('C-05: Subtítulo de categoría de encomienda usa contraste AA (amber-600 en light / amber-400 en dark)', () => {
            assert.ok(paquetesSrc.includes('text-amber-600 dark:text-amber-400') || paquetesSrc.includes('text-amber-500 dark:text-amber-400'),
                'Debe usar amber de alto contraste');
        });

        it('C-06: Labels de formulario usan text-slate-700 dark:text-slate-300 para contraste >= 8:1', () => {
            assert.ok(paquetesSrc.includes('text-slate-700 dark:text-slate-300'),
                'Labels deben usar text-slate-700 dark:text-slate-300');
        });

        it('C-07: Inputs y selects definen fondo y borde adaptativo para Light y Dark', () => {
            assert.ok(paquetesSrc.includes('bg-slate-50 dark:bg-[#030712]') || paquetesSrc.includes('bg-slate-50 dark:bg-slate-950'),
                'Inputs deben tener bg-slate-50 en light y fondo oscuro en dark');
            assert.ok(paquetesSrc.includes('border-slate-300 dark:border-slate-700'),
                'Inputs deben tener border-slate-300 en light y dark:border-slate-700 en dark');
            assert.ok(paquetesSrc.includes('text-slate-900 dark:text-white'),
                'Inputs deben tener texto text-slate-900 dark:text-white');
        });

        it('C-08: No existe botón de cerrar duplicado en el header del modal de encomienda', () => {
            // No debe haber un <button @click="isModalOpen = false" en el encabezado si Modal ya tiene su propio CloseButton
            const formHeaderSnippet = paquetesSrc.match(/<form @submit\.prevent="submitPaquete"[\s\S]*?<\/div>[\s\S]*?<\/div>/);
            assert.ok(formHeaderSnippet, 'Debe encontrarse el encabezado de submitPaquete');
            assert.ok(!formHeaderSnippet[0].includes('material-symbols-rounded">close<'),
                'No debe haber botón duplicado de close en el encabezado interno del modal');
        });
    });

    describe('Parqueaderos/Index.vue Modal Form Contrast', () => {
        const parqueaderosSrc = readComponent('resources/js/Pages/Porteria/Parqueaderos/Index.vue');

        it('C-09: Título y categoría en modal de ingreso vehicular tienen alto contraste', () => {
            assert.ok(parqueaderosSrc.includes('text-slate-900 dark:text-white'),
                'Título de ingreso vehicular debe ser text-slate-900 dark:text-white');
            assert.ok(parqueaderosSrc.includes('text-emerald-600 dark:text-emerald-400') || parqueaderosSrc.includes('text-emerald-500 dark:text-emerald-400'),
                'Categoría debe ser de alto contraste');
        });

        it('C-10: Labels e inputs de parqueaderos cumplen estándar de color slate', () => {
            assert.ok(parqueaderosSrc.includes('text-slate-700 dark:text-slate-300'),
                'Labels de parqueadero deben usar text-slate-700 dark:text-slate-300');
            assert.ok(parqueaderosSrc.includes('bg-slate-50 dark:bg-[#030712]') || parqueaderosSrc.includes('bg-slate-50 dark:bg-slate-950'),
                'Inputs de parqueadero deben usar bg-slate-50 dark:bg-[#030712]');
        });
    });

    describe('Minuta/Index.vue Modal Form & Legal Forensic Notice Contrast', () => {
        const minutaSrc = readComponent('resources/js/Pages/Porteria/Minuta/Index.vue');

        it('C-11: Título de minuta forense usa text-slate-900 dark:text-white', () => {
            assert.ok(minutaSrc.includes('text-slate-900 dark:text-white'),
                'Título de minuta debe ser text-slate-900 dark:text-white');
        });

        it('C-12: Labels e inputs de minuta usan tokens de alto contraste', () => {
            assert.ok(minutaSrc.includes('text-slate-700 dark:text-slate-300'),
                'Labels de minuta deben usar text-slate-700 dark:text-slate-300');
            assert.ok(minutaSrc.includes('bg-slate-50 dark:bg-[#030712]') || minutaSrc.includes('bg-slate-50 dark:bg-slate-950'),
                'Inputs de minuta deben usar bg-slate-50 dark:bg-[#030712]');
        });

        it('C-13: Aviso Legal Forense es 100% legible en Light y Dark (no blanco sobre blanco)', () => {
            assert.ok(minutaSrc.includes('bg-blue-50/90 dark:bg-blue-950/40') || minutaSrc.includes('bg-blue-50 dark:bg-blue-950/40'),
                'Aviso legal debe tener fondo azul suave en light y oscuro en dark');
            assert.ok(minutaSrc.includes('text-blue-950 dark:text-blue-200'),
                'Texto del aviso legal debe tener alto contraste (text-blue-950 en light, dark:text-blue-200 en dark)');
            assert.ok(minutaSrc.includes('text-blue-900 dark:text-blue-100'),
                'Strong de aviso legal debe ser visible en ambos modos');
        });
    });

    describe('Visitas/Index.vue Modal Form Contrast & Single Close Button', () => {
        const visitasSrc = readComponent('resources/js/Pages/Porteria/Visitas/Index.vue');

        it('C-14: Título de registro de visita usa text-slate-900 dark:text-white', () => {
            assert.ok(visitasSrc.includes('text-slate-900 dark:text-white'),
                'Título de visitas debe ser text-slate-900 dark:text-white');
        });

        it('C-15: Labels e inputs de visitas cumplen estándar de contraste', () => {
            assert.ok(visitasSrc.includes('text-slate-700 dark:text-slate-300'),
                'Labels de visitas deben ser text-slate-700 dark:text-slate-300');
            assert.ok(visitasSrc.includes('bg-slate-50 dark:bg-[#030712]') || visitasSrc.includes('bg-slate-50 dark:bg-slate-950'),
                'Inputs de visitas deben ser bg-slate-50 dark:bg-[#030712]');
        });

        it('C-16: Lector de cédula PDF417 cuenta con contraste de texto legible en ambos modos', () => {
            assert.ok(visitasSrc.includes('bg-blue-50/90 dark:bg-blue-950/40') || visitasSrc.includes('bg-blue-50/80 dark:bg-blue-950/30') || visitasSrc.includes('bg-blue-50 dark:bg-blue-950/40'),
                'Contenedor del lector de cédula debe tener fondo accesible');
        });

        it('C-17: Modal de visitas no tiene botón de cerrar duplicado en el encabezado interno', () => {
            const formHeaderSnippet = visitasSrc.match(/<form @submit\.prevent="submitIngreso"[\s\S]*?<\/div>[\s\S]*?<\/div>/);
            assert.ok(formHeaderSnippet, 'Debe encontrarse el encabezado de submitIngreso');
            assert.ok(!formHeaderSnippet[0].includes('material-symbols-rounded">close<'),
                'No debe haber botón duplicado de close en el encabezado interno del modal');
        });
    });

    describe('Select.vue & DatePicker.vue Contrast Standards', () => {
        const selectSrc = readComponent('resources/js/Components/UI/Select.vue');
        const datePickerSrc = readComponent('resources/js/Components/UI/DatePicker.vue');

        it('C-18: Select.vue labels usan text-slate-700 dark:text-slate-300 en lugar de opacidades bajas', () => {
            assert.ok(selectSrc.includes('text-slate-700 dark:text-slate-300'),
                'Select.vue label debe usar text-slate-700 dark:text-slate-300');
        });

        it('C-19: DatePicker.vue labels usan text-slate-700 dark:text-slate-300', () => {
            assert.ok(datePickerSrc.includes('text-slate-700 dark:text-slate-300'),
                'DatePicker.vue label debe usar text-slate-700 dark:text-slate-300');
        });
    });
});
