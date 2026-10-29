<?php

namespace Tests\Unit\Porteria;

use Tests\TestCase;
use NexoPro\Porteria\Services\PorteriaService;
use NexoPro\Porteria\Contracts\PorteriaRepositoryInterface;
use NexoPro\Porteria\DTOs\RegistrarMinutaForenseDTO;
use App\Modules\Operations\Models\MinutaPorteria;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use App\Modules\Operations\Models\PaseVisita;
use App\Modules\Operations\Models\SolicitudCitofonia;
use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\IAM\Models\User;
use Carbon\Carbon;
use DomainException;

/**
 * Suite TDD Fase 4: Minuta Digital Forense Inmutable & Portal B2B para Supervisores
 * Cumplimiento estricto de estándares SuperVigilancia y principios SOLID.
 */
class PorteriaFase4ForensicAndB2BTest extends TestCase
{
    private PorteriaRepositoryInterface $repositoryDouble;
    private PorteriaService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Carbon::setTestNow(Carbon::parse('2026-10-28 20:15:00'));

        $this->repositoryDouble = new class implements PorteriaRepositoryInterface {
            public array $minutas = [];
            public array $copropiedades = [
                [
                    'id' => 'copropiedad-norte-01',
                    'nombre' => 'Residencial Altos del Norte',
                    'ciudad' => 'Bogotá',
                    'direccion' => 'Calle 140 # 11-45',
                    'unidades_totales' => 120,
                ],
                [
                    'id' => 'copropiedad-sur-02',
                    'nombre' => 'Torres del Parque Sur',
                    'ciudad' => 'Bogotá',
                    'direccion' => 'Carrera 68 # 45-20 Sur',
                    'unidades_totales' => 240,
                ],
            ];

            public function guardarVisita(array $datos): Visita { return new Visita(); }
            public function buscarVisitaPorId(string $id): ?Visita { return null; }
            public function actualizarVisita(Visita $visita, array $datos): Visita { return $visita; }
            public function listarVisitasActivas(string $copropiedadId): array { return []; }
            public function guardarRecibo(array $datos): ReciboPublico { return new ReciboPublico(); }
            public function guardarPaquete(array $datos): Paquete { return new Paquete(); }
            public function actualizarPaquete(Paquete $paquete, array $datos): Paquete { return $paquete; }
            public function guardarParqueadero(array $datos): ParqueaderoRegistro { return new ParqueaderoRegistro(); }
            public function actualizarParqueadero(ParqueaderoRegistro $registro, array $datos): ParqueaderoRegistro { return $registro; }
            public function contarVehiculosActivosPorTipo(string $copropiedadId, string $tipoEspacio): int { return 0; }
            public function buscarGuardaPorPin(string $copropiedadId, string $pin): ?User { return null; }
            public function guardarPaseVisita(array $datos): PaseVisita { return new PaseVisita(); }
            public function buscarPaseVisitaPorToken(string $token): ?PaseVisita { return null; }
            public function actualizarPaseVisita(PaseVisita $pase, array $datos): PaseVisita { return $pase; }
            public function guardarSolicitudCitofonia(array $datos): SolicitudCitofonia { return new SolicitudCitofonia(); }
            public function buscarSolicitudCitofoniaPorToken(string $token): ?SolicitudCitofonia { return null; }
            public function actualizarSolicitudCitofonia(SolicitudCitofonia $solicitud, array $datos): SolicitudCitofonia { return $solicitud; }

            public function registrarMinuta(array $datos): MinutaPorteria
            {
                $minuta = new MinutaPorteria();
                $minuta->forceFill(array_merge([
                    'id' => 'minuta-' . uniqid(),
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ], $datos));

                $this->minutas[] = $minuta;
                return $minuta;
            }

            public function obtenerUltimaMinutaPorCopropiedad(string $copropiedadId): ?MinutaPorteria
            {
                $copropiedadMinutas = array_filter($this->minutas, fn($m) => $m->copropiedad_id === $copropiedadId);
                if (empty($copropiedadMinutas)) {
                    return null;
                }
                return end($copropiedadMinutas);
            }

            public function listarMinutasPorCopropiedad(string $copropiedadId): array
            {
                return array_values(array_filter($this->minutas, fn($m) => $m->copropiedad_id === $copropiedadId));
            }

            public function obtenerCopropiedadesParaSupervision(?string $supervisorId = null): array
            {
                return $this->copropiedades;
            }
        };

