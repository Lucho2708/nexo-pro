<?php

namespace Tests\Unit\Porteria;

use Tests\TestCase;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\MinutaPorteria;
use App\Modules\IAM\Models\User;
use NexoPro\Porteria\Contracts\PorteriaServiceInterface;
use NexoPro\Porteria\Contracts\PorteriaRepositoryInterface;
use NexoPro\Porteria\DTOs\RegistrarPaqueteDTO;
use NexoPro\Porteria\DTOs\RelevarTurnoDTO;
use NexoPro\Porteria\Services\PorteriaService;
use Illuminate\Support\Str;
use InvalidArgumentException;
use DomainException;

/**
 * [TDD - RED -> GREEN -> REFACTOR]
 * Pruebas unitarias para la Fase 1 del Módulo de Portería:
 * - HU-SEC-01: Relevo táctil de turno por PIN de 4 dígitos y registro automático en minuta.
 * - HU-SEC-03: Generación de PIN de entrega en paquetería y validación blindada de entrega.
 */
class PorteriaFase1ErgonomicsTest extends TestCase
{
    private PorteriaServiceInterface $service;
    private PorteriaRepositoryInterface $repository;
    private string $copropiedadId;
    private string $unidadId;
    private User $guardaSaliente;
    private User $guardaEntrante;

    protected function setUp(): void
    {
        parent::setUp();

        $this->copropiedadId = (string) Str::uuid();
        $this->unidadId = (string) Str::uuid();

        // Guarda saliente (actual en puesto)
        $this->guardaSaliente = new User([
            'id' => (string) Str::uuid(),
            'name' => 'Nelson Prada',
            'role' => 'security',
            'is_active' => true,
        ]);
        $this->guardaSaliente->id = (string) Str::uuid();

        // Guarda entrante (quien asume el turno con PIN 4812)
        $this->guardaEntrante = new User([
            'id' => (string) Str::uuid(),
            'name' => 'Rodrigo Morales',
            'role' => 'security',
            'is_active' => true,
        ]);
        $this->guardaEntrante->id = (string) Str::uuid();

        // Repositorio in-memory determinista
        $this->repository = new class($this->guardaEntrante) implements PorteriaRepositoryInterface {
            private User $guardaValido;
            public array $minutasRegistradas = [];
            public array $paquetes = [];

            public function __construct(User $guardaValido)
            {
                $this->guardaValido = $guardaValido;
            }

            public function guardarVisita(array $datos): \App\Modules\Operations\Models\Visita
            {
                $visita = new \App\Modules\Operations\Models\Visita($datos);
                $visita->id = (string) Str::uuid();
                return $visita;
            }

            public function actualizarVisita(\App\Modules\Operations\Models\Visita $visita, array $datos): \App\Modules\Operations\Models\Visita
            {
                $visita->fill($datos);
                return $visita;
            }

            public function guardarRecibo(array $datos): \App\Modules\Operations\Models\ReciboPublico
            {
                $recibo = new \App\Modules\Operations\Models\ReciboPublico($datos);
                $recibo->id = (string) Str::uuid();
                return $recibo;
            }

            public function guardarPaquete(array $datos): Paquete
            {
                $paquete = new Paquete($datos);
                $paquete->id = (string) Str::uuid();
                $paquete->exists = true;
                $this->paquetes[$paquete->id] = $paquete;
                return $paquete;
            }

            public function actualizarPaquete(Paquete $paquete, array $datos): Paquete
            {
                $paquete->fill($datos);
                $this->paquetes[$paquete->id] = $paquete;
                return $paquete;
            }

            public function guardarParqueadero(array $datos): \App\Modules\Operations\Models\ParqueaderoRegistro
            {
                $registro = new \App\Modules\Operations\Models\ParqueaderoRegistro($datos);
                $registro->id = (string) Str::uuid();
                return $registro;
            }

            public function actualizarParqueadero(\App\Modules\Operations\Models\ParqueaderoRegistro $registro, array $datos): \App\Modules\Operations\Models\ParqueaderoRegistro
            {
                $registro->fill($datos);
                return $registro;
            }

            public function contarVehiculosActivosPorTipo(string $copropiedadId, string $tipoEspacio): int
            {
                return 0;
            }

            public function buscarGuardaPorPin(string $copropiedadId, string $pin): ?User
            {
                if ($pin === '4812') {
                    return $this->guardaValido;
                }
                return null;
            }

            public function registrarMinuta(array $datos): MinutaPorteria
            {
                $minuta = new MinutaPorteria($datos);
                $minuta->id = (string) Str::uuid();
                $this->minutasRegistradas[] = $minuta;
                return $minuta;
            }

            public function guardarPaseVisita(array $datos): \App\Modules\Operations\Models\PaseVisita
            {
                $pase = new \App\Modules\Operations\Models\PaseVisita($datos);
                $pase->id = (string) Str::uuid();
                return $pase;
            }

            public function buscarPaseVisitaPorToken(string $token): ?\App\Modules\Operations\Models\PaseVisita
            {
                return null;
            }

            public function actualizarPaseVisita(\App\Modules\Operations\Models\PaseVisita $pase, array $datos): \App\Modules\Operations\Models\PaseVisita
            {
                $pase->fill($datos);
                return $pase;
            }

            public function guardarSolicitudCitofonia(array $datos): \App\Modules\Operations\Models\SolicitudCitofonia
            {
                $s = new \App\Modules\Operations\Models\SolicitudCitofonia($datos);
                $s->id = (string) Str::uuid();
                return $s;
            }

            public function buscarSolicitudCitofoniaPorToken(string $token): ?\App\Modules\Operations\Models\SolicitudCitofonia
            {
                return null;
            }

            public function actualizarSolicitudCitofonia(\App\Modules\Operations\Models\SolicitudCitofonia $solicitud, array $datos): \App\Modules\Operations\Models\SolicitudCitofonia
            {
                $solicitud->fill($datos);
                return $solicitud;
            }

            public function obtenerUltimaMinutaPorCopropiedad(string $copropiedadId): ?MinutaPorteria { return null; }
            public function listarMinutasPorCopropiedad(string $copropiedadId): array { return []; }
            public function obtenerCopropiedadesParaSupervision(?string $supervisorId = null): array { return []; }
        };

        $this->service = new PorteriaService($this->repository);
    }

