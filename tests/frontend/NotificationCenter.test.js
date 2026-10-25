import { describe, it } from 'node:test';
import assert from 'node:assert/strict';

/**
 * Lógica del controlador NotificationCenter.vue
 */
export class NotificationCenterController {
    constructor(announcements = []) {
        this.announcements = [...announcements];
        this.isOpen = false;
    }

    get unreadCount() {
        return this.announcements.filter(a => !a.is_read).length;
    }

    toggleDropdown() {
        this.isOpen = !this.isOpen;
        return this.isOpen;
    }

    markAsRead(announcementId) {
        const item = this.announcements.find(a => a.id === announcementId);
        if (item && !item.is_read) {
            item.is_read = true;
            return true;
        }
        return false;
    }

    addAnnouncement(announcement) {
        this.announcements.unshift({
            ...announcement,
            is_read: false,
            created_at: new Date().toISOString()
        });
    }
}

describe('NotificationCenter Component Logic & Broadcasting Tests', () => {
    it('Happy Path: Calcula reactivamente el total de avisos no leídos', () => {
        const center = new NotificationCenterController([
            { id: 1, title: 'Corte de agua programado', is_read: false },
            { id: 2, title: 'Convocatoria asamblea extraordinaria', is_read: false },
            { id: 3, title: 'Mantenimiento de ascensores', is_read: true }
        ]);

        assert.equal(center.unreadCount, 2);
    });

    it('Happy Path: Marcar como leído reduce el contador y muta el estado local', () => {
        const center = new NotificationCenterController([
            { id: 1, title: 'Corte de agua programado', is_read: false },
            { id: 2, title: 'Convocatoria asamblea', is_read: false }
        ]);

        assert.equal(center.unreadCount, 2);
        const marked = center.markAsRead(1);
        assert.ok(marked);
        assert.equal(center.unreadCount, 1);
    });

    it('Edge Case: Marcar un anuncio ya leído es idempotente y no altera el contador', () => {
        const center = new NotificationCenterController([
            { id: 1, title: 'Aviso general', is_read: true }
        ]);

        assert.equal(center.unreadCount, 0);
        const marked = center.markAsRead(1);
        assert.equal(marked, false);
        assert.equal(center.unreadCount, 0);
    });

    it('Happy Path: Alterna visibilidad del dropdown accesible', () => {
        const center = new NotificationCenterController([]);
        assert.equal(center.isOpen, false);
        
        center.toggleDropdown();
        assert.equal(center.isOpen, true);

        center.toggleDropdown();
        assert.equal(center.isOpen, false);
    });

    it('Happy Path: Inserción de nuevo aviso push recibido por WebSocket incrementa contador', () => {
        const center = new NotificationCenterController([]);
        assert.equal(center.unreadCount, 0);

        center.addAnnouncement({ id: 10, title: 'Urgente: Alarma comunitaria' });
        assert.equal(center.unreadCount, 1);
        assert.equal(center.announcements[0].title, 'Urgente: Alarma comunitaria');
    });
});
