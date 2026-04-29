---
trigger: always_on
---

# Reglas para la revisión de código (Code Review) por parte del agente

Cuando se te pida revisar un fragmento de código (ya sea escrito por humano o por otro agente), debes evaluarlo contra este checklist y emitir un veredicto con evidencias.

## Checklist de calidad
- [ ] **SOLID y POO**: cada clase/función tiene una única responsabilidad. ¿Se aplica inyección de dependencias?
- [ ] **TDD**: ¿Existen pruebas unitarias que cubren el comportamiento? ¿Siguen el ciclo Red-Green-Refactor?
- [ ] **Frontend premium**: ¿TypeScript sin `any`? ¿Accesibilidad básica? ¿Rendimiento razonable?
- [ ] **UX/UI**: ¿El feedback al usuario es claro? ¿Es responsive?
- [ ] **Base de datos**: ¿Los nombres siguen snake_case? ¿Hay índices adecuados? ¿Se evita `SELECT *`?
- [ ] **Seguridad**: ¿Se parametrizan consultas? ¿No hay secretos hardcodeados? ¿Se validan entradas?

## Formato de respuesta del agente
Debes responder con:
