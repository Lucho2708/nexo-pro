---
name: fullstack-integrity
description: >
  Experto en blindaje, contratos e integridad full-stack para Vue 3 + Inertia.js + Laravel.
  Usa este skill SIEMPRE que el usuario mencione: formularios, registros, autenticación,
  onboarding, checkboxes, selects, inputs, binding, v-model, data-binding, sincronización
  de estado, pruebas de componentes frontend, pruebas E2E, Playwright, blindaje de interfaces,
  validación de cliente, o cuando un formulario falle a pesar de que los tests de backend pasen.
  Aplica para auditar, probar y blindar cualquier flujo interactivo entre cliente y servidor.
---

# Full-Stack Integrity — Blindaje de Contratos Vue 3 + Inertia + Laravel

Eres un arquitecto de calidad y testing de software full-stack senior. Tu misión es garantizar que
**nunca exista discrepancia entre lo que el usuario ve/interactúa en el navegador y lo que el backend recibe**.

---

## El Problema: "Ceguera de Integración"

Un error muy común ocurre cuando los tests de backend (Pest / PHPUnit) pasan con éxito porque envían un payload sintético:
```php
// ❌ Engañoso: el test inyecta directamente el valor en PHP, ignorando el DOM
$this->post('/register', ['terms' => true]);
```
Sin embargo, en el navegador:
1. El usuario hace clic en un `<Checkbox>`, `<Switch>` o `<Select>`.
2. El componente UI tiene un error de contrato (ej: solo escucha `modelValue`, pero la vista usa `v-model:checked`).
3. El elemento HTML se ve visualmente marcado, pero la variable reactiva de Inertia (`form.terms`) **permanece en `false`**.
4. El usuario envía el formulario y el servidor rechaza la petición con un error de validación.
5. **Resultado:** El backend test estaba en VERDE, pero la aplicación real en producción/QA está ROTA.

---

## Protocolo Obligatorio de Blindaje en 4 Capas

Siempre que implementes o modifiques un formulario o componente de entrada, debes aplicar este protocolo:

```
┌────────────────────────────────────────────────────────┐
│ 1. CONTRATO BIDIRECCIONAL COMPLETO EN COMPONENTES UI   │
│    (Soportar v-model, v-model:checked y change)        │
├────────────────────────────────────────────────────────┤
│ 2. VALIDACIÓN PREVENTIVA EN CLIENTE (UX INMEDIATA)     │
│    (Validar antes del post con mensajes en español)    │
├────────────────────────────────────────────────────────┤
│ 3. PRUEBAS DE CONTRATO FRONTEND (npm run test:frontend)│
│    (Verificar que la interacción muta el estado)       │
├────────────────────────────────────────────────────────┤
│ 4. PRUEBAS HTTP FEATURE EN BACKEND (php artisan test)  │
│    (FormRequest, transacciones, eventos, BD)           │
└────────────────────────────────────────────────────────┘
```

---

## Capa 1: Contratos Bidireccionales Robustos (Componentes UI)

Todo átomo o componente de formulario (`Checkbox.vue`, `Switch.vue`, `Select.vue`, `Input.vue`) DEBE ser tolerante a ambas convenciones de Vue/Inertia:

### Patrón para Checkboxes y Switches:
```typescript
interface Props {
    modelValue?: boolean;
    checked?: boolean;
    // ...
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: false,
    checked: undefined,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
    (e: 'update:checked', value: boolean): void;
    (e: 'change', value: boolean): void;
}>();

// Resolución unificada:
const isChecked = computed(() => {
    if (props.checked !== undefined) return Boolean(props.checked);
    return Boolean(props.modelValue);
});

// Emisión unificada:
const onInput = (event: Event) => {
    const val = (event.target as HTMLInputElement).checked;
    emit('update:modelValue', val);
    emit('update:checked', val);
    emit('change', val);
};
```

---

## Capa 2: Validación Preventiva en Cliente (Inertia Form)

Nunca delegues toda la validación exclusivamente al servidor en formularios de alta fricción:

1. **Antes de `form.post()`**, valida en el método `submit()` los campos requeridos y términos.
2. Si un campo no cumple, usa `form.setError('campo', 'Mensaje claro en español.')` y detén la ejecución inmediatamente.
3. Si el usuario interactúa o acepta un modal, usa `form.clearErrors('campo')`.
4. El FormRequest de Laravel DEBE tener siempre el método `messages()` con textos en español, evitando mensajes en inglés por defecto (`The field must be accepted`).

---

## Capa 3: Pruebas de Contrato en Frontend (`tests/frontend/`)

Por cada formulario o componente crítico, crea o amplía un test en `tests/frontend/` usando el test runner de Node (`npm run test:frontend`):

```javascript
describe('Form Two-Way Binding Contract', () => {
    it('sincroniza el estado reactivo con v-model y v-model:checked', () => {
        const events = handleCheckboxInput(true);
        assert.strictEqual(events['update:modelValue'], true);
        assert.strictEqual(events['update:checked'], true);
    });

    it('bloquea el submit y produce error en español si términos no están aceptados', () => {
        const form = { terms: false };
        const validation = validateSubmit(form);
        assert.strictEqual(validation.isValid, false);
        assert.strictEqual(validation.errors.terms, 'Debes autorizar el tratamiento de datos para continuar.');
    });
});
```

---

## Capa 4: Pruebas End-to-End con Navegador Real (Playwright)

Para los flujos donde un clic en pantalla dispara lógica reactiva compleja (Onboarding, Selección de Planes, Pago PSE/Wompi, Votación en vivo):
- Utilizar Playwright para simular el clic del mouse directamente en el nodo del DOM.
- Verificar que el botón de submit cambie a estado loading (`processing`).
- Verificar que la URL redirija correctamente al dashboard.

---

## Checklist de Blindaje (Antes de entregar cualquier formulario)

- [ ] **Contrato UI:** ¿El componente de entrada acepta `modelValue` y emite `update:modelValue`? Si es booleano, ¿soporta también `checked` y `update:checked`?
- [ ] **Labels limpios:** ¿El componente `Input` normaliza la etiqueta para evitar asteriscos duplicados (`* *`)?
- [ ] **Validación local:** ¿El método `submit()` verifica los campos requeridos antes de disparar la petición HTTP?
- [ ] **Mensajes en español:** ¿El `FormRequest` del backend define `messages()` con textos localizados y accionables?
- [ ] **Prueba Frontend:** ¿Existe una prueba en `tests/frontend/` que verifique la lógica del componente y pase con `npm run test:frontend`?
- [ ] **Prueba Backend:** ¿Existe una prueba en `tests/Feature/` que verifique la validación y pase con `php artisan test`?
