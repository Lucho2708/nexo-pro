import { describe, it } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

describe('Backoffice CRM Commercial Pipeline & Kanban Board', () => {
    const kanbanPath = path.resolve(process.cwd(), 'resources/js/Pages/Backoffice/Leads/Index.vue');
    const kanbanContent = fs.readFileSync(kanbanPath, 'utf-8');

    it('Information Architecture: Renders all 6 official stages of the sales funnel', () => {
        assert.ok(kanbanContent.includes('nuevo'), 'Contains nuevo stage');
        assert.ok(kanbanContent.includes('contactado'), 'Contains contactado stage');
        assert.ok(kanbanContent.includes('demo_agendada'), 'Contains demo_agendada stage');
        assert.ok(kanbanContent.includes('cotizado'), 'Contains cotizado stage');
        assert.ok(kanbanContent.includes('ganado'), 'Contains ganado stage');
        assert.ok(kanbanContent.includes('perdido'), 'Contains perdido stage');
    });

    it('UX & Data Presentation: Displays lead score, units count, and conversion rate', () => {
        assert.ok(kanbanContent.includes('Score:'), 'Displays lead qualification score');
        assert.ok(kanbanContent.includes('unidades_totales'), 'Displays units count of prospect');
        assert.ok(kanbanContent.includes('tasa_conversion'), 'Displays global conversion metric');
    });

    it('BackofficeLayout: Provides dedicated commercial branding and navigation', () => {
        const layoutPath = path.resolve(process.cwd(), 'resources/js/Layouts/BackofficeLayout.vue');
        const layoutContent = fs.readFileSync(layoutPath, 'utf-8');

        assert.ok(layoutContent.includes('Backoffice Comercial'), 'Identifies backoffice role');
        assert.ok(layoutContent.includes('backoffice.leads.index'), 'Navigates to CRM pipeline');
        assert.ok(layoutContent.includes('ThemeToggle'), 'Features theme toggle for light/dark mode');
    });
});
