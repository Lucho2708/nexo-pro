---
trigger: always_on
---

# Reglas SOLID y POO para el desarrollo

Eres un experto en ingeniería de software. Todo código que escribas, refactorices o sugieras debe cumplir estrictamente con los siguientes principios:

## Principios SOLID
1. **S - Responsabilidad Única**: Cada clase, módulo o función debe tener una sola razón para cambiar. Si identificas más de una responsabilidad, debes proponer una división.
2. **O - Abierto/Cerrado**: Las entidades deben estar abiertas para extensión pero cerradas para modificación. Usa herencia, interfaces o composición para agregar comportamiento nuevo sin alterar el existente.
3. **L - Sustitución de Liskov**: Las clases derivadas deben poder sustituir a sus clases base sin alterar el correcto funcionamiento del programa. Evita sobrescribir métodos con comportamientos que rompan la lógica esperada.
4. **I - Segregación de Interfaces**: Es mejor tener interfaces pequeñas y específicas que una interfaz general. Divide interfaces grandes en roles más concretos.
5. **D - Inversión de Dependencias**: Los módulos de alto nivel no deben depender de módulos de bajo nivel. Ambos deben depender de abstracciones. Usa inyección de dependencias siempre que sea posible.

## Buenas prácticas de POO
- Favorece la **composición sobre la herencia** (prefiere inyectar comportamientos antes que extender clases).
- Encapsula correctamente: propiedades privadas o protegidas, getters/setters solo cuando sean necesarios.
- Aplica el patrón **Repository** para el acceso a datos, **Service** para la lógica de negocio y **DTO** para transferencia de datos.
- Nombra clases con sustantivos (Ej: `UserService`, `EmailValidator`), métodos con verbos (`sendEmail`, `calculateTotal`).

## Obligación del agente
Antes de entregar cualquier bloque de código, **debes explicar brevemente cómo aplicas SOLID en esa pieza**. Si no puedes justificarlo, propón una refactorización.