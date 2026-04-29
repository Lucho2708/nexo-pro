# Arquitectura Laravel y Patrones Vue.js 3 — NEXO-PRO

## Arquitectura de capas en Laravel

### Flujo correcto de una petición

```
Request HTTP
    ↓
[FormRequest]        — Validación + Autorización (Gate/Policy)
    ↓
[Controller]         — Recibe, transforma a DTO, llama Action, retorna Resource
    ↓
[Action / Service]   — Lógica de negocio, orquesta Repository + Events
    ↓
[Repository]         — Acceso a datos (Eloquent, caché, etc.)
    ↓
[Model Eloquent]     — Solo relaciones, scopes, casts
    ↓
[API Resource]       — Transforma el modelo a la respuesta JSON
```

### Reglas por capa

| Capa | SÍ puede | NO puede |
|---|---|---|
| Controller | Llamar Actions, retornar Resources | Lógica de negocio, queries directas |
| Action | Orquestar Services y Repos, disparar Events | Saber de HTTP, conocer Request |
| Service | Lógica de dominio compleja | Devolver responses HTTP |
| Repository | Queries Eloquent, caché | Lógica de negocio |
| Model | Relaciones, scopes, casts, accesores | Enviar emails, llamar APIs externas |

---

## Patrones en Vue.js 3 (Composition API)

### Composable Pattern (equivalente a Service en Vue)

**Cuándo usar:** Lógica reutilizable entre componentes.

```javascript
// composables/useLiquidaciones.js
import { ref, computed } from 'vue'
import { useApi } from '@/composables/useApi'

export function useLiquidaciones(copropiedadId) {
    const { get, post, loading, error } = useApi()

    const liquidaciones = ref([])
    const filtro = ref({ estado: 'todas', mes: null })

    const pendientes = computed(() =>
        liquidaciones.value.filter(l => l.estado === 'pendiente')
    )

    const totalPendiente = computed(() =>
        pendientes.value.reduce((sum, l) => sum + l.valor, 0)
    )

    async function cargar() {
        liquidaciones.value = await get(`/api/v1/copropiedades/${copropiedadId}/liquidaciones`, filtro.value)
    }

    async function marcarPagada(liquidacionId, datosPago) {
        await post(`/api/v1/liquidaciones/${liquidacionId}/pagar`, datosPago)
        await cargar() // refrescar
    }

    return { liquidaciones, pendientes, totalPendiente, filtro, cargar, marcarPagada, loading, error }
}

// Uso en componente — limpio
const { pendientes, totalPendiente, cargar, loading } = useLiquidaciones(props.copropiedadId)
```

---

### Provider Pattern (Context / Provide-Inject)

**Cuándo usar:** Compartir estado entre componentes anidados sin prop drilling.

```javascript
// providers/CopropiedadProvider.js
import { provide, inject, ref, readonly } from 'vue'

const COPROPIEDAD_KEY = Symbol('copropiedad')

export function provideCopropiedad(copropiedad) {
    const estado = ref(copropiedad)

    function actualizar(datos) {
        estado.value = { ...estado.value, ...datos }
    }

    provide(COPROPIEDAD_KEY, {
        copropiedad: readonly(estado), // solo lectura para los hijos
        actualizar,
    })
}

export function useCopropiedad() {
    const context = inject(COPROPIEDAD_KEY)
    if (!context) throw new Error('useCopropiedad debe usarse dentro de CopropiedadProvider')
    return context
}

// En el componente raíz del módulo administrativo
// AdminLayout.vue
provideCopropiedad(page.props.copropiedad)

// En cualquier componente hijo profundo
const { copropiedad } = useCopropiedad() // sin pasar props
```

---

### Strategy Pattern en Vue (renderizado condicional desacoplado)

**Cuándo usar:** Renderizar distintos componentes según un tipo, sin if/else en el template.

```javascript
// En vez de v-if/v-else-if anidados:
// ❌ MAL
// <WidgetMora v-if="tipo === 'mora'" />
// <WidgetPagado v-else-if="tipo === 'pagado'" />
// <WidgetPendiente v-else-if="tipo === 'pendiente'" />

// ✅ BIEN: mapa de estrategias
import WidgetMora      from '@/Components/WidgetMora.vue'
import WidgetPagado    from '@/Components/WidgetPagado.vue'
import WidgetPendiente from '@/Components/WidgetPendiente.vue'

const widgetsPorEstado = {
    mora:      WidgetMora,
    pagado:    WidgetPagado,
    pendiente: WidgetPendiente,
}

const WidgetActivo = computed(() => widgetsPorEstado[props.estado] ?? WidgetPendiente)

// Template limpio:
// <component :is="WidgetActivo" v-bind="props" />
```

---

### Observer Pattern en Vue (EventBus con mitt)

**Cuándo usar:** Comunicación entre componentes no relacionados en el árbol.

```javascript
// plugins/eventBus.js
import mitt from 'mitt'
export const eventBus = mitt()

// En componente emisor (confirmación de pago)
import { eventBus } from '@/plugins/eventBus'
eventBus.emit('pago:registrado', { liquidacionId: 123, monto: 200000 })

// En componente receptor (dashboard KPIs)
import { eventBus } from '@/plugins/eventBus'
import { onMounted, onUnmounted } from 'vue'

onMounted(() => {
    eventBus.on('pago:registrado', actualizarKpis)
})

onUnmounted(() => {
    eventBus.off('pago:registrado', actualizarKpis) // siempre limpiar
})
```

---

### Facade Pattern en Vue (useApi composable)

**Cuándo usar:** Abstraer la comunicación HTTP para que los componentes no sepan de Axios.

```javascript
// composables/useApi.js
import axios from 'axios'
import { ref } from 'vue'
import { useToast } from '@/composables/useToast'

export function useApi() {
    const loading = ref(false)
    const error   = ref(null)
    const { mostrarError } = useToast()

    async function get(url, params = {}) {
        loading.value = true
        error.value = null
        try {
            const { data } = await axios.get(url, { params })
            return data.data ?? data
        } catch (e) {
            error.value = e.response?.data?.message ?? 'Error de conexión'
            mostrarError(error.value)
            throw e
        } finally {
            loading.value = false
        }
    }

    async function post(url, payload) {
        loading.value = true
        error.value = null
        try {
            const { data } = await axios.post(url, payload)
            return data.data ?? data
        } catch (e) {
            error.value = e.response?.data?.errors ?? e.response?.data?.message
            throw e
        } finally {
            loading.value = false
        }
    }

    return { get, post, loading, error }
}
```
