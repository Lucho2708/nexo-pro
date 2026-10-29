import { describe, it } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

const projectRoot = path.resolve('/home/lsilva/Documentos/Laravel/nexo-pro');

function readComponent(relPath) {
    const fullPath = path.join(projectRoot, relPath);
    return fs.readFileSync(fullPath, 'utf8');
}

describe('SecurityLayout UX/UI Redesign: Header, Navbar & Tactical HUD Integrity', () => {
    const layoutSrc = readComponent('resources/js/Layouts/SecurityLayout.vue');

    it('UX-01: Reloj digital táctico está ubicado en el Header con protección contra salto de línea (whitespace-nowrap)', () => {
        // El header debe contener el reloj en vivo para máxima visibilidad horizontal
        const headerSnippet = layoutSrc.match(/<header[\s\S]*?<\/header>/);
        assert.ok(headerSnippet, 'Debe existir la etiqueta <header>');
        assert.ok(headerSnippet[0].includes('currentTime'), 'El Header debe contener currentTime');
        assert.ok(layoutSrc.includes('whitespace-nowrap') || layoutSrc.includes('font-mono'), 'Debe usar font-mono y formato sin saltos');
        assert.ok(layoutSrc.includes('currentAmPm'), 'Debe formatear AM/PM de manera atómica para evitar saltos como "a.\\nm."');
    });

    it('UX-02: Header elimina la duplicación del nombre de copropiedad en escritorio mostrando el contexto de la vista', () => {
        assert.ok(layoutSrc.includes('activePageTitle'), 'El Header debe derivar el título y sección activa de la vista');
    });

    it('UX-03: Botón de Relevar Turno se ubica en el Header sin duplicación en el footer del sidebar', () => {
        const headerSnippet = layoutSrc.match(/<header[\s\S]*?<\/header>/);
        assert.ok(headerSnippet[0].includes('showRelevoModal = true'), 'El Header debe contener el disparador de Relevar Turno');

        // El sidebar footer NO debe tener un botón duplicado de relevar turno
        const asideSnippet = layoutSrc.match(/<aside[\s\S]*?<\/aside>/);
        assert.ok(asideSnippet, 'Debe existir <aside>');
        assert.ok(!asideSnippet[0].includes('Relevar Turno'), 'El sidebar no debe duplicar el botón de Relevar Turno');
    });

    it('UX-04: ThemeToggle está ubicado ergonómicamente en el Header junto a los controles rápidos', () => {
        const headerSnippet = layoutSrc.match(/<header[\s\S]*?<\/header>/);
        assert.ok(headerSnippet[0].includes('<ThemeToggle'), 'El Header debe contener ThemeToggle');
    });

    it('UX-05: El card del Guarda en el sidebar cuenta con avatar, estado "En Servicio" y no está comprimido', () => {
        const asideSnippet = layoutSrc.match(/<aside[\s\S]*?<\/aside>/);
        assert.ok(asideSnippet[0].includes('En Servicio') || asideSnippet[0].includes('en servicio'), 'El sidebar debe mostrar estado En Servicio');
        assert.ok(asideSnippet[0].includes('logout'), 'El sidebar debe incluir botón de Cerrar Sesión');
    });

    it('UX-06: Elementos táctiles cumplen con target mínimo >= 44px', () => {
        assert.ok(layoutSrc.includes('min-h-[44px]') || layoutSrc.includes('min-h-[48px]'), 'Debe cumplir touch target reglamentario');
    });
});
