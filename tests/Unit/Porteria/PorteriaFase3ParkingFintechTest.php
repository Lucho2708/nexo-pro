<?php

namespace Tests\Unit\Porteria;

use Tests\TestCase;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\PaseVisita;
use App\Modules\Operations\Models\SolicitudCitofonia;
use App\Modules\IAM\Models\User;
use NexoPro\Porteria\Contracts\PorteriaServiceInterface;
use NexoPro\Porteria\Contracts\PorteriaRepositoryInterface;
use NexoPro\Porteria\DTOs\RegistrarParqueaderoDTO;
use NexoPro\Porteria\DTOs\PoliticaTarifariaDTO;
use NexoPro\Porteria\Services\PorteriaService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DomainException;

/**
 * [TDD - RED -> GREEN -> REFACTOR]
 * Pruebas unitarias para la Fase 3 del Módulo de Portería:
 * - HU-SEC-07: Monitoreo de aforo, semáforo y bloqueo estricto por sobrecupo.
 * - HU-SEC-08: Tarificación y liquidación Fintech (cortesía vs exceso de horas vía Wompi/PSE).
 */
class PorteriaFase3ParkingFintechTest extends TestCase
{
    private PorteriaServiceInterface $service;
    private PorteriaRepositoryInterface $repository;
    private string $copropiedadId;
    private string $unidadId;
    private string $guardaId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->copropiedadId = (string) Str::uuid();
        $this->unidadId = (string) Str::uuid();
        $this->guardaId = (string) Str::uuid();

        // Repositorio in-memory para pruebas deterministas de Parqueadero y Tarifas
        $this->repository = new class implements PorteriaRepositoryInterface {
            public array $parqueaderos = [];

            public function guardarVisita(array $datos): Visita
            {
                $v = new Visita($datos);
                $v->id = (string) Str::uuid();
                return $v;
            }

            public function actualizarVisita(Visita $visita, array $datos): Visita
            {
                $visita->fill($datos);
                return $visita;
            }

            public function guardarRecibo(array $datos): \App\Modules\Operations\Models\ReciboPublico
            {
                $r = new \App\Modules\Operations\Models\ReciboPublico($datos);
                $r->id = (string) Str::uuid();
                return $r;
            }

            public function guardarPaquete(array $datos): \App\Modules\Operations\Models\Paquete
            {
                $p = new \App\Modules\Operations\Models\Paquete($datos);
                $p->id = (string) Str::uuid();
                return $p;
            }

            public function actualizarPaquete(\App\Modules\Operations\Models\Paquete $paquete, array $datos): \App\Modules\Operations\Models\Paquete
            {
                $paquete->fill($datos);
                return $paquete;
            }

            public function guardarParqueadero(array $datos): ParqueaderoRegistro
            {
                $registro = new ParqueaderoRegistro($datos);
                $registro->id = (string) Str::uuid();
                $registro->exists = true;
                $this->parqueaderos[$registro->id] = $registro;
                return $registro;
            }

            public function actualizarParqueadero(ParqueaderoRegistro $registro, array $datos): ParqueaderoRegistro
            {
                $registro->fill($datos);
                $this->parqueaderos[$registro->id] = $registro;
                return $registro;
            }

            public function contarVehiculosActivosPorTipo(string $copropiedadId, string $tipoEspacio): int
            {
                $conteo = 0;
                foreach ($this->parqueaderos as $registro) {
                    if ($registro->copropiedad_id === $copropiedadId 
                        && $registro->tipo_espacio === $tipoEspacio 
                        && $registro->estado === 'activo') {
                        $conteo++;
                    }
                }
                return $conteo;
            }

            public function buscarGuardaPorPin(string $copropiedadId, string $pin): ?User
            {
                return null;
            }

            public function registrarMinuta(array $datos): \App\Modules\Operations\Models\MinutaPorteria
            {
                $m = new \App\Modules\Operations\Models\MinutaPorteria($datos);
                $m->id = (string) Str::uuid();
                return $m;
            }

            public function guardarPaseVisita(array $datos): PaseVisita
            {
                $p = new PaseVisita($datos);
                $p->id = (string) Str::uuid();
                return $p;
            }

            public function buscarPaseVisitaPorToken(string $token): ?PaseVisita
            {
                return null;
            }

            public function actualizarPaseVisita(PaseVisita $pase, array $datos): PaseVisita
            {
                $pase->fill($datos);
                return $pase;
            }

            public function guardarSolicitudCitofonia(array $datos): SolicitudCitofonia
            {
                $s = new SolicitudCitofonia($datos);
                $s->id = (string) Str::uuid();
                return $s;
            }

            public function buscarSolicitudCitofoniaPorToken(string $token): ?SolicitudCitofonia
            {
                return null;
            }

            public function actualizarSolicitudCitofonia(SolicitudCitofonia $solicitud, array $datos): SolicitudCitofonia
            {
                $solicitud->fill($datos);
                return $solicitud;
            }

            public function obtenerUltimaMinutaPorCopropiedad(string $copropiedadId): ?\App\Modules\Operations\Models\MinutaPorteria { return null; }
            public function listarMinutasPorCopropiedad(string $copropiedadId): array { return []; }
            public function obtenerCopropiedadesParaSupervision(?string $supervisorId = null): array { return []; }
        };

