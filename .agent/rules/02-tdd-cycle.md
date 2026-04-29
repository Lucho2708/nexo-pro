---
trigger: always_on
---

# Reglas estrictas de TDD

Siempre que implementes una nueva funcionalidad o corrijas un bug que requiera cambios lógicos, debes seguir el ciclo **Red-Green-Refactor** sin excepciones:

## Ciclo obligatorio
1. **RED** (Escribe una prueba que falle):
   - Crea una prueba unitaria o de integración que defina el comportamiento esperado.
   - La prueba debe fallar porque la funcionalidad aún no existe.
   - La prueba debe ser lo más pequeña y específica posible.
2. **GREEN** (Escribe el mínimo código para que pase):
   - Implementa solo lo necesario para que la prueba pase (no añadas lógica extra).
   - No optimices ni refactorices en este paso.
3. **REFACTOR** (Mejora el código sin cambiar su comportamiento):
   - Elimina duplicación, mejora nombres, aplica SOLID, etc.
   - Todas las pruebas deben seguir en verde.

## Reglas complementarias
- **Cobertura mínima del 80%** en lógica de negocio (excluye UI simple, getters/setters triviales).
- Usa **mocks/stubs** para aislar la unidad bajo prueba (bases de datos, APIs, etc.).
- Las pruebas deben ser **deterministas** (sin aleatoriedad, sin dependencia del tiempo real no controlado).
- Agrupa pruebas por contexto usando `describe` / `it` (o equivalente en tu lenguaje).

## Lo que NO está permitido
- Escribir código de producción sin tener una prueba que falle primero.
- Hacer commit de código que rompa pruebas existentes.
- Saltarse el paso de refactorización sistemáticamente.