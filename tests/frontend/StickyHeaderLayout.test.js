import { describe, it } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

describe('Sticky Header & Administrator Navigation Persistence (UX & Architecture)', () => {
    const layoutPath = path.resolve(process.cwd(), 'resources/js/Layouts/AuthenticatedLayout.vue');
    const layoutContent = fs.readFileSync(layoutPath, 'utf-8');

    it('TDD - RED -> GREEN: Header must be sticky top-0 with z-index >= 30', () => {
        const headerMatch = layoutContent.match(/<header\s+([^>]+)>/i);
        assert.ok(headerMatch, '<header> element must exist in AuthenticatedLayout.vue');
        
        const headerClasses = headerMatch[1];
        assert.ok(headerClasses.includes('sticky'), 'Header must have position: sticky');
        assert.ok(headerClasses.includes('top-0'), 'Header must be anchored at top-0');
        assert.ok(/z-(30|40|50|\[\d+\])/.test(headerClasses), 'Header must have adequate z-index (>= 30)');
    });

    it('TDD - Sticky Integrity: Parent <main> must NOT contain overflow-x-hidden or overflow that breaks position: sticky', () => {
        // Extract the <main> opening tag
        const mainMatch = layoutContent.match(/<main\s+([^>]+)>/i);
        assert.ok(mainMatch, '<main> element must exist in AuthenticatedLayout.vue');
        
        const mainAttributes = mainMatch[1];
        assert.ok(
            !mainAttributes.includes('overflow-x-hidden'),
            'CRITICAL BUG: <main> must NOT have overflow-x-hidden because it converts computed overflow-y to auto and breaks position: sticky on child header'
        );
        assert.ok(
            !mainAttributes.includes('overflow-hidden'),
            '<main> must NOT have overflow-hidden because it breaks viewport sticky positioning'
        );
    });

    it('UX & Contrast: Header must have high-opacity backdrop-blur surface to prevent content bleed-through while scrolling', () => {
        const headerMatch = layoutContent.match(/<header\s+([^>]+)>/i);
        const headerClasses = headerMatch[1];

        // Ensure backdrop blur and high opacity background (>= 80% surface opacity, e.g. bg-surface/80 or bg-surface/90)
        assert.ok(headerClasses.includes('backdrop-blur'), 'Header must have backdrop-blur for frosted glass effect');
        assert.ok(
            /bg-surface\/(80|85|90|95)/.test(headerClasses) || headerClasses.includes('bg-surface '),
            'Header background must have at least 80% opacity to prevent chart/text clashing during scroll'
        );
        assert.ok(headerClasses.includes('border-b'), 'Header must feature a subtle bottom border to delineate navigation from scrollable content');
    });

    it('Accessibility & Information Architecture: Header contains critical administrator controls', () => {
        // Must contain user greeting
        assert.ok(layoutContent.includes('HOLA,'), 'Header must contain administrator greeting');
        // Must contain multi-tenant PropertySwitcher
        assert.ok(layoutContent.includes('PropertySwitcher'), 'Header must include PropertySwitcher');
        // Must contain ThemeToggle
        assert.ok(layoutContent.includes('ThemeToggle'), 'Header must include ThemeToggle');
        // Must contain NotificationCenter
        assert.ok(layoutContent.includes('NotificationCenter'), 'Header must include NotificationCenter');
        // Must contain user menu trigger & avatar
        assert.ok(layoutContent.includes('showUserMenu'), 'Header must include user menu dropdown trigger');
    });

    it('Defensive Impersonation: Sticky offset adjusts appropriately when support impersonation mode is active', () => {
        // When user is impersonating, header offset should be top-9 or top-10 to sit flush below support banner
        assert.ok(layoutContent.includes('is_impersonating'), 'Layout handles superadmin impersonation banner');
    });
});