    /**
     * [HU-SEC-01: Relevo Táctil de Turno por PIN]
     * Al ingresar el PIN de 4 dígitos correcto, el sistema registra la novedad de
     * cambio de turno en la minuta y devuelve el guarda entrante.
     */
    public function test_relevar_turno_exitoso_con_pin_registra_minuta_y_actualiza_operador(): void
    {
        $dto = new RelevarTurnoDTO(
            copropiedadId: $this->copropiedadId,
            guardaSalienteId: $this->guardaSaliente->id,
            guardaSalienteNombre: $this->guardaSaliente->name,
            pinEntrante: '4812'
        );

        $resultado = $this->service->relevarTurno($dto);

        $this->assertTrue($resultado['success']);
        $this->assertEquals($this->guardaEntrante->id, $resultado['guarda_entrante_id']);
        $this->assertEquals('Rodrigo Morales', $resultado['guarda_entrante_nombre']);

        // Verificar que se haya registrado en la minuta
        $this->assertCount(1, $this->repository->minutasRegistradas);
        $minuta = $this->repository->minutasRegistradas[0];
        $this->assertEquals('cambio_turno', $minuta->tipo_evento);
        $this->assertStringContainsString('Nelson Prada', $minuta->descripcion);
        $this->assertStringContainsString('Rodrigo Morales', $minuta->descripcion);
    }

    /**
     * [HU-SEC-01: Edge Case]
     * Si el PIN es incorrecto o de longitud errónea, lanza excepción de dominio.
     */
    public function test_relevar_turno_con_pin_invalido_arroja_excepcion(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('El PIN ingresado no corresponde a ningún guarda activo.');

        $dto = new RelevarTurnoDTO(
            copropiedadId: $this->copropiedadId,
            guardaSalienteId: $this->guardaSaliente->id,
            guardaSalienteNombre: $this->guardaSaliente->name,
            pinEntrante: '9999'
        );

        $this->service->relevarTurno($dto);
    }

    /**
     * [HU-SEC-03: Paquetería con PIN Secreto de Entrega]
     * Al recepcionar un paquete, el sistema genera automáticamente un PIN secreto
     * de 4 dígitos numéricos para retiro seguro por el residente.
     */
    public function test_recepcion_paquete_genera_pin_de_entrega_automatico(): void
    {
        $dto = new RegistrarPaqueteDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            empresaMensajeria: 'Mercado Libre Envíos',
            codigoGuia: 'MELI-482910',
            descripcion: 'Caja mediana de tecnología',
            guardaId: $this->guardaSaliente->id
        );

        $paquete = $this->service->recepcionarPaquete($dto);

        $this->assertNotNull($paquete->pin_entrega);
        $this->assertMatchesRegularExpression('/^[0-9]{4}$/', $paquete->pin_entrega);
        $this->assertEquals('recibido', $paquete->estado);
    }

    /**
     * [HU-SEC-03: Entrega Blindada con PIN]
     * Al entregar un paquete con el PIN exacto, el estado cambia a 'entregado'.
     */
    public function test_entregar_paquete_con_pin_correcto_marca_entregado(): void
    {
        $dto = new RegistrarPaqueteDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            empresaMensajeria: 'Amazon',
            codigoGuia: 'AMZ-882190',
            descripcion: 'Sobre acolchado',
            guardaId: $this->guardaSaliente->id
        );

        $paquete = $this->service->recepcionarPaquete($dto);
        $pinGenerado = $paquete->pin_entrega;

        $paqueteEntregado = $this->service->entregarPaqueteConPin(
            paquete: $paquete,
            pin: $pinGenerado,
            entregadoANombre: 'Claudia Restrepo'
        );

        $this->assertEquals('entregado', $paqueteEntregado->estado);
        $this->assertEquals('Claudia Restrepo', $paqueteEntregado->entregado_a_nombre);
        $this->assertNotNull($paqueteEntregado->entregado_at);
    }

    /**
     * [HU-SEC-03: Edge Case Entrega con PIN Erróneo]
     * Si el residente o visitante dicta un PIN equivocado, se bloquea la entrega.
     */
    public function test_entregar_paquete_con_pin_incorrecto_arroja_excepcion(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('El PIN de entrega es incorrecto.');

        $dto = new RegistrarPaqueteDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            empresaMensajeria: 'Coordinadora',
            codigoGuia: 'CRD-102938',
            descripcion: 'Paquete frágil',
            guardaId: $this->guardaSaliente->id
        );

        $paquete = $this->service->recepcionarPaquete($dto);

        $this->service->entregarPaqueteConPin(
            paquete: $paquete,
            pin: '0000', // PIN erróneo
            entregadoANombre: 'Intruso Desconocido'
        );
    }
}