        $this->service = new PorteriaService($this->repository);
    }

    /**
     * [HU-SEC-07: Bloqueo Automático por Sobrecupo]
     * Si la capacidad de visitantes está al 100% (ej: 3 cupos y 3 ocupados),
     * cualquier nuevo ingreso de tipo 'visitante' es bloqueado con excepción de dominio.
     */
    public function test_bloqueo_automatico_de_ingreso_vehicular_por_sobrecupo_en_visitantes(): void
    {
        $capacidadMaxima = 3;

        // Ocupar los 3 cupos
        for ($i = 1; $i <= $capacidadMaxima; $i++) {
            $this->repository->guardarParqueadero([
                'copropiedad_id' => $this->copropiedadId,
                'unidad_id' => $this->unidadId,
                'tipo_espacio' => 'visitante',
                'placa_vehiculo' => "CAR-00{$i}",
                'estado' => 'activo',
                'ingreso_at' => Carbon::now(),
                'guarda_id' => $this->guardaId,
            ]);
        }

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Capacidad de parqueadero de visitantes colmada. Sobrecupo detectado.');

        $dtoExceso = new RegistrarParqueaderoDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            tipoEspacio: 'visitante',
            placaVehiculo: 'CAR-999',
            numeroBahia: null,
            guardaId: $this->guardaId
        );

        // Se pasa capacidad máxima de 3
        $this->service->registrarIngresoVehiculoConCapacidad($dtoExceso, $capacidadMaxima);
    }

    /**
     * [HU-SEC-07: Ingreso Permitido Dentro del Límite]
     * Si hay cupos libres, el registro se autoriza normalmente.
     */
    public function test_ingreso_vehicular_exitoso_cuando_hay_cupos_disponibles(): void
    {
        $dto = new RegistrarParqueaderoDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            tipoEspacio: 'visitante',
            placaVehiculo: 'QWE-456',
            numeroBahia: 'V-04',
            guardaId: $this->guardaId
        );

        $registro = $this->service->registrarIngresoVehiculoConCapacidad($dto, 10);

        $this->assertNotNull($registro->id);
        $this->assertEquals('QWE-456', $registro->placa_vehiculo);
        $this->assertEquals('activo', $registro->estado);
    }

    /**
     * [HU-SEC-08: Tarificación - Salida Dentro del Tiempo de Cortesía]
     * Regla: 2 horas de cortesía gratis.
     * Si el visitante sale a los 75 minutos (1h 15m), el valor liquidado es $0 COP.
     */
    public function test_liquidar_tarifa_dentro_del_tiempo_de_cortesia_retorna_valor_cero(): void
    {
        $ingreso = Carbon::now()->subMinutes(75); // 1 hora y 15 min

        $registro = $this->repository->guardarParqueadero([
            'copropiedad_id' => $this->copropiedadId,
            'unidad_id' => $this->unidadId,
            'tipo_espacio' => 'visitante',
            'placa_vehiculo' => 'COR-123',
            'estado' => 'activo',
            'ingreso_at' => $ingreso,
            'guarda_id' => $this->guardaId,
        ]);

        $politica = new PoliticaTarifariaDTO(
            horasCortesia: 2,
            tarifaPorHora: 2500,
            tarifaDiaMax: 35000
        );

        $liquidacion = $this->service->liquidarTarifaVehiculo($registro, $politica, Carbon::now());

        $this->assertTrue($liquidacion['es_cortesia']);
        $this->assertEquals(0.00, $liquidacion['valor_liquidado']);
        $this->assertEquals(0, $liquidacion['horas_cobrables']);
        $this->assertEquals(75, $liquidacion['minutos_permanencia']);
    }

    /**
     * [HU-SEC-08: Tarificación - Exceso de Tiempo y Generación de Cobro]
     * Regla: 2 horas cortesía gratis. Tarifa $2.500 COP por hora o fracción.
     * Estancia: 3 horas y 10 minutos (190 minutos).
     * Minutos cobrables: 190 - 120 = 70 minutos -> 2 horas cobrables -> $5.000 COP.
     */
    public function test_liquidar_tarifa_con_exceso_de_tiempo_calcula_horas_y_genera_referencia_de_pago(): void
    {
        $ingreso = Carbon::now()->subMinutes(190);

        $registro = $this->repository->guardarParqueadero([
            'copropiedad_id' => $this->copropiedadId,
            'unidad_id' => $this->unidadId,
            'tipo_espacio' => 'visitante',
            'placa_vehiculo' => 'EXC-789',
            'estado' => 'activo',
            'ingreso_at' => $ingreso,
            'guarda_id' => $this->guardaId,
        ]);

        $politica = new PoliticaTarifariaDTO(
            horasCortesia: 2,
            tarifaPorHora: 2500,
            tarifaDiaMax: 35000
        );

        $liquidacion = $this->service->liquidarTarifaVehiculo($registro, $politica, Carbon::now());

        $this->assertFalse($liquidacion['es_cortesia']);
        $this->assertEquals(5000.00, $liquidacion['valor_liquidado']);
        $this->assertEquals(2, $liquidacion['horas_cobrables']);
        $this->assertEquals(190, $liquidacion['minutos_permanencia']);
        $this->assertNotEmpty($liquidacion['referencia_pago']);
        $this->assertStringStartsWith('PKG-', $liquidacion['referencia_pago']);
    }

    /**
     * [HU-SEC-08: Salida Bloqueada si Tiene Saldo Pendiente]
     * Un vehículo con saldo liquidado > 0 no puede registrar salida sin haber pagado.
     */
    public function test_salida_bloqueada_si_vehiculo_tiene_saldo_pendiente_sin_pagar(): void
    {
        $registro = $this->repository->guardarParqueadero([
            'copropiedad_id' => $this->copropiedadId,
            'unidad_id' => $this->unidadId,
            'tipo_espacio' => 'visitante',
            'placa_vehiculo' => 'DEB-001',
            'estado' => 'activo',
            'ingreso_at' => Carbon::now()->subHours(4),
            'valor_liquidado' => 5000.00,
            'pagado_at' => null, // Saldo pendiente
            'guarda_id' => $this->guardaId,
        ]);

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('El vehículo tiene un saldo pendiente de $ 5,000 COP.');

        $this->service->marcarSalidaVehiculoConValidacionPago($registro);
    }

    /**
     * [HU-SEC-08: Pago y Salida Exitosa]
     * Tras registrar el pago (Wompi PSE o efectivo), la salida vehicular se autoriza.
     */
    public function test_procesar_pago_y_salida_vehicular_exitosa(): void
    {
        $registro = $this->repository->guardarParqueadero([
            'copropiedad_id' => $this->copropiedadId,
            'unidad_id' => $this->unidadId,
            'tipo_espacio' => 'visitante',
            'placa_vehiculo' => 'PAG-999',
            'estado' => 'activo',
            'ingreso_at' => Carbon::now()->subHours(3),
            'valor_liquidado' => 2500.00,
            'pagado_at' => null,
            'guarda_id' => $this->guardaId,
        ]);

        // Procesar pago
        $registroPagado = $this->service->procesarPagoParqueadero(
            registro: $registro,
            metodoPago: 'wompi_pse',
            referenciaPago: 'PKG-ABC123XYZ'
        );

        $this->assertNotNull($registroPagado->pagado_at);
        $this->assertEquals('wompi_pse', $registroPagado->metodo_pago);
        $this->assertEquals('PKG-ABC123XYZ', $registroPagado->referencia_pago);

        // Ahora la salida debe ser permitida
        $registroFinalizado = $this->service->marcarSalidaVehiculoConValidacionPago($registroPagado);
        $this->assertEquals('finalizado', $registroFinalizado->estado);
        $this->assertNotNull($registroFinalizado->salida_at);
    }
}
