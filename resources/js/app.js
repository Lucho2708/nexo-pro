import './bootstrap';
import '../css/app.css';

import { createApp, h, defineAsyncComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const ToastContainer = defineAsyncComponent(() => import('./Components/UI/ToastContainer.vue'));

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

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
