import { describe, it } from 'node:test';
import assert from 'node:assert/strict';

export const DEFAULT_CATALOGO_COMPONENTES = [
    { id: 'default-1', nombre: 'Habitación' },
    { id: 'default-2', nombre: 'Baño' },
    { id: 'default-3', nombre: 'Cocina' },
    { id: 'default-4', nombre: 'Sala' },
    { id: 'default-5', nombre: 'Comedor' },
    { id: 'default-6', nombre: 'Balcón' },
    { id: 'default-7', nombre: 'Terraza' },
    { id: 'default-8', nombre: 'Estudio' },
    { id: 'default-9', nombre: 'Zona de Ropas' },
    { id: 'default-10', nombre: 'Patio' },
    { id: 'default-11', nombre: 'Parqueadero' },
    { id: 'default-12', nombre: 'Depósito' }
];

export class TipoUnidadFormController {
    constructor(initialCatalog = []) {
        this.catalogo = initialCatalog && initialCatalog.length > 0 
            ? [...initialCatalog] 
            : [...DEFAULT_CATALOGO_COMPONENTES];
        this.nombre = '';
        this.area_m2 = '';
        this.componentes = [];
    }

    toggleComponente(comp) {
        const index = this.componentes.findIndex(c => c.id === comp.id);
        if (index === -1) {
            this.componentes.push({ id: comp.id, nombre: comp.nombre, cantidad: 1 });
        } else {
            this.componentes.splice(index, 1);
        }
    }

    updateCantidad(compId, delta) {
        const comp = this.componentes.find(c => c.id === compId);
        if (comp) {
            comp.cantidad = Math.max(1, comp.cantidad + delta);
        }
    }

    addCustomComponente(nombre) {
        if (!nombre || !nombre.trim()) return null;
        const cleanName = nombre.trim();
        const existing = this.catalogo.find(c => c.nombre.toLowerCase() === cleanName.toLowerCase());
        if (existing) return existing;

        const newComp = {
            id: `custom-${Date.now()}`,
            nombre: cleanName
        };
        this.catalogo.push(newComp);
        return newComp;
    }

    validate() {
        if (!this.nombre.trim()) {
            return { valid: false, error: 'Por favor ingresa el nombre del modelo.' };
        }
        const area = Number(this.area_m2);
        if (isNaN(area) || area <= 0) {
            return { valid: false, error: 'Por favor ingresa un área privada válida mayor a cero.' };
        }
        if (this.componentes.length === 0) {
            return { valid: false, error: 'Debes seleccionar al menos un componente arquitectónico.' };
        }
        return { valid: true, error: null };
    }
}

describe('Tipo Unidad & Architectural Components Selector Tests', () => {
    it('Happy Path: Auto-recupera catálogo por defecto si el backend envía lista vacía (Anti-Empty-State)', () => {
        const controller = new TipoUnidadFormController([]);
        assert.ok(controller.catalogo.length >= 12);
        assert.ok(controller.catalogo.some(c => c.nombre === 'Habitación'));
        assert.ok(controller.catalogo.some(c => c.nombre === 'Baño'));
        assert.ok(controller.catalogo.some(c => c.nombre === 'Cocina'));
    });

    it('Happy Path: Selecciona componentes y asigna cantidad inicial = 1', () => {
        const controller = new TipoUnidadFormController();
        const hab = controller.catalogo.find(c => c.nombre === 'Habitación');
        
        controller.toggleComponente(hab);
        assert.equal(controller.componentes.length, 1);
        assert.equal(controller.componentes[0].nombre, 'Habitación');
        assert.equal(controller.componentes[0].cantidad, 1);
    });

    it('Happy Path: Modifica cantidades respetando mínimo reglamentario >= 1', () => {
        const controller = new TipoUnidadFormController();
        const ban = controller.catalogo.find(c => c.nombre === 'Baño');
        
        controller.toggleComponente(ban);
        controller.updateCantidad(ban.id, 2); // 1 + 2 = 3
        assert.equal(controller.componentes[0].cantidad, 3);

        controller.updateCantidad(ban.id, -5); // Clamp a 1
        assert.equal(controller.componentes[0].cantidad, 1);
    });

    it('Happy Path: Deselecciona componente al volver a hacer clic', () => {
        const controller = new TipoUnidadFormController();
        const ban = controller.catalogo.find(c => c.nombre === 'Baño');
        
        controller.toggleComponente(ban);
        assert.equal(controller.componentes.length, 1);

        controller.toggleComponente(ban);
        assert.equal(controller.componentes.length, 0);
    });

    it('Happy Path: Permite agregar componentes arquitectónicos personalizados al vuelo', () => {
        const controller = new TipoUnidadFormController();
        const custom = controller.addCustomComponente('Jacuzzi Panorámico');
        
        assert.ok(custom);
        assert.equal(custom.nombre, 'Jacuzzi Panorámico');
        assert.ok(controller.catalogo.some(c => c.nombre === 'Jacuzzi Panorámico'));
    });

    it('Edge Case: Valida campos obligatorios antes de permitir el registro del modelo', () => {
        const controller = new TipoUnidadFormController();
        
        // 1. Sin nombre
        let validation = controller.validate();
        assert.equal(validation.valid, false);
        assert.ok(validation.error.includes('nombre'));

        // 2. Sin área válida
        controller.nombre = 'Apartamento Tipo A';
        controller.area_m2 = '0';
        validation = controller.validate();
        assert.equal(validation.valid, false);
        assert.ok(validation.error.includes('área privada'));

        // 3. Sin componentes
        controller.area_m2 = '85.50';
        validation = controller.validate();
        assert.equal(validation.valid, false);
        assert.ok(validation.error.includes('componente'));

        // 4. Todo completo
        const hab = controller.catalogo.find(c => c.nombre === 'Habitación');
        controller.toggleComponente(hab);
        validation = controller.validate();
        assert.equal(validation.valid, true);
        assert.equal(validation.error, null);
    });
});
