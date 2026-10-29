import './bootstrap';
import '../css/app.css';

import { createApp, h, defineAsyncComponent } from 'vue';
import { createInertiaApp, router } from '@inertiajs/vue3';
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

// Desvanecimiento suave y retiro del Preloader nativo de logo
const dismissPreloader = () => {
    if (typeof document === 'undefined') return;
    const preloader = document.getElementById('nexo-app-preloader');
    if (preloader) {
        preloader.classList.add('fade-out');
        setTimeout(() => {
            preloader.remove();
        }, 400);
    }
};

// Micro-loader de Navegación de Rutas Inertia con Logo Hexagonal
if (typeof window !== 'undefined') {
    let navigationTimeout = null;
    let navSpinnerEl = null;

    router.on('start', () => {
        navigationTimeout = setTimeout(() => {
            if (!navSpinnerEl) {
                navSpinnerEl = document.createElement('div');
                navSpinnerEl.id = 'nexo-nav-logo-spinner';
                navSpinnerEl.setAttribute('aria-hidden', 'true');
                navSpinnerEl.innerHTML = `
                    <div style="position: fixed; top: 16px; right: 16px; z-index: 99998; background: rgba(6, 8, 14, 0.88); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(59, 130, 246, 0.35); border-radius: 14px; padding: 6px 14px; display: flex; align-items: center; gap: 9px; box-shadow: 0 8px 30px rgba(0,0,0,0.6); pointer-events: none; transition: opacity 0.2s ease;">
                        <svg width="20" height="20" viewBox="0 0 200 200" style="animation: nexo-nav-spin 1.2s linear infinite; will-change: transform;">
                            <polygon points="100,10 180,55 180,145 100,190 20,145 20,55" fill="none" stroke="#2563EB" stroke-width="18" />
                            <path d="M 55,135 L 55,65 L 100,100 L 145,135 L 145,65" fill="none" stroke="#F59E0B" stroke-width="20" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span style="font-family: 'Manrope', system-ui, sans-serif; font-size: 11px; font-weight: 800; color: #E2E8F0; letter-spacing: 0.08em; text-transform: uppercase;">Cargando...</span>
                    </div>
                    <style>
                        @keyframes nexo-nav-spin {
                            0% { transform: rotate(0deg); }
                            100% { transform: rotate(360deg); }
                        }
                    </style>
                `;
                document.body.appendChild(navSpinnerEl);
            }
        }, 220);
    });

    router.on('finish', () => {
        if (navigationTimeout) {
            clearTimeout(navigationTimeout);
            navigationTimeout = null;
        }
        if (navSpinnerEl) {
            navSpinnerEl.remove();
            navSpinnerEl = null;
        }
    });

    // Failsafe de desvanecimiento por si la carga inicial ya ocurrió
    if (document.readyState === 'complete') {
        setTimeout(dismissPreloader, 100);
    } else {
        window.addEventListener('load', () => setTimeout(dismissPreloader, 100));
        setTimeout(dismissPreloader, 1800);
    }
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

        const mounted = vueApp.mount(el);
        if (typeof window !== 'undefined') {
            requestAnimationFrame(() => {
                dismissPreloader();
            });
        }
        return mounted;
    },
    progress: {
        color: '#2563EB',
        showSpinner: false,
    },
});
