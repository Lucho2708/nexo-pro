<?php

namespace Tests\Feature\Porteria;

use Tests\TestCase;
use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use App\Modules\Operations\Models\PaseVisita;
use App\Modules\Operations\Models\SolicitudCitofonia;
use App\Modules\Operations\Models\MinutaPorteria;
use NexoPro\Porteria\Contracts\PorteriaServiceInterface;
use NexoPro\Porteria\DTOs\RegistrarVisitaDTO;
use NexoPro\Porteria\DTOs\RegistrarPaqueteDTO;
use NexoPro\Porteria\DTOs\RegistrarParqueaderoDTO;
use NexoPro\Porteria\DTOs\RelevarTurnoDTO;
use NexoPro\Porteria\DTOs\ValidarPaseVisitaDTO;
use NexoPro\Porteria\DTOs\SolicitarCitofoniaDTO;
use NexoPro\Porteria\DTOs\RegistrarMinutaForenseDTO;
use Carbon\Carbon;
use Mockery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

/**
 * Suite E2E / HTTP Integration para el Módulo Completo de Portería y Seguridad:
 * Valida los endpoints HTTP, middleware, validaciones y contratos de servicio.
 */
class PorteriaModuleHttpIntegrationTest extends TestCase
{
    private User $securityGuard;
    private $serviceMock;

