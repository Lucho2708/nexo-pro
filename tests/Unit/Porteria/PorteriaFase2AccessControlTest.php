<?php

namespace Tests\Unit\Porteria;

use Tests\TestCase;
use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\PaseVisita;
use App\Modules\Operations\Models\SolicitudCitofonia;
use App\Modules\IAM\Models\User;
use NexoPro\Porteria\Contracts\PorteriaServiceInterface;
use NexoPro\Porteria\Contracts\PorteriaRepositoryInterface;
use NexoPro\Porteria\DTOs\CrearPaseVisitaDTO;
use NexoPro\Porteria\DTOs\ValidarPaseVisitaDTO;
use NexoPro\Porteria\DTOs\SolicitarCitofoniaDTO;
use NexoPro\Porteria\DTOs\RegistrarVisitaDTO;
use NexoPro\Porteria\Services\PorteriaService;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DomainException;

/**
 * [TDD - RED -> GREEN -> REFACTOR]
 * Pruebas unitarias para la Fase 2 del Módulo de Portería:
 * - HU-SEC-04: Pases de visita digitales y pre-autorización por QR/PIN.
 * - HU-SEC-05: Citofonía virtual interactiva (WhatsApp / Push).
 * - HU-SEC-06: Control express de domiciliarios y temporizador de permanencia.
 */
class PorteriaFase2AccessControlTest extends TestCase
{
    private PorteriaServiceInterface $service;
    private PorteriaRepositoryInterface $repository;
    private string $copropiedadId;
    private string $unidadId;
    private string $residenteId;
    private string $guardaId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->copropiedadId = (string) Str::uuid();
        $this->unidadId = (string) Str::uuid();
        $this->residenteId = (string) Str::uuid();
        $this->guardaId = (string) Str::uuid();

        // Repositorio in-memory determinista para la Fase 2
        $this->repository = new class implements PorteriaRepositoryInterface {
            public array $pases = [];
            public array $visitas = [];
            public array $citofonias = [];

            public function guardarVisita(array $datos): Visita
            {
                $visita = new Visita($datos);
                $visita->id = (string) Str::uuid();
                $visita->exists = true;
                $this->visitas[$visita->id] = $visita;
                return $visita;
            }

            public function actualizarVisita(Visita $visita, array $datos): Visita
            {
                $visita->fill($datos);
                $this->visitas[$visita->id] = $visita;
                return $visita;
            }

            public function guardarRecibo(array $datos): \App\Modules\Operations\Models\ReciboPublico
            {
                $recibo = new \App\Modules\Operations\Models\ReciboPublico($datos);
                $recibo->id = (string) Str::uuid();
                return $recibo;
            }

            public function guardarPaquete(array $datos): \App\Modules\Operations\Models\Paquete
            {
                $paquete = new \App\Modules\Operations\Models\Paquete($datos);
                $paquete->id = (string) Str::uuid();
                return $paquete;
            }

            public function actualizarPaquete(\App\Modules\Operations\Models\Paquete $paquete, array $datos): \App\Modules\Operations\Models\Paquete
            {
                $paquete->fill($datos);
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
                return null;
            }

            public function registrarMinuta(array $datos): \App\Modules\Operations\Models\MinutaPorteria
            {
                $minuta = new \App\Modules\Operations\Models\MinutaPorteria($datos);
                $minuta->id = (string) Str::uuid();
                return $minuta;
            }

            public function guardarPaseVisita(array $datos): PaseVisita
            {
                $pase = new PaseVisita($datos);
                $pase->id = (string) Str::uuid();
                $pase->exists = true;
                $this->pases[$pase->id] = $pase;
                return $pase;
            }

            public function buscarPaseVisitaPorToken(string $token): ?PaseVisita
            {
                foreach ($this->pases as $pase) {
                    if ($pase->token_acceso === $token || $pase->codigo_pin === $token) {
                        return $pase;
                    }
                }
                return null;
            }

            public function actualizarPaseVisita(PaseVisita $pase, array $datos): PaseVisita
            {
                $pase->fill($datos);
                $this->pases[$pase->id] = $pase;
                return $pase;
            }

            public function guardarSolicitudCitofonia(array $datos): SolicitudCitofonia
            {
                $solicitud = new SolicitudCitofonia($datos);
                $solicitud->id = (string) Str::uuid();
                $solicitud->exists = true;
                $this->citofonias[$solicitud->id] = $solicitud;
                return $solicitud;
            }

            public function buscarSolicitudCitofoniaPorToken(string $token): ?SolicitudCitofonia
            {
                foreach ($this->citofonias as $solicitud) {
                    if ($solicitud->token_respuesta === $token) {
                        return $solicitud;
                    }
                }
                return null;
            }

            public function actualizarSolicitudCitofonia(SolicitudCitofonia $solicitud, array $datos): SolicitudCitofonia
            {
                $solicitud->fill($datos);
                $this->citofonias[$solicitud->id] = $solicitud;
                return $solicitud;
            }

            public function obtenerUltimaMinutaPorCopropiedad(string $copropiedadId): ?\App\Modules\Operations\Models\MinutaPorteria { return null; }
            public function listarMinutasPorCopropiedad(string $copropiedadId): array { return []; }
            public function obtenerCopropiedadesParaSupervision(?string $supervisorId = null): array { return []; }
        };

