import { createSSRApp, h } from 'vue';
import { renderToString } from '@vue/server-renderer';
import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - NEXO-PRO`,
        resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
        setup({ App, props, plugin }) {
            const vueApp = createSSRApp({ render: () => h(App, props) });
            
            vueApp.use(plugin).use(ZiggyVue, {
                ...(page.props.ziggy || {}),
                location: page.props.ziggy?.location ? new URL(page.props.ziggy.location) : undefined,
            });

            vueApp.config.globalProperties.$safeRoute = (name, params = undefined) => {
                try {
                    if (route().has(name)) return route(name, params);
                } catch (e) {}
                return '#'; 
            };

            vueApp.config.globalProperties.$isRouteActive = (pattern) => {
                try {
                    return route().current(pattern);
                } catch (e) {
                    return false;
                }
            };

            return vueApp;
        },
    })
);
