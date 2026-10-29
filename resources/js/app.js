import './bootstrap';
import '../css/app.css';

import { createApp, h, defineAsyncComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const ToastContainer = defineAsyncComponent(() => import('./Components/UI/ToastContainer.vue'));

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Blindaje contra traducción automática de ligaduras de iconos (Google Material Symbols)
if (typeof window !== 'undefined') {
    const protectIcons = (root = document) => {
        const icons = root.querySelectorAll?.('.material-symbols-outlined, .material-symbols-rounded, [class*="material-symbols-"]');
        if (!icons) return;
        for (let i = 0; i < icons.length; i++) {
            const el = icons[i];
            if (!el.hasAttribute('translate')) {
                el.setAttribute('translate', 'no');
            }
            if (!el.classList.contains('notranslate')) {
                el.classList.add('notranslate');
            }
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => protectIcons());
    } else {
        protectIcons();
    }

    const observer = new MutationObserver((mutations) => {
        let shouldProtect = false;
        for (let i = 0; i < mutations.length; i++) {
            if (mutations[i].addedNodes.length > 0) {
                shouldProtect = true;
                break;
            }
        }
        if (shouldProtect) {
            protectIcons();
        }
    });

    observer.observe(document.documentElement, {
        childList: true,
        subtree: true,
    });
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ 
            render: () => h('div', [
                h(App, props),
                h(ToastContainer)
            ]) 
        });

        vueApp.use(plugin).use(ZiggyVue);

        // Failsafe Global Injected into all Vue Components
        vueApp.config.globalProperties.$safeRoute = (name, params = undefined) => {
            try {
                if (route().has(name)) return route(name, params);
            } catch (e) {
                // Telemetría Silenciosa (Fire and forget)
                fetch('/telemetry/frontend-error', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({
                        message: `Ziggy Failsafe previno un crasheo por ruta inexistente: '${name}'. Verifica el código de la UI.`,
                        level: 'warning',
                        context: { route_attempted: name, url: window.location.href, user_agent: navigator.userAgent }
                    })
                }).catch(() => {});
            }
            return '#'; 
        };

        vueApp.config.globalProperties.$isRouteActive = (pattern) => {
            try {
                return route().current(pattern);
            } catch (e) {
                return false;
            }
        };

        return vueApp.mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