        $this->service = new PorteriaService($this->repository);
    }

    /**
     * [HU-SEC-04: Creación de Pase de Visita con QR]
     * Un residente genera un pase temporal de visita que crea un token criptográfico y un PIN de 6 caracteres.
     */
    public function test_crear_pase_visita_exitoso_genera_token_y_pin(): void
    {
        $dto = new CrearPaseVisitaDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            residenteId: $this->residenteId,
            visitanteNombre: 'Juliana Morales',
            visitanteDocumento: '52984123',
            placaVehiculo: 'UVW-123',
            tipoPase: 'individual',
            validoDesde: Carbon::now()->subMinutes(10),
            validoHasta: Carbon::now()->addHours(6),
            usosPermitidos: 1
        );

        $pase = $this->service->crearPaseVisita($dto);

        $this->assertNotNull($pase->id);
        $this->assertNotEmpty($pase->token_acceso);
        $this->assertNotEmpty($pase->codigo_pin);
        $this->assertEquals('activo', $pase->estado);
        $this->assertEquals(0, $pase->usos_consumidos);
        $this->assertTrue($pase->estaVigente());
    }

    /**
     * [HU-SEC-04: Canje de Pase QR en Garita]
     * El visitante presenta su QR o PIN en garita; el guarda lo valida, registra el ingreso
     * en Visitas con pre_autorizado: true y consume un uso del pase.
     */
    public function test_validar_ingreso_con_pase_qr_vigente_registra_visita_y_consume_uso(): void
    {
        $dtoCrear = new CrearPaseVisitaDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            residenteId: $this->residenteId,
            visitanteNombre: 'Esteban Quintero',
            visitanteDocumento: '1098472819',
            placaVehiculo: null,
            tipoPase: 'individual',
            validoDesde: Carbon::now()->subMinutes(5),
            validoHasta: Carbon::now()->addHours(4),
            usosPermitidos: 1
        );

        $pase = $this->service->crearPaseVisita($dtoCrear);

        $dtoValidar = new ValidarPaseVisitaDTO(
            tokenOPin: $pase->token_acceso,
            guardaId: $this->guardaId
        );

        $visita = $this->service->validarIngresoConPase($dtoValidar);

        $this->assertNotNull($visita->id);
        $this->assertEquals('Esteban Quintero', $visita->visitante_nombre);
        $this->assertEquals('ingresado', $visita->estado);
        $this->assertTrue($visita->pre_autorizado);
        $this->assertEquals($pase->id, $visita->pase_visita_id);

        // Verificar que el pase consumió su uso
        $this->assertEquals(1, $pase->usos_consumidos);
        $this->assertEquals('agotado', $pase->estado);
    }

    /**
     * [HU-SEC-04: Edge Case Pase Expirado]
     * Si el visitante llega después de la fecha límite, se bloquea el canje.
     */
    public function test_validar_ingreso_con_pase_vencido_arroja_excepcion_de_dominio(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('El pase de visita ha expirado.');

        $dtoCrear = new CrearPaseVisitaDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            residenteId: $this->residenteId,
            visitanteNombre: 'Visitante Tardío',
            visitanteDocumento: '12345678',
            placaVehiculo: null,
            tipoPase: 'individual',
            validoDesde: Carbon::now()->subHours(5),
            validoHasta: Carbon::now()->subHour(), // Expiró hace 1 hora
            usosPermitidos: 1
        );

        $pase = $this->service->crearPaseVisita($dtoCrear);

        $this->service->validarIngresoConPase(new ValidarPaseVisitaDTO(
            tokenOPin: $pase->token_acceso,
            guardaId: $this->guardaId
        ));
    }

    /**
     * [HU-SEC-05: Citofonía Virtual Interactiva]
     * Al solicitar autorización para una visita no programada, se crea una solicitud pendiente
     * y el residente puede autorizar o denegar con 1 clic.
     */
    public function test_solicitar_citofonia_virtual_y_autorizacion_del_residente(): void
    {
        $dtoSolicitud = new SolicitarCitofoniaDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            guardaId: $this->guardaId,
            visitanteNombre: 'Gas Natural Vanti Técnico',
            visitanteDocumento: '79841234',
            tipoVisita: 'contratista'
        );

        $solicitud = $this->service->solicitarCitofoniaVirtual($dtoSolicitud);

        $this->assertNotNull($solicitud->id);
        $this->assertEquals('pendiente', $solicitud->estado);
        $this->assertNotEmpty($solicitud->token_respuesta);

        // Residente responde "Autorizar"
        $solicitudRespondida = $this->service->responderCitofoniaVirtual(
            tokenRespuesta: $solicitud->token_respuesta,
            respuesta: 'autorizado',
            respondidoPorId: $this->residenteId
        );

        $this->assertEquals('autorizado', $solicitudRespondida->estado);
        $this->assertEquals($this->residenteId, $solicitudRespondida->respondido_por_id);
        $this->assertNotNull($solicitudRespondida->respondido_at);
    }

    /**
     * [HU-SEC-06: Control Express de Domiciliarios y Temporizador]
     * Al registrar un domicilio, se asigna automáticamente tiempo límite de 15 minutos
     * y se detecta si la permanencia ha sido excedida.
     */
    public function test_registrar_domicilio_express_asigna_tiempo_limite_y_detecta_exceso(): void
    {
        $dto = new RegistrarVisitaDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            visitanteNombre: 'Repartidor Rappi',
            visitanteDocumento: '1034829102',
            tipoVisita: 'domicilio',
            placaVehiculo: 'XYZ-01D',
            empresaServicio: 'Rappi',
            preAutorizado: false,
            guardaId: $this->guardaId,
            observaciones: 'Lleva pedido restaurante'
        );

        $visita = $this->service->registrarVisita($dto);

        $this->assertEquals('domicilio', $visita->tipo_visita);
        $this->assertEquals(15, $visita->tiempo_limite_minutos);

        // Verificación de permanencia normal (a los 5 minutos)
        $hace5Minutos = Carbon::now()->addMinutes(5);
        $this->assertFalse($this->service->esTiempoPermanenciaExcedido($visita, $hace5Minutos));

        // Verificación de permanencia excedida (a los 20 minutos)
        $hace20Minutos = Carbon::now()->addMinutes(20);
        $this->assertTrue($this->service->esTiempoPermanenciaExcedido($visita, $hace20Minutos));
    }
}
