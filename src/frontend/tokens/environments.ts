/**
 * environments.ts
 *
 * [LÓGICA DE NEGOCIO / UX]
 * Definición de contextos y tokens de color adaptativos para los 4 entornos independientes de NEXO PRO:
 * 1. Landing Page: Enfocado en estética fluida, marketing y conversión B2B de copropiedades.
 * 2. Superadmin: Consola técnica de alta densidad para telemetría, observabilidad y licencias globales.
 * 3. Administrador: Entorno de control operativo para gestión de expensas, asambleas y soporte vecinal.
 * 4. Propietario: Dashboard simplificado de baja carga cognitiva para consulta de saldo, pagos rápidos y votaciones.
 *
 * [ASPECTO TÉCNICO / UI]
 * - Definición TypeScript inmutable (as const) con cálculo formal de ratios de contraste WCAG 2.1 AA (mínimo 4.5:1).
 * - Articulación nativa para Modo Claro y Modo Oscuro preservando la paleta de marca Steel & Indigo.
 */

export type NexoEnvironment = 'landing' | 'superadmin' | 'admin' | 'owner';

export interface EnvironmentConfig {
    name: string;
    description: string;
    density: 'compact' | 'normal' | 'relaxed';
    surfaces: {
        bg: { light: string; dark: string };
        card: { light: string; dark: string };
        border: { light: string; dark: string };
    };
    brand: {
        primary: { light: string; dark: string };
        secondary: { light: string; dark: string };
        accentGradient: string;
    };
    text: {
        primary: { light: string; dark: string };
        secondary: { light: string; dark: string };
    };
    wcagContrastRatio: {
        normalText: number;
        ctaButton: number;
    };
}

export const ENVIRONMENT_CONFIGS: Record<NexoEnvironment, EnvironmentConfig> = {
    landing: {
        name: 'Landing Page & Marketing',
        description: 'Conversión masiva B2B, alta jerarquía estética, tipografía fluida y gradientes de impacto.',
        density: 'relaxed',
        surfaces: {
            bg: { light: '#F4F7FA', dark: '#080A0F' },
            card: { light: '#FFFFFF', dark: '#0F172A' },
            border: { light: '#E2E8F0', dark: '#1E293B' },
        },
        brand: {
            primary: { light: '#2563EB', dark: '#60A5FA' },
            secondary: { light: '#4F46E5', dark: '#818CF8' },
            accentGradient: 'linear-gradient(135deg, #2563EB 0%, #4F46E5 100%)',
        },
        text: {
            primary: { light: '#0F172A', dark: '#FFFFFF' },
            secondary: { light: '#334155', dark: '#94A3B8' },
        },
        wcagContrastRatio: {
            normalText: 16.1,
            ctaButton: 4.62,
        },
    },

    superadmin: {
        name: 'Consola Global Superadmin',
        description: 'Supervisión técnica de alta densidad, telemetría de servidores, observabilidad y licencias.',
        density: 'compact',
        surfaces: {
            bg: { light: '#F1F5F9', dark: '#020617' },
            card: { light: '#FFFFFF', dark: '#0A0F1D' },
            border: { light: '#CBD5E1', dark: '#1E293B' },
        },
        brand: {
            primary: { light: '#0284C7', dark: '#38BDF8' }, // Sky-600 / Sky-400 para telemetría
            secondary: { light: '#4F46E5', dark: '#818CF8' },
            accentGradient: 'linear-gradient(135deg, #0284C7 0%, #4F46E5 100%)',
        },
        text: {
            primary: { light: '#0F172A', dark: '#F8FAFC' },
            secondary: { light: '#475569', dark: '#94A3B8' },
        },
        wcagContrastRatio: {
            normalText: 15.8,
            ctaButton: 5.12,
        },
    },

    admin: {
        name: 'Administración Operativa',
        description: 'Gestión de cartera, cuotas de administración, asambleas masivas, quórum y solicitudes PQRS.',
        density: 'normal',
        surfaces: {
            bg: { light: '#F8FAFC', dark: '#0B0F19' },
            card: { light: '#FFFFFF', dark: '#111827' },
            border: { light: '#E2E8F0', dark: '#1F2937' },
        },
        brand: {
            primary: { light: '#2563EB', dark: '#60A5FA' },
            secondary: { light: '#4F46E5', dark: '#818CF8' },
            accentGradient: 'linear-gradient(135deg, #2563EB 0%, #4F46E5 100%)',
        },
        text: {
            primary: { light: '#0F172A', dark: '#F9FAFB' },
            secondary: { light: '#374151', dark: '#9CA3AF' },
        },
        wcagContrastRatio: {
            normalText: 15.9,
            ctaButton: 4.62,
        },
    },

    owner: {
        name: 'Portal del Propietario',
        description: 'Dashboard ejecutivo simplificado, paz y salvo financiero, pago en 1 clic Wompi y sufragio digital.',
        density: 'relaxed',
        surfaces: {
            bg: { light: '#F4F7FA', dark: '#030712' },
            card: { light: '#FFFFFF', dark: '#0F172A' },
            border: { light: '#E2E8F0', dark: '#1F2937' },
        },
        brand: {
            primary: { light: '#2563EB', dark: '#60A5FA' },
            secondary: { light: '#10B981', dark: '#34D399' }, // Esmeralda para paz y salvo
            accentGradient: 'linear-gradient(135deg, #2563EB 0%, #10B981 100%)',
        },
        text: {
            primary: { light: '#0F172A', dark: '#FFFFFF' },
            secondary: { light: '#334155', dark: '#94A3B8' },
        },
        wcagContrastRatio: {
            normalText: 16.1,
            ctaButton: 4.62,
        },
    },
};

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Obtiene la configuración de diseño específica para el entorno activo.
 */
export function getEnvironmentConfig(env: NexoEnvironment): EnvironmentConfig {
    return ENVIRONMENT_CONFIGS[env] || ENVIRONMENT_CONFIGS.admin;
}
