<?php

namespace Tests\Unit\Porteria;

use Tests\TestCase;
use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use NexoPro\Porteria\Contracts\PorteriaServiceInterface;
use NexoPro\Porteria\Contracts\PorteriaRepositoryInterface;
use NexoPro\Porteria\DTOs\RegistrarVisitaDTO;
use NexoPro\Porteria\DTOs\RegistrarReciboDTO;
use NexoPro\Porteria\DTOs\RegistrarPaqueteDTO;
use NexoPro\Porteria\DTOs\RegistrarParqueaderoDTO;
use NexoPro\Porteria\Services\PorteriaService;
use Illuminate\Support\Str;
use Mockery;

class PorteriaOperationsTest extends TestCase
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

        // Implementación in-memory del repositorio para pruebas unitarias deterministas
        $this->repository = new class implements PorteriaRepositoryInterface {
            public function guardarVisita(array $datos): Visita
            {
                $visita = new Visita($datos);
                $visita->id = (string) Str::uuid();
                $visita->exists = true;
                return $visita;
            }

            public function actualizarVisita(Visita $visita, array $datos): Visita
            {
                $visita->fill($datos);
                return $visita;
            }

            public function guardarRecibo(array $datos): ReciboPublico
            {
                $recibo = new ReciboPublico($datos);
                $recibo->id = (string) Str::uuid();
                $recibo->exists = true;
                return $recibo;
            }

            public function guardarPaquete(array $datos): Paquete
            {
                $paquete = new Paquete($datos);
                $paquete->id = (string) Str::uuid();
                $paquete->exists = true;
                return $paquete;
            }

            public function actualizarPaquete(Paquete $paquete, array $datos): Paquete
            {
                $paquete->fill($datos);
                return $paquete;
            }

            public function guardarParqueadero(array $datos): ParqueaderoRegistro
            {
                $registro = new ParqueaderoRegistro($datos);
                $registro->id = (string) Str::uuid();
                $registro->exists = true;
                return $registro;
            }

            public function actualizarParqueadero(ParqueaderoRegistro $registro, array $datos): ParqueaderoRegistro
            {
                $registro->fill($datos);
                return $registro;
            }

            public function contarVehiculosActivosPorTipo(string $copropiedadId, string $tipoEspacio): int
            {
                return 0;
            }

            public function buscarGuardaPorPin(string $copropiedadId, string $pin): ?\App\Modules\IAM\Models\User
            {
                return null;
            }

            public function registrarMinuta(array $datos): \App\Modules\Operations\Models\MinutaPorteria
            {
                $minuta = new \App\Modules\Operations\Models\MinutaPorteria($datos);
                $minuta->id = (string) Str::uuid();
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

            public function obtenerUltimaMinutaPorCopropiedad(string $copropiedadId): ?\App\Modules\Operations\Models\MinutaPorteria { return null; }
            public function listarMinutasPorCopropiedad(string $copropiedadId): array { return []; }
            public function obtenerCopropiedadesParaSupervision(?string $supervisorId = null): array { return []; }
        };

        $this->service = new PorteriaService($this->repository);
    }

    /**
     * [LÓGICA DE NEGOCIO / UX]
     * Permite al guarda registrar el ingreso de un visitante con cédula y nombre,
     * calculando su estado como 'ingresado' para control en garita.
     */
    public function test_registrar_visita_exitosamente_con_estado_ingresado(): void
    {
        $dto = new RegistrarVisitaDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            visitanteNombre: 'Carlos Alberto Perez',
            visitanteDocumento: '1020304050',
            tipoVisita: 'personal',
            placaVehiculo: 'XYZ-789',
            empresaServicio: null,
            preAutorizado: true,
            guardaId: $this->guardaId,
            observaciones: 'Lleva paquete pequeño'
        );

        $visita = $this->service->registrarVisita($dto);

        $this->assertNotNull($visita->id);
        $this->assertEquals('Carlos Alberto Perez', $visita->visitante_nombre);
        $this->assertEquals('1020304050', $visita->visitante_documento);
        $this->assertEquals('ingresado', $visita->estado);
        $this->assertTrue($visita->pre_autorizado);
    }

    /**
     * [LÓGICA DE NEGOCIO / UX]
     * Al registrar la llegada de un recibo público, queda registrado en el casillero
     * con estado 'en_casillero' para posterior entrega al residente.
     */
    public function test_registrar_recibo_publico_en_casillero(): void
    {
        $dto = new RegistrarReciboDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            tipoServicio: 'acueducto',
            empresaEmisora: 'Empresa de Acueducto de Bogotá',
            periodoFacturado: 'Octubre 2026',
            guardaId: $this->guardaId
        );

        $recibo = $this->service->registrarRecibo($dto);

        $this->assertNotNull($recibo->id);
        $this->assertEquals('acueducto', $recibo->tipo_servicio);
        $this->assertEquals('en_casillero', $recibo->estado);
        $this->assertEquals('Octubre 2026', $recibo->periodo_facturado);
    }

    /**
     * [LÓGICA DE NEGOCIO / UX]
     * Registra una encomienda recibida y permite su entrega formal al residente.
     */
    public function test_recepcion_y_entrega_de_paqueteria(): void
    {
        $dto = new RegistrarPaqueteDTO(
            copropiedadId: $this->copropiedadId,
            unidadId: $this->unidadId,
            empresaMensajeria: 'Servientrega',
            codigoGuia: 'SER-994821',
            descripcion: 'Sobre manila documentos',
            guardaId: $this->guardaId
        );

        $paquete = $this->service->recepcionarPaquete($dto);

        $this->assertEquals('recibido', $paquete->estado);
        $this->assertEquals('SER-994821', $paquete->codigo_guia);

        // Entregar paquete
        $paqueteEntregado = $this->service->entregarPaquete($paquete, 'María Fernanda Gomez');
        $this->assertEquals('entregado', $paqueteEntregado->estado);
        $this->assertEquals('María Fernanda Gomez', $paqueteEntregado->entregado_a_nombre);
        $this->assertNotNull($paqueteEntregado->entregado_at);
    }

    /**
     * [LÓGICA DE NEGOCIO / UX]
     * Calcula la disponibilidad de parqueaderos restando los cupos ocupados del aforo total.
     */
    public function test_calculo_de_aforo_de_parqueaderos(): void
    {
        $capacidadTotal = 20;
        $cuposOcupados = 14;

        $disponibilidad = $this->service->calcularDisponibilidadParqueadero($capacidadTotal, $cuposOcupados);

        $this->assertEquals(20, $disponibilidad['capacidad_total']);
        $this->assertEquals(14, $disponibilidad['ocupados']);
        $this->assertEquals(6, $disponibilidad['disponibles']);
        $this->assertEquals(70.0, $disponibilidad['porcentaje_ocupacion']);
        $this->assertFalse($disponibilidad['alerta_sobrecupo']);
    }

    /**
     * [EDGE CASE]
     * Detecta sobrecupo cuando los vehículos superan la capacidad establecida.
     */
    public function test_detecta_sobrecupo_en_parqueadero(): void
    {
        $disponibilidad = $this->service->calcularDisponibilidadParqueadero(10, 10);
        $this->assertEquals(0, $disponibilidad['disponibles']);
        $this->assertTrue($disponibilidad['alerta_sobrecupo']);
    }
}
