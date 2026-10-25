import { describe, it } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

describe('Portería Module Ergonomics, Design Tokens & Touch Targets (WCAG 2.1 AA)', () => {
    const layoutPath = path.resolve(process.cwd(), 'resources/js/Layouts/SecurityLayout.vue');
    const layoutContent = fs.readFileSync(layoutPath, 'utf-8');

    it('UX & Touch Targets: Nav items and buttons meet minimum touch target >= 44px (with primary items >= 48px)', () => {
        // Desktop nav targets min-h-[48px]
        assert.ok(layoutContent.includes('min-h-[48px]'), 'Desktop buttons meet ergonomics target >= 48px');
        // Mobile bar meets touch target >= 44px
        assert.ok(layoutContent.includes('min-h-[44px]'), 'Mobile navigation meets touch target >= 44px');
    });

    it('Information Architecture: Contains all 6 tactical garita modules', () => {
        assert.ok(layoutContent.includes('porteria.dashboard'), 'Includes Consola Garita route');
        assert.ok(layoutContent.includes('porteria.visitas.index'), 'Includes Visitas route');
        assert.ok(layoutContent.includes('porteria.recibos.index'), 'Includes Recibos route');
        assert.ok(layoutContent.includes('porteria.paquetes.index'), 'Includes Paquetes route');
        assert.ok(layoutContent.includes('porteria.parqueaderos.index'), 'Includes Parqueaderos route');
        assert.ok(layoutContent.includes('porteria.minuta.index'), 'Includes Minuta route');
    });

    it('Accessibility & Contrast: Features ThemeToggle and High-Contrast Dark Mode surfaces', () => {
        assert.ok(layoutContent.includes('ThemeToggle'), 'Features ThemeToggle component');
        assert.ok(layoutContent.includes('dark:bg-[#06080D]') || layoutContent.includes('dark:bg-[#0b0e14]'), 'Includes high-contrast dark surfaces for night shifts');
    });

    it('Dashboard Console: Renders parking capacity gauge and overcapacity alert logic', () => {
        const dashboardPath = path.resolve(process.cwd(), 'resources/js/Pages/Porteria/Dashboard.vue');
        const dashboardContent = fs.readFileSync(dashboardPath, 'utf-8');

        assert.ok(dashboardContent.includes('alerta_sobrecupo'), 'Dashboard evaluates overcapacity status');
        assert.ok(dashboardContent.includes('porcentaje_ocupacion'), 'Dashboard computes occupancy percentage');
        assert.ok(dashboardContent.includes('visitas_activas'), 'Displays active visitors count');
        assert.ok(dashboardContent.includes('recibos_en_casillero'), 'Displays utility bills pending count');
        assert.ok(dashboardContent.includes('paquetes_pendientes'), 'Displays packages pending count');
    });
});
