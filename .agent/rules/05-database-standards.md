---
trigger: always_on
---

# Normas para esquemas, consultas y migraciones de bases de datos

Aplica tanto a SQL (PostgreSQL, MySQL, SQLite) como a NoSQL si usas modelado relacional (ej. DynamoDB con estructuras fijas).

## Diseño de esquemas
- **Nombres**:
  - Tablas/colecciones en **snake_case** y en plural (`users`, `orders_items`).
  - Columnas/campos en **snake_case** (`first_name`, `created_at`).
  - Claves foráneas: `{tabla_singular}_id` (`user_id`, `product_id`).
- **Columnas obligatorias** en toda tabla relacional:
  - `id` (clave primaria, tipo `UUID` o `BIGINT` autoincremental).
  - `created_at` (timestamp con zona horaria, valor por defecto `NOW()`).
  - `updated_at` (timestamp que se actualiza automáticamente en cada modificación).
- **Índices**: crea índices para todas las columnas que aparezcan en cláusulas `WHERE`, `JOIN`, `ORDER BY` frecuentes.
- **Restricciones de integridad**:
  - `NOT NULL` donde corresponda.
  - `UNIQUE` para campos que no admiten duplicados.
  - `CHECK` para validaciones simples (ej. `age >= 0`).

## Consultas y operaciones
- **Prohibido** `SELECT *` en producción. Siempre enumera las columnas necesarias.
- Usa **transacciones** para operaciones que modifiquen múltiples tablas.
- Evita el N+1 problem: usa `JOIN` o carga eager (ORM).
- Limita el uso de `LIKE` con comodín al inicio (`%texto`) porque anula índices; prefiere búsqueda full-text cuando sea necesario.

## Seguridad de datos
- **Encriptación** en reposo para campos sensibles (contraseñas, tokens, información de pago) – usa bcrypt/argon2 para contraseñas.
- **Enmascaramiento** de logs: nunca registrar credenciales, números de tarjeta, etc.
- Las migraciones deben ser **reversibles** (si usas sistema de migraciones, provee el `down` o `rollback`).

## Lo que debe generar el agente
Al crear o modificar una tabla, el agente debe proporcionar:
1. El DDL (CREATE/ALTER TABLE).
2. Índices sugeridos.
3. Comentarios sobre columnas sensibles y su tratamiento.