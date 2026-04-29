# Estándares de Auditoría y Trazabilidad (Security Audit)

Todo controlador que gestione lógica de negocio o cambie el estado de datos en NEXO-PRO debe implementar el sistema de auditoría profunda.

## Reglas Obligatorias
1. **Uso del Trait**: Todo controlador debe incluir `use App\Traits\Auditable;`.
2. **Registro de Acciones**: Cada método que realice cambios (store, update, destroy, toggle, etc.) debe invocar `$this->audit()`.
3. **Nomenclatura**:
    - `feature`: Siempre en MAYÚSCULAS (Ejem: 'USUARIOS', 'LICENCIAS', 'PAGOS').
    - `action`: Siempre en MAYÚSCULAS y con guiones bajos (Ejem: 'CREATE_USER', 'RESET_2FA').
4. **Privacidad (Habeas Data)**:
    - Está estrictamente prohibido pasar datos sensibles en el array de `metadata` (contraseñas, números de tarjeta completos, secretos).
    - El Trait `Auditable` ya cuenta con un sanitizador, pero es responsabilidad del desarrollador no enviar datos privados de manera explícita.
5. **Metadatos Útiles**: Siempre incluye IDs de referencia y nombres legibles de los objetos afectados para facilitar el soporte técnico.

## Ejemplo de Implementación Estándar
```php
class NuevoModuloController extends Controller {
    use Auditable;

    public function update(Request $request, Modelo $item) {
        // ... lógica de actualización ...
        
        $this->audit('MODULO', 'UPDATE_ITEM', [
            'item_id' => $item->id,
            'original_state' => $item->getOriginal('status'),
            'new_state' => $item->status
        ]);
    }
}
```

## Obligación del Agente
Antes de entregar un controlador nuevo o refactorizado, debes verificar que cumple con este contrato de auditoría. Si no se incluye el registro de auditoría, el código se considera INCOMPLETO.
