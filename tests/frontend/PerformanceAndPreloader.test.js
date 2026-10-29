import { test, describe } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

describe('Performance Optimization, CSS Loading Speed & Native Logo Preloader Suite', () => {
    const rootDir = process.cwd();

    test('resources/css/app.css eliminates @import url waterfall anti-pattern', () => {
        const cssPath = path.join(rootDir, 'resources/css/app.css');
        const content = fs.readFileSync(cssPath, 'utf8');

        assert.strictEqual(
            content.includes('@import url('),
            false,
            'app.css must not use @import url(...) to prevent network waterfall delays'
        );
    });

    test('vite.config.js enables Rust-based lightningcss minifier', () => {
        const vitePath = path.join(rootDir, 'vite.config.js');
        const content = fs.readFileSync(vitePath, 'utf8');

        assert.ok(
            content.includes("cssMinify: 'lightningcss'"),
            'vite.config.js must specify cssMinify: "lightningcss"'
        );
    });

    test('app.blade.php preloads unified Manrope font and removes duplicate font families', () => {
        const bladePath = path.join(rootDir, 'resources/views/app.blade.php');
        const content = fs.readFileSync(bladePath, 'utf8');

        assert.ok(
            content.includes('family=Manrope:wght@400;500;600;700;800&display=swap'),
            'app.blade.php must preload and link Manrope with display=swap'
        );

        assert.strictEqual(
            content.includes('family=Inter'),
            false,
            'app.blade.php must not duplicate download of Inter when Manrope is the brand font'
        );
    });

    test('app.blade.php avoids universal * transitions to protect 60/120 FPS refresh rate', () => {
        const bladePath = path.join(rootDir, 'resources/views/app.blade.php');
        const content = fs.readFileSync(bladePath, 'utf8');

        assert.strictEqual(
            content.includes('transition-property: transform, opacity !important'),
            false,
            'app.blade.php must not force transition-property on the universal selector *'
        );
    });

    test('app.blade.php embeds native Zero-JS NEXO-PRO logo splash preloader', () => {
        const bladePath = path.join(rootDir, 'resources/views/app.blade.php');
        const content = fs.readFileSync(bladePath, 'utf8');

        assert.ok(
            content.includes('id="nexo-app-preloader"'),
            'app.blade.php must contain #nexo-app-preloader'
        );

        assert.ok(
            content.includes('nexo-splash-hexagon'),
            'app.blade.php preloader must render the isometric hexagon'
        );

        assert.ok(
            content.includes('nexo-splash-n-pulse'),
            'app.blade.php preloader must animate the glowing N path'
        );
    });

    test('resources/js/app.js handles preloader dismissal and Inertia route micro-spinner', () => {
        const appJsPath = path.join(rootDir, 'resources/js/app.js');
        const content = fs.readFileSync(appJsPath, 'utf8');

        assert.ok(
            content.includes('dismissPreloader'),
            'app.js must provide dismissPreloader function'
        );

        assert.ok(
            content.includes('nexo-nav-logo-spinner'),
            'app.js must provide the Inertia route navigation logo spinner'
        );
    });
});