        $this->service = new PorteriaService($this->repositoryDouble);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    /**
     * HU-SEC-09: Registro de minuta forense con hash SHA-256 encadenado y evidencia fotográfica
     */
    public function test_registrar_minuta_forense_genera_hash_criptografico_encadenado(): void
    {
        $dto1 = new RegistrarMinutaForenseDTO(
            copropiedadId: 'copropiedad-norte-01',
            guardaId: 'guarda-01',
            tipoEvento: 'cambio_turno',
            prioridad: 'informativo',
            descripcion: 'Relevo sin novedades de turno diurno a nocturno.',
            evidenciaFotoPath: null
        );

        $minuta1 = $this->service->registrarMinutaForense($dto1);

        $this->assertNotEmpty($minuta1->hash_inmutable);
        $this->assertSame(64, strlen($minuta1->hash_inmutable));
        $this->assertNotEmpty($minuta1->prev_hash);
        $this->assertTrue($minuta1->firmado_digitalmente);

        // Segundo registro debe encadenar el prev_hash con el hash_inmutable del registro 1
        Carbon::setTestNow(Carbon::parse('2026-10-28 20:30:00'));

        $dto2 = new RegistrarMinutaForenseDTO(
            copropiedadId: 'copropiedad-norte-01',
            guardaId: 'guarda-01',
            tipoEvento: 'ronda',
            prioridad: 'informativo',
            descripcion: 'Ronda perimetral de torre 1 y sótano completada.',
            evidenciaFotoPath: 'evidencias/ronda_torre_1.jpg'
        );

        $minuta2 = $this->service->registrarMinutaForense($dto2);

        $this->assertSame($minuta1->hash_inmutable, $minuta2->prev_hash);
        $this->assertNotSame($minuta1->hash_inmutable, $minuta2->hash_inmutable);
        $this->assertSame('evidencias/ronda_torre_1.jpg', $minuta2->evidencia_foto_path);
    }

    /**
     * HU-SEC-09: Verificación de integridad forense de la cadena criptográfica
     */
    public function test_verificar_integridad_forense_valida_cadena_intacta(): void
    {
        $dto1 = new RegistrarMinutaForenseDTO(
            copropiedadId: 'copropiedad-norte-01',
            guardaId: 'guarda-01',
            tipoEvento: 'cambio_turno',
            prioridad: 'informativo',
            descripcion: 'Inicio de turno',
            evidenciaFotoPath: null
        );
        $this->service->registrarMinutaForense($dto1);

        $dto2 = new RegistrarMinutaForenseDTO(
            copropiedadId: 'copropiedad-norte-01',
            guardaId: 'guarda-01',
            tipoEvento: 'ronda',
            prioridad: 'advertencia',
            descripcion: 'Lámpara de sótano fundida',
            evidenciaFotoPath: null
        );
        $this->service->registrarMinutaForense($dto2);

        $resultado = $this->service->verificarIntegridadCadenaMinuta('copropiedad-norte-01');

        $this->assertTrue($resultado['es_valida']);
        $this->assertSame(2, $resultado['bloques_verificados']);
        $this->assertNull($resultado['bloque_corrupto_id']);
    }

