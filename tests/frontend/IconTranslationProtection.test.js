import { test, describe } from 'node:test';
import assert from 'node:assert/strict';
import fs from 'node:fs';
import path from 'node:path';

describe('Material Symbols Anti-Translation & Mobile Chrome Protection', () => {
    const rootDir = process.cwd();

    test('app.blade.php contains meta notranslate and root translate="no"', () => {
        const bladePath = path.join(rootDir, 'resources/views/app.blade.php');
        const content = fs.readFileSync(bladePath, 'utf8');

        assert.match(
            content,
            /<meta\s+name=["']google["']\s+content=["']notranslate["']>/i,
            'app.blade.php must include <meta name="google" content="notranslate">'
        );

        assert.match(
            content,
            /translate=["']no["']/i,
            'app.blade.php root <html> must include translate="no"'
        );

        assert.match(
            content,
            /class=["'][^"']*notranslate[^"']*["']/i,
            'app.blade.php root <html> must include notranslate class'
        );
    });

    test('config/app.php defaults to Spanish (es) locale to prevent browser auto-translation triggers', () => {
        const configPath = path.join(rootDir, 'config/app.php');
        const content = fs.readFileSync(configPath, 'utf8');

        assert.match(
            content,
            /'locale'\s*=>\s*env\('APP_LOCALE',\s*'es'\)/,
            'config/app.php must default APP_LOCALE to "es"'
        );

        assert.match(
            content,
            /'fallback_locale'\s*=>\s*env\('APP_FALLBACK_LOCALE',\s*'es'\)/,
            'config/app.php must default APP_FALLBACK_LOCALE to "es"'
        );
    });

    test('resources/css/app.css enforces font-feature-settings liga and prevents text-transform', () => {
        const cssPath = path.join(rootDir, 'resources/css/app.css');
        const content = fs.readFileSync(cssPath, 'utf8');

        assert.ok(
            content.includes("font-feature-settings: 'liga' 1"),
            'app.css must enforce font-feature-settings: "liga" 1'
        );

        assert.ok(
            content.includes('text-transform: none !important'),
            'app.css must protect against uppercase button styles leaking into icons'
        );

        assert.ok(
            content.includes('overflow: hidden'),
            'app.css must prevent icon text overflow'
        );
    });

    test('Core UI components (Input.vue and Button.vue) mark icon spans with translate="no" and notranslate', () => {
        const inputPath = path.join(rootDir, 'resources/js/Components/UI/Input.vue');
        const inputContent = fs.readFileSync(inputPath, 'utf8');

        assert.ok(
            inputContent.includes('notranslate'),
            'Input.vue icons must include notranslate class'
        );
        assert.ok(
            inputContent.includes('translate="no"'),
            'Input.vue icons must include translate="no"'
        );

        const buttonPath = path.join(rootDir, 'resources/js/Components/UI/Button.vue');
        const buttonContent = fs.readFileSync(buttonPath, 'utf8');

        assert.ok(
            buttonContent.includes('notranslate'),
            'Button.vue icons must include notranslate class'
        );
        assert.ok(
            buttonContent.includes('translate="no"'),
            'Button.vue icons must include translate="no"'
        );
    });

    test('resources/js/app.js provides client-side DOM mutation failsafe for dynamic icon elements', () => {
        const appJsPath = path.join(rootDir, 'resources/js/app.js');
        const content = fs.readFileSync(appJsPath, 'utf8');

        assert.ok(
            content.includes('MutationObserver'),
            'app.js must install a MutationObserver for dynamic icons'
        );
        assert.ok(
            content.includes('notranslate'),
            'app.js MutationObserver must ensure notranslate is applied'
        );
    });
});
