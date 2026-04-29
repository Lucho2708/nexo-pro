---
description: 
---

---
title: "Flujo de Trabajo Full-Stack con TDD Multi-Agente"
description: "Orquesta múltiples agentes para desarrollar una feature completa con TDD, backend, frontend y pruebas de integración."
---

## 🎯 Objetivo del Workflow
Ejecutar de forma orquestada el desarrollo completo de una funcionalidad, desde las pruebas hasta el frontend, aplicando los principios TDD y SOLID establecidos en las Rules del proyecto.

## 🧠 Plan de Acción

1.  **Inicialización y Planificación (Agente Principal - Tú)**
    *   **Rol**: Actuarás como el arquitecto principal. Usarás el comando `/superpowers-brainstorm` para definir los requisitos de la nueva funcionalidad con el agente.
    *   **Entregable**: Un `plan-implementacion.md` detallado y aprobado en la raíz del proyecto.

2.  **Fase TDD (Agente Experto en Pruebas)**
    *   **Rol**: Lanza un nuevo agente con el comando `@tdd-expert` (el skill que crearemos). Dale la instrucción: "Implementa el validador de emails siguiendo el ciclo TDD estricto descrito en las rules `02-tdd-cycle.md`. La prueba debe cubrir casos como 'usuario@dominio.com' (válido) y 'usuario@' (inválido)."
    *   **Entregable**: El archivo `email_validator.py` y su correspondiente `test_email_validator.py`.

3.  **Fase Backend y Base de Datos (Agente Experto en Backend)**
    *   **Rol**: Crea un segundo agente simultáneamente al paso 2. Dale la instrucción: "Crea un endpoint `/api/users` que use el `email_validator`. Sigue las reglas de estandarización de BD (`05-database-standards.md`) y seguridad (`06-security-hardening.md`)."
    *   **Entregable**: El código del endpoint, las migraciones de BD y las pruebas de integración.

4.  **Fase Frontend (Agente Experto en Frontend)**
    *   **Rol**: Crea un tercer agente en paralelo a los dos anteriores. Dale la instrucción: "Construye un formulario de registro de usuario en React que consuma el endpoint `/api/users`. Aplica las reglas de frontend premium (`03-frontend-premium.md`) y principios UX/UI (`04-ux-ui-principles.md`)."
    *   **Entregable**: El componente de React, sus estilos y las pruebas unitarias del mismo.

5.  **Fase de Integración y Revisión (Agente de QA)**
    *   **Rol**: Una vez que los otros tres agentes hayan terminado, lanza un cuarto agente con la instrucción: "Ejecuta todas las pruebas (unitarias y de integración). Verifica que no haya conflictos entre el frontend y el backend. Genera un informe de cobertura de código y de seguridad (OWASP)."
    *   **Entregable**: Un informe `qa-report.md` con los resultados de las pruebas y las validaciones.

## 📋 Instrucciones para el Usuario
Para ejecutar este workflow, deberás invocarlo manualmente escribiendo `/fullstack-tdd-workflow` en el chat. A continuación, el sistema te guiará a través de la creación de los diferentes agentes y la asignación de sus tareas específicas.