    /**
     * HU-SEC-09: Verificación de integridad forense detecta adulteración si se altera un registro
     */
    public function test_verificar_integridad_detecta_adulteracion_en_bitacora(): void
    {
        $dto1 = new RegistrarMinutaForenseDTO(
            copropiedadId: 'copropiedad-norte-01',
            guardaId: 'guarda-01',
            tipoEvento: 'cambio_turno',
            prioridad: 'informativo',
            descripcion: 'Original texto',
            evidenciaFotoPath: null
        );
        $minuta1 = $this->service->registrarMinutaForense($dto1);

        $dto2 = new RegistrarMinutaForenseDTO(
            copropiedadId: 'copropiedad-norte-01',
            guardaId: 'guarda-01',
            tipoEvento: 'novedad',
            prioridad: 'advertencia',
            descripcion: 'Novedad posterior',
            evidenciaFotoPath: null
        );
        $this->service->registrarMinutaForense($dto2);

        // Simulamos un ataque o corrupción directa en base de datos alterando la descripción del registro 1
        $minuta1->descripcion = 'Texto alterado maliciosamente por un atacante';

        $resultado = $this->service->verificarIntegridadCadenaMinuta('copropiedad-norte-01');

        $this->assertFalse($resultado['es_valida']);
        $this->assertSame($minuta1->id, $resultado['bloque_corrupto_id']);
    }

    /**
     * HU-SEC-09: Incidentes urgentes disparan alerta inmediata de supervisión
     */
    public function test_incidente_urgente_dispara_alerta_inmediata(): void
    {
        $dtoUrgente = new RegistrarMinutaForenseDTO(
            copropiedadId: 'copropiedad-norte-01',
            guardaId: 'guarda-01',
            tipoEvento: 'incidente',
            prioridad: 'urgente',
            descripcion: 'Intento de ingreso no autorizado por reja perimetral vehicular.',
            evidenciaFotoPath: 'evidencias/foto_reja.jpg'
        );

        $resultado = $this->service->registrarMinutaForense($dtoUrgente);

        $this->assertSame('urgente', $resultado->prioridad);
        $this->assertTrue($this->service->tieneAlertaUrgenteActiva('copropiedad-norte-01'));
    }

    /**
     * HU-SEC-10: Dashboard B2B para supervisores de vigilancia calcula KPIs y semáforos de puestos
     */
    public function test_dashboard_b2b_calcula_metricas_y_semaforos_de_garitas(): void
    {
        // Garita 1: Residencial Altos del Norte con novedad urgente activa -> Estado CRITICO
        $dto1 = new RegistrarMinutaForenseDTO(
            copropiedadId: 'copropiedad-norte-01',
            guardaId: 'guarda-01',
            tipoEvento: 'incidente',
            prioridad: 'urgente',
            descripcion: 'Falla crítica en cerradura principal',
            evidenciaFotoPath: null
        );
        $this->service->registrarMinutaForense($dto1);

        // Garita 2: Torres del Parque Sur con relevo normal reciente -> Estado NORMAL
        $dto2 = new RegistrarMinutaForenseDTO(
            copropiedadId: 'copropiedad-sur-02',
            guardaId: 'guarda-02',
            tipoEvento: 'cambio_turno',
            prioridad: 'informativo',
            descripcion: 'Relevo ordinario efectuado correctamente',
            evidenciaFotoPath: null
        );
        $this->service->registrarMinutaForense($dto2);

        $dashboard = $this->service->obtenerDashboardSupervisionB2B('supervisor-100');

        $this->assertSame(2, $dashboard['kpis']['total_puestos']);
        $this->assertSame(1, $dashboard['kpis']['puestos_operativos']);
        $this->assertSame(1, $dashboard['kpis']['alertas_criticas']);

        $puestoNorte = collect($dashboard['puestos'])->firstWhere('id', 'copropiedad-norte-01');
        $this->assertSame('CRITICO', $puestoNorte['estado_semaforo']);
        $this->assertSame(1, $puestoNorte['novedades_urgentes_24h']);

        $puestoSur = collect($dashboard['puestos'])->firstWhere('id', 'copropiedad-sur-02');
        $this->assertSame('NORMAL', $puestoSur['estado_semaforo']);
        $this->assertSame(0, $puestoSur['novedades_urgentes_24h']);
    }
}