    protected function setUp(): void
    {
        parent::setUp();

        $copropiedad = new Copropiedad([
            'nombre' => 'Residencial Altos del Norte',
            'settings' => ['parqueaderos_visitantes' => 25],
        ]);
        $copropiedad->id = (string) Str::uuid();

        $this->securityGuard = new User([
            'name' => 'Guarda Pruebas',
            'email' => 'guarda@nexopro.com',
            'role' => 'security',
            'current_copropiedad_id' => $copropiedad->id,
        ]);
        $this->securityGuard->id = (string) Str::uuid();
        $this->securityGuard->setRelation('currentCopropiedad', $copropiedad);

        $this->serviceMock = Mockery::mock(PorteriaServiceInterface::class);
        $this->app->instance(PorteriaServiceInterface::class, $this->serviceMock);

        // Desactivamos middlewares que requieren DB viva en este entorno
        $this->withoutMiddleware([
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\CaptureSystemMetrics::class,
            \App\Http\Middleware\CheckLicenseStatus::class,
            \App\Http\Middleware\EnsureLegalConsent::class,
            \App\Http\Middleware\EnsureTwoFactorIsConfigured::class,
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * E2E HU-SEC-01: Relevo táctil de turno por PIN
     */
    public function test_e2e_relevar_turno_con_pin_exitoso(): void
    {
        $this->serviceMock->shouldReceive('relevarTurno')
            ->once()
            ->withArgs(function (RelevarTurnoDTO $dto) {
                return $dto->pinEntrante === '4812';
            })
            ->andReturn([
                'success' => true,
                'guarda_entrante_nombre' => 'Carlos Rodriguez',
            ]);

        $response = $this->actingAs($this->securityGuard)
            ->post('/porteria/relevar-turno', [
                'pin' => '4812',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Relevo de turno exitoso. Bienvenido, Carlos Rodriguez.');
    }

    /**
     * E2E HU-SEC-02: Registro de visita
     */
    public function test_e2e_registrar_ingreso_visita(): void
    {
        $visita = new Visita();
        $visita->id = (string) Str::uuid();
        $visita->visitante_nombre = 'Maria Gomez';
        $visita->visitante_documento = '10203040';

        $this->serviceMock->shouldReceive('registrarVisita')
            ->once()
            ->andReturn($visita);

        $response = $this->actingAs($this->securityGuard)
            ->post('/porteria/visitas', [
                'unidad_id' => (string) Str::uuid(),
                'visitante_nombre' => 'Maria Gomez',
                'visitante_documento' => '10203040',
                'tipo_visita' => 'personal',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Visitante registrado exitosamente.');
    }

    /**
     * E2E HU-SEC-04: Validación de Pase QR pre-autorizado
     */
    public function test_e2e_validar_pase_visita_qr(): void
    {
        $visita = new Visita();
        $visita->id = (string) Str::uuid();
        $visita->visitante_nombre = 'Invitado QR';
        $visita->unidad_id = (string) Str::uuid();

        $this->serviceMock->shouldReceive('validarIngresoConPase')
            ->once()
            ->andReturn($visita);

        $response = $this->actingAs($this->securityGuard)
            ->post('/porteria/visitas/validar-pase', [
                'token_o_pin' => 'TOKEN_VALIDO_64_CHARS',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Acceso autorizado para Invitado QR mediante Pase QR.');
    }

    /**
     * E2E HU-SEC-05: Solicitud interactiva de citofonía virtual
     */
    public function test_e2e_solicitar_citofonia_virtual(): void
    {
        $solicitud = new SolicitudCitofonia();
        $solicitud->id = (string) Str::uuid();
        $solicitud->token_respuesta = 'TOKEN_CPAF123';

        $this->serviceMock->shouldReceive('solicitarCitofoniaVirtual')
            ->once()
            ->andReturn($solicitud);

        $response = $this->actingAs($this->securityGuard)
            ->post('/porteria/citofonia/solicitar', [
                'unidad_id' => (string) Str::uuid(),
                'visitante_nombre' => 'Pedro Domiciliario',
                'tipo_visita' => 'domicilio',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Llamada de citofonía virtual enviada al residente. Esperando respuesta...');
    }

    /**
     * E2E HU-SEC-05: Respuesta del residente desde WhatsApp/App
     */
    public function test_e2e_residente_responde_citofonia_virtual(): void
    {
        $solicitud = new SolicitudCitofonia();
        $solicitud->estado = 'autorizado';

        $this->serviceMock->shouldReceive('responderCitofoniaVirtual')
            ->once()
            ->withArgs(function ($token, $respuesta, $respondidoPor) {
                return $token === 'TOKEN_CPAF123' && $respuesta === 'autorizado';
            })
            ->andReturn($solicitud);

        $response = $this->actingAs($this->securityGuard)
            ->postJson('/porteria/citofonia/TOKEN_CPAF123/responder', [
                'respuesta' => 'autorizado',
            ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'estado' => 'autorizado',
            'mensaje' => 'Ingreso de visitante autorizado exitosamente.',
        ]);
    }

    /**
     * E2E HU-SEC-03: Recepción de paquete y entrega con PIN
     */
    public function test_e2e_recepcion_y_entrega_paquete_con_pin(): void
    {
        $paquete = new Paquete();
        $paquete->id = (string) Str::uuid();
        $paquete->pin_entrega = '7712';
        $paquete->unidad_id = (string) Str::uuid();

        Route::bind('paquete', fn($val) => $paquete);

        $this->serviceMock->shouldReceive('recepcionarPaquete')
            ->once()
            ->andReturn($paquete);

        // 1. Recepción
        $response1 = $this->actingAs($this->securityGuard)
            ->post('/porteria/paquetes', [
                'unidad_id' => $paquete->unidad_id,
                'empresa_mensajeria' => 'Servientrega',
            ]);

        $response1->assertRedirect();
        $response1->assertSessionHas('success', 'Paquete registrado en garita.');

        // 2. Entrega con PIN
        $paquete->estado = 'entregado';
        $this->serviceMock->shouldReceive('entregarPaqueteConPin')
            ->once()
            ->withArgs(function ($p, $pin, $entregadoA) {
                return $pin === '7712' && $entregadoA === 'Ana Torres';
            })
            ->andReturn($paquete);

        $response2 = $this->actingAs($this->securityGuard)
            ->patch("/porteria/paquetes/{$paquete->id}/entregar", [
                'pin' => '7712',
                'entregado_a_nombre' => 'Ana Torres',
            ]);

        $response2->assertRedirect();
        $response2->assertSessionHas('success', 'Paquete entregado satisfactoriamente.');
    }

    /**
     * E2E HU-SEC-07 & HU-SEC-08: Ciclo completo de parqueaderos y liquidación Fintech
     */
    public function test_e2e_ciclo_parqueaderos_ingreso_liquidacion_y_pago(): void
    {
        $registro = new ParqueaderoRegistro();
        $registro->id = (string) Str::uuid();
        $registro->placa_vehiculo = 'ABC123';
        $registro->tipo_espacio = 'visitante';
        $registro->ingreso_at = Carbon::now()->subHours(3);

        Route::bind('registro', fn($val) => $registro);

        // 1. Ingreso con validación de capacidad
        $this->serviceMock->shouldReceive('registrarIngresoVehiculoConCapacidad')
            ->once()
            ->andReturn($registro);

        $response1 = $this->actingAs($this->securityGuard)
            ->post('/porteria/parqueaderos', [
                'placa_vehiculo' => 'ABC123',
                'tipo_espacio' => 'visitante',
                'numero_bahia' => 'B-04',
            ]);

        $response1->assertRedirect();
        $response1->assertSessionHas('success', 'Ingreso de vehículo registrado.');

        // 2. Liquidación
        $this->serviceMock->shouldReceive('liquidarTarifaVehiculo')
            ->once()
            ->andReturn([
                'registro_id' => $registro->id,
                'es_cortesia' => false,
                'minutos_permanencia' => 180,
                'horas_cobrables' => 1,
                'valor_liquidado' => 2500,
                'referencia_pago' => 'PRK-REF-100',
            ]);

        $response2 = $this->actingAs($this->securityGuard)
            ->getJson("/porteria/parqueaderos/{$registro->id}/liquidar");

        $response2->assertOk();
        $response2->assertJson([
            'success' => true,
            'liquidacion' => [
                'es_cortesia' => false,
                'horas_cobrables' => 1,
                'valor_liquidado' => 2500,
            ],
        ]);

        // 3. Procesar Pago
        $registro->pagado_at = Carbon::now();
        $registro->valor_liquidado = 2500;
        $this->serviceMock->shouldReceive('procesarPagoParqueadero')
            ->once()
            ->andReturn($registro);

        $response3 = $this->actingAs($this->securityGuard)
            ->post("/porteria/parqueaderos/{$registro->id}/pagar", [
                'metodo_pago' => 'wompi_pse',
            ]);

        $response3->assertRedirect();
        $response3->assertSessionHas('success', 'Pago de parqueadero registrado satisfactoriamente.');

        // 4. Salida con validación de pago
        $registro->estado = 'finalizado';
        $this->serviceMock->shouldReceive('marcarSalidaVehiculoConValidacionPago')
            ->once()
            ->andReturn($registro);

        $response4 = $this->actingAs($this->securityGuard)
            ->patch("/porteria/parqueaderos/{$registro->id}/salida");

        $response4->assertRedirect();
        $response4->assertSessionHas('success', 'Salida vehicular registrada.');
    }

    /**
     * E2E HU-SEC-09: Registro en minuta forense inmutable con SHA-256
     */
    public function test_e2e_registro_minuta_forense(): void
    {
        $minuta = new MinutaPorteria();
        $minuta->id = (string) Str::uuid();
        $minuta->hash_inmutable = 'e3b0c44298fc1c149afbf4c8996fb92427ae41e4649b934ca495991b7852b855';

        $this->serviceMock->shouldReceive('registrarMinutaForense')
            ->once()
            ->withArgs(function (RegistrarMinutaForenseDTO $dto) {
                return $dto->tipoEvento === 'incidente' && $dto->prioridad === 'urgente';
            })
            ->andReturn($minuta);

        $response = $this->actingAs($this->securityGuard)
            ->post('/porteria/minuta', [
                'tipo_evento' => 'incidente',
                'prioridad' => 'urgente',
                'descripcion' => 'Sensor de humo activado en depósito 2.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    /**
     * E2E HU-SEC-10: Dashboard B2B de Supervisión
     */
    public function test_e2e_dashboard_supervision_b2b(): void
    {
        $this->serviceMock->shouldReceive('obtenerDashboardSupervisionB2B')
            ->once()
            ->andReturn([
                'kpis' => [
                    'total_puestos' => 5,
                    'puestos_operativos' => 4,
                    'puestos_alerta' => 1,
                    'alertas_criticas' => 0,
                ],
                'puestos' => [],
            ]);

        $response = $this->actingAs($this->securityGuard)
            ->get('/porteria/supervision');

        $response->assertOk();
    }

    /**
     * Blindaje E2E: Visitas index no falla si no hay unidades cargadas y no busca columna 'bloque' inexistente
     */
    public function test_e2e_visitas_index_queries_torre_without_bloque_exception(): void
    {
        $response = $this->actingAs($this->securityGuard)
            ->get('/porteria/visitas');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Porteria/Visitas/Index')
            ->has('unidades')
            ->has('visitas')
        );
    }

    /**
     * Blindaje E2E: Paquetes index no falla si no hay unidades y usa 'torre' de forma segura
     */
    public function test_e2e_paquetes_index_queries_torre_without_bloque_exception(): void
    {
        $response = $this->actingAs($this->securityGuard)
            ->get('/porteria/paquetes');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Porteria/Paquetes/Index')
            ->has('unidades')
            ->has('paquetes')
        );
    }

    /**
     * Blindaje E2E: Recibos index no falla si no hay unidades y usa 'torre' de forma segura
     */
    public function test_e2e_recibos_index_queries_torre_without_bloque_exception(): void
    {
        $response = $this->actingAs($this->securityGuard)
            ->get('/porteria/recibos');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Porteria/Recibos/Index')
            ->has('unidades')
            ->has('recibos')
        );
    }

    /**
     * Blindaje E2E: Dashboard maneja copropiedad sin inmuebles poblados
     */
    public function test_e2e_dashboard_handles_empty_unidades_cleanly(): void
    {
        $this->serviceMock->shouldReceive('calcularDisponibilidadParqueadero')
            ->once()
            ->andReturn([
                'capacidad_total' => 20,
                'ocupados' => 0,
                'disponibles' => 20,
                'porcentaje_ocupacion' => 0,
                'alerta_sobrecupo' => false,
            ]);

        $response = $this->actingAs($this->securityGuard)
            ->get('/porteria');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Porteria/Dashboard')
            ->has('metrics')
        );
    }

    /**
     * Blindaje E2E: Minuta maneja cadena vacía sin errores
     */
    public function test_e2e_minuta_handles_empty_chain_cleanly(): void
    {
        $this->serviceMock->shouldReceive('verificarIntegridadCadenaMinuta')
            ->once()
            ->andReturn([
                'cadena_valida' => true,
                'bloques_verificados' => 0,
                'bloque_corrupto_id' => null,
                'mensaje' => 'Bitácora vacía. Sin novedades registradas aún.',
            ]);

        $response = $this->actingAs($this->securityGuard)
            ->get('/porteria/minuta');

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Porteria/Minuta/Index')
            ->has('minutas')
            ->has('auditoria')
        );
    }
}
