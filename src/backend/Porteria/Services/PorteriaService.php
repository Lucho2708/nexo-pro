<?php

namespace NexoPro\Porteria\Services;

use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use App\Modules\Operations\Models\PaseVisita;
use App\Modules\Operations\Models\SolicitudCitofonia;
use NexoPro\Porteria\Contracts\PorteriaServiceInterface;
use NexoPro\Porteria\Contracts\PorteriaRepositoryInterface;
use NexoPro\Porteria\Repositories\PorteriaRepository;
use NexoPro\Porteria\DTOs\RegistrarVisitaDTO;
use NexoPro\Porteria\DTOs\RegistrarReciboDTO;
use NexoPro\Porteria\DTOs\RegistrarPaqueteDTO;
use NexoPro\Porteria\DTOs\RegistrarParqueaderoDTO;
use NexoPro\Porteria\DTOs\RelevarTurnoDTO;
use NexoPro\Porteria\DTOs\CrearPaseVisitaDTO;
use NexoPro\Porteria\DTOs\ValidarPaseVisitaDTO;
use NexoPro\Porteria\DTOs\SolicitarCitofoniaDTO;
use NexoPro\Porteria\DTOs\PoliticaTarifariaDTO;
use NexoPro\Porteria\DTOs\RegistrarMinutaForenseDTO;
use App\Modules\Operations\Models\MinutaPorteria;
use Carbon\Carbon;
use Illuminate\Support\Str;
use DomainException;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Servicio central de garita y seguridad para conjuntos residenciales.
 * Orquesta el registro de visitantes, notificación de recibos públicos y correspondencia,
 * y cálculo en tiempo real de disponibilidad de parqueaderos.
 *
 * [ASPECTO TÉCNICO / UI]
 * Implementación desacoplada de Clean Architecture que cumple estrictamente con SOLID:
 * - SRP: Exclusivamente gestiona la lógica de operaciones de portería.
 * - OCP: Extensible para nuevos tipos de ingreso mediante polimorfismo sin modificar el servicio base.
 * - LSP: Respeta al 100% el contrato PorteriaServiceInterface.
 * - ISP: Métodos segregados por dominio de garita.
 * - DIP: Inyección del PorteriaRepositoryInterface para total aislamiento en tests.
 */
class PorteriaService implements PorteriaServiceInterface
{
    private PorteriaRepositoryInterface $repository;

    public function __construct(?PorteriaRepositoryInterface $repository = null)
    {
        $this->repository = $repository ?? new PorteriaRepository();
    }

    public function registrarVisita(RegistrarVisitaDTO $dto): Visita
    {
        $tiempoLimite = match ($dto->tipoVisita) {
            'domicilio' => 15,
            'taxi' => 10,
            default => null,
        };

        return $this->repository->guardarVisita([
            'copropiedad_id' => $dto->copropiedadId,
            'unidad_id' => $dto->unidadId,
            'visitante_nombre' => $dto->visitanteNombre,
            'visitante_documento' => $dto->visitanteDocumento,
            'tipo_visita' => $dto->tipoVisita,
            'placa_vehiculo' => $dto->placaVehiculo,
            'empresa_servicio' => $dto->empresaServicio,
            'tiempo_limite_minutos' => $tiempoLimite,
            'pre_autorizado' => $dto->preAutorizado,
            'estado' => 'ingresado',
            'ingreso_at' => Carbon::now(),
            'guarda_id' => $dto->guardaId,
            'observaciones' => $dto->observaciones,
        ]);
    }

    public function marcarSalidaVisita(Visita $visita): Visita
    {
        return $this->repository->actualizarVisita($visita, [
            'estado' => 'finalizado',
            'salida_at' => Carbon::now(),
        ]);
    }

    public function registrarRecibo(RegistrarReciboDTO $dto): ReciboPublico
    {
        return $this->repository->guardarRecibo([
            'copropiedad_id' => $dto->copropiedadId,
            'unidad_id' => $dto->unidadId,
            'tipo_servicio' => $dto->tipoServicio,
            'empresa_emisora' => $dto->empresaEmisora,
            'periodo_facturado' => $dto->periodoFacturado,
            'estado' => 'en_casillero',
            'recibido_por_guarda_id' => $dto->guardaId,
        ]);
    }

    public function recepcionarPaquete(RegistrarPaqueteDTO $dto): Paquete
    {
        $pinEntrega = sprintf('%04d', random_int(1000, 9999));

        return $this->repository->guardarPaquete([
            'copropiedad_id' => $dto->copropiedadId,
            'unidad_id' => $dto->unidadId,
            'empresa_mensajeria' => $dto->empresaMensajeria,
            'codigo_guia' => $dto->codigoGuia,
            'descripcion' => $dto->descripcion,
            'pin_entrega' => $pinEntrega,
            'estado' => 'recibido',
            'recibido_por_guarda_id' => $dto->guardaId,
        ]);
    }

    public function entregarPaquete(Paquete $paquete, string $entregadoANombre): Paquete
    {
        return $this->repository->actualizarPaquete($paquete, [
            'estado' => 'entregado',
            'entregado_at' => Carbon::now(),
            'entregado_a_nombre' => $entregadoANombre,
        ]);
    }

    public function entregarPaqueteConPin(Paquete $paquete, string $pin, string $entregadoANombre): Paquete
    {
        if ($paquete->pin_entrega && $paquete->pin_entrega !== $pin) {
            throw new DomainException('El PIN de entrega es incorrecto.');
        }

        return $this->repository->actualizarPaquete($paquete, [
            'estado' => 'entregado',
            'entregado_at' => Carbon::now(),
            'entregado_a_nombre' => $entregadoANombre,
        ]);
    }

    public function relevarTurno(RelevarTurnoDTO $dto): array
    {
        $guardaEntrante = $this->repository->buscarGuardaPorPin($dto->copropiedadId, $dto->pinEntrante);

        if (!$guardaEntrante) {
            throw new DomainException('El PIN ingresado no corresponde a ningún guarda activo.');
        }

        // Registrar la novedad inmutable de cambio de turno en la minuta
        $this->repository->registrarMinuta([
            'copropiedad_id' => $dto->copropiedadId,
            'guarda_id' => $guardaEntrante->id,
            'tipo_evento' => 'cambio_turno',
            'prioridad' => 'informativo',
            'descripcion' => "Relevo de guardia en puesto de portería. Entrega: {$dto->guardaSalienteNombre}, Recibe: {$guardaEntrante->name}.",
        ]);

        return [
            'success' => true,
            'guarda_entrante_id' => $guardaEntrante->id,
            'guarda_entrante_nombre' => $guardaEntrante->name,
        ];
    }

    public function registrarIngresoVehiculo(RegistrarParqueaderoDTO $dto): ParqueaderoRegistro
    {
        return $this->repository->guardarParqueadero([
            'copropiedad_id' => $dto->copropiedadId,
            'unidad_id' => $dto->unidadId,
            'tipo_espacio' => $dto->tipoEspacio,
            'placa_vehiculo' => $dto->placaVehiculo,
            'numero_bahia' => $dto->numeroBahia,
            'estado' => 'activo',
            'ingreso_at' => Carbon::now(),
            'guarda_id' => $dto->guardaId,
        ]);
    }

    public function marcarSalidaVehiculo(ParqueaderoRegistro $registro): ParqueaderoRegistro
    {
        return $this->repository->actualizarParqueadero($registro, [
            'estado' => 'finalizado',
            'salida_at' => Carbon::now(),
        ]);
    }

    public function calcularDisponibilidadParqueadero(int $capacidadTotal, int $cuposOcupados): array
    {
        $capacidad = max(0, $capacidadTotal);
        $ocupados = max(0, $cuposOcupados);
        $disponibles = max(0, $capacidad - $ocupados);
        $porcentaje = $capacidad > 0 ? round(($ocupados / $capacidad) * 100, 2) : 100.0;
        $alertaSobrecupo = $disponibles === 0 || $ocupados >= $capacidad;

        return [
            'capacidad_total' => $capacidad,
            'ocupados' => $ocupados,
            'disponibles' => $disponibles,
            'porcentaje_ocupacion' => min(100.0, $porcentaje),
            'alerta_sobrecupo' => $alertaSobrecupo,
        ];
    }

    public function crearPaseVisita(CrearPaseVisitaDTO $dto): PaseVisita
    {
        $tokenAcceso = bin2hex(random_bytes(32));
        $codigoPin = Str::upper(Str::random(6));

        return $this->repository->guardarPaseVisita([
            'copropiedad_id' => $dto->copropiedadId,
            'unidad_id' => $dto->unidadId,
            'residente_id' => $dto->residenteId,
            'token_acceso' => $tokenAcceso,
            'codigo_pin' => $codigoPin,
            'visitante_nombre' => $dto->visitanteNombre,
            'visitante_documento' => $dto->visitanteDocumento,
            'placa_vehiculo' => $dto->placaVehiculo,
            'tipo_pase' => $dto->tipoPase,
            'valido_desde' => $dto->validoDesde,
            'valido_hasta' => $dto->validoHasta,
            'usos_permitidos' => $dto->usosPermitidos,
            'usos_consumidos' => 0,
            'estado' => 'activo',
        ]);
    }

    public function validarIngresoConPase(ValidarPaseVisitaDTO $dto): Visita
    {
        $pase = $this->repository->buscarPaseVisitaPorToken($dto->tokenOPin);

        if (!$pase) {
            throw new DomainException('El pase de visita o PIN ingresado no existe.');
        }

        $ahora = Carbon::now();

        if ($ahora > $pase->valido_hasta) {
            throw new DomainException('El pase de visita ha expirado.');
        }

        if ($ahora < $pase->valido_desde) {
            throw new DomainException('El pase de visita aún no se encuentra vigente.');
        }

        if ($pase->usos_consumidos >= $pase->usos_permitidos) {
            throw new DomainException('El pase de visita ha alcanzado el límite de usos permitidos.');
        }

        if ($pase->estado !== 'activo') {
            throw new DomainException('El pase de visita no se encuentra activo.');
        }

        // Incrementar uso y actualizar estado si agota
        $nuevosUsos = $pase->usos_consumidos + 1;
        $nuevoEstado = $nuevosUsos >= $pase->usos_permitidos ? 'agotado' : 'activo';

        $this->repository->actualizarPaseVisita($pase, [
            'usos_consumidos' => $nuevosUsos,
            'estado' => $nuevoEstado,
        ]);

        // Registrar visita autorizada
        return $this->repository->guardarVisita([
            'copropiedad_id' => $pase->copropiedad_id,
            'unidad_id' => $pase->unidad_id,
            'visitante_nombre' => $dto->visitanteNombre ?? $pase->visitante_nombre ?? 'Visitante Pre-autorizado',
            'visitante_documento' => $dto->visitanteDocumento ?? $pase->visitante_documento ?? 'PRE-AUTORIZADO',
            'tipo_visita' => $pase->tipo_pase === 'contratista' ? 'contratista' : 'personal',
            'placa_vehiculo' => $dto->placaVehiculo ?? $pase->placa_vehiculo,
            'pre_autorizado' => true,
            'pase_visita_id' => $pase->id,
            'estado' => 'ingresado',
            'ingreso_at' => $ahora,
            'guarda_id' => $dto->guardaId,
            'observaciones' => "Acceso autorizado mediante Pase Digital QR ({$pase->tipo_pase}).",
        ]);
    }

    public function solicitarCitofoniaVirtual(SolicitarCitofoniaDTO $dto): SolicitudCitofonia
    {
        $tokenRespuesta = bin2hex(random_bytes(32));

        return $this->repository->guardarSolicitudCitofonia([
            'copropiedad_id' => $dto->copropiedadId,
            'unidad_id' => $dto->unidadId,
            'guarda_id' => $dto->guardaId,
            'visitante_nombre' => $dto->visitanteNombre,
            'visitante_documento' => $dto->visitanteDocumento,
            'tipo_visita' => $dto->tipoVisita,
            'token_respuesta' => $tokenRespuesta,
            'estado' => 'pendiente',
            'expira_at' => Carbon::now()->addMinutes(5),
        ]);
    }

    public function responderCitofoniaVirtual(string $tokenRespuesta, string $respuesta, string $respondidoPorId): SolicitudCitofonia
    {
        $solicitud = $this->repository->buscarSolicitudCitofoniaPorToken($tokenRespuesta);

        if (!$solicitud) {
            throw new DomainException('Solicitud de citofonía no encontrada o token inválido.');
        }

        if (Carbon::now() > $solicitud->expira_at) {
            throw new DomainException('La solicitud de citofonía ha expirado.');
        }

        if ($solicitud->estado !== 'pendiente') {
            throw new DomainException('Esta solicitud ya fue respondida.');
        }

        if (!in_array($respuesta, ['autorizado', 'denegado'])) {
            throw new DomainException('Respuesta inválida. Debe ser "autorizado" o "denegado".');
        }

        return $this->repository->actualizarSolicitudCitofonia($solicitud, [
            'estado' => $respuesta,
            'respondido_por_id' => $respondidoPorId,
            'respondido_at' => Carbon::now(),
        ]);
    }

    public function esTiempoPermanenciaExcedido(Visita $visita, ?\DateTimeInterface $momentoActual = null): bool
    {
        if ($visita->estado !== 'ingresado' || empty($visita->tiempo_limite_minutos)) {
            return false;
        }

        $momento = $momentoActual ? Carbon::parse($momentoActual) : Carbon::now();
        $ingreso = Carbon::parse($visita->ingreso_at);

        return abs($momento->diffInMinutes($ingreso)) > $visita->tiempo_limite_minutos;
    }

    public function registrarIngresoVehiculoConCapacidad(RegistrarParqueaderoDTO $dto, int $capacidadMaxima): ParqueaderoRegistro
    {
        if ($dto->tipoEspacio === 'visitante') {
            $activos = $this->repository->contarVehiculosActivosPorTipo($dto->copropiedadId, 'visitante');
            if ($activos >= $capacidadMaxima) {
                throw new DomainException('Capacidad de parqueadero de visitantes colmada. Sobrecupo detectado.');
            }
        }

        return $this->registrarIngresoVehiculo($dto);
    }

    public function liquidarTarifaVehiculo(ParqueaderoRegistro $registro, PoliticaTarifariaDTO $politica, ?\DateTimeInterface $salidaMomento = null): array
    {
        $momento = $salidaMomento ? Carbon::parse($salidaMomento) : Carbon::now();
        $ingreso = Carbon::parse($registro->ingreso_at);

        $minutosPermanencia = (int) round(abs($momento->diffInMinutes($ingreso)));
        $minutosCortesia = $politica->horasCortesia * 60;
        $minutosCobrables = max(0, $minutosPermanencia - $minutosCortesia);
        $horasCobrables = $minutosCobrables > 0 ? (int) ceil($minutosCobrables / 60) : 0;
        $valorLiquidado = $horasCobrables * $politica->tarifaPorHora;

        if ($politica->tarifaDiaMax > 0 && $valorLiquidado > $politica->tarifaDiaMax) {
            $valorLiquidado = $politica->tarifaDiaMax;
        }

        $referenciaPago = $valorLiquidado > 0 ? 'PKG-' . Str::upper(Str::random(10)) : null;

        $this->repository->actualizarParqueadero($registro, [
            'minutos_permanencia' => $minutosPermanencia,
            'minutos_cobrables' => $minutosCobrables,
            'valor_liquidado' => $valorLiquidado,
            'referencia_pago' => $referenciaPago,
        ]);

        return [
            'registro_id' => $registro->id,
            'es_cortesia' => $valorLiquidado == 0,
            'minutos_permanencia' => $minutosPermanencia,
            'horas_cobrables' => $horasCobrables,
            'valor_liquidado' => (float) $valorLiquidado,
            'referencia_pago' => $referenciaPago,
        ];
    }

    public function procesarPagoParqueadero(ParqueaderoRegistro $registro, string $metodoPago, string $referenciaPago): ParqueaderoRegistro
    {
        return $this->repository->actualizarParqueadero($registro, [
            'pagado_at' => Carbon::now(),
            'metodo_pago' => $metodoPago,
            'referencia_pago' => $referenciaPago,
        ]);
    }

    public function marcarSalidaVehiculoConValidacionPago(ParqueaderoRegistro $registro): ParqueaderoRegistro
    {
        if ($registro->valor_liquidado > 0 && empty($registro->pagado_at)) {
            $formateado = number_format($registro->valor_liquidado, 0, ',', ',');
            throw new DomainException("El vehículo tiene un saldo pendiente de $ {$formateado} COP.");
        }

        return $this->repository->actualizarParqueadero($registro, [
            'estado' => 'finalizado',
            'salida_at' => Carbon::now(),
        ]);
    }

    public function registrarMinutaForense(RegistrarMinutaForenseDTO $dto): MinutaPorteria
    {
        $ultimaMinuta = $this->repository->obtenerUltimaMinutaPorCopropiedad($dto->copropiedadId);
        $prevHash = $ultimaMinuta ? $ultimaMinuta->hash_inmutable : hash('sha256', 'NEXO_PRO_GENESIS_' . $dto->copropiedadId);
        $timestamp = Carbon::now()->toIso8601String();

        $hashInmutable = $this->calcularHashMinuta(
            prevHash: $prevHash,
            timestamp: $timestamp,
            copropiedadId: $dto->copropiedadId,
            guardaId: $dto->guardaId,
            tipoEvento: $dto->tipoEvento,
            prioridad: $dto->prioridad,
            descripcion: $dto->descripcion,
            evidenciaFotoPath: $dto->evidenciaFotoPath
        );

        return $this->repository->registrarMinuta([
            'copropiedad_id' => $dto->copropiedadId,
            'guarda_id' => $dto->guardaId,
            'tipo_evento' => $dto->tipoEvento,
            'prioridad' => $dto->prioridad,
            'descripcion' => $dto->descripcion,
            'hash_inmutable' => $hashInmutable,
            'prev_hash' => $prevHash,
            'evidencia_foto_path' => $dto->evidenciaFotoPath,
            'firmado_digitalmente' => true,
        ]);
    }

    public function verificarIntegridadCadenaMinuta(string $copropiedadId): array
    {
        $minutas = $this->repository->listarMinutasPorCopropiedad($copropiedadId);

        if (empty($minutas)) {
            return [
                'es_valida' => true,
                'bloques_verificados' => 0,
                'bloque_corrupto_id' => null,
            ];
        }

        $expectedPrevHash = hash('sha256', 'NEXO_PRO_GENESIS_' . $copropiedadId);

        foreach ($minutas as $minuta) {
            if ($minuta->prev_hash !== $expectedPrevHash) {
                return [
                    'es_valida' => false,
                    'bloque_corrupto_id' => $minuta->id,
                ];
            }

            $timestamp = $minuta->created_at ? Carbon::parse($minuta->created_at)->toIso8601String() : '';
            $recalculatedHash = $this->calcularHashMinuta(
                prevHash: $minuta->prev_hash,
                timestamp: $timestamp,
                copropiedadId: $minuta->copropiedad_id,
                guardaId: $minuta->guarda_id,
                tipoEvento: $minuta->tipo_evento,
                prioridad: $minuta->prioridad,
                descripcion: $minuta->descripcion,
                evidenciaFotoPath: $minuta->evidencia_foto_path
            );

            if ($minuta->hash_inmutable !== $recalculatedHash) {
                return [
                    'es_valida' => false,
                    'bloque_corrupto_id' => $minuta->id,
                ];
            }

            $expectedPrevHash = $minuta->hash_inmutable;
        }

        return [
            'es_valida' => true,
            'bloques_verificados' => count($minutas),
            'bloque_corrupto_id' => null,
        ];
    }

    public function tieneAlertaUrgenteActiva(string $copropiedadId): bool
    {
        $minutas = $this->repository->listarMinutasPorCopropiedad($copropiedadId);
        $limite = Carbon::now()->subHours(24);

        foreach ($minutas as $minuta) {
            if ($minuta->prioridad === 'urgente' && Carbon::parse($minuta->created_at)->gte($limite)) {
                return true;
            }
        }

        return false;
    }

    public function obtenerDashboardSupervisionB2B(?string $supervisorId = null): array
    {
        $copropiedades = $this->repository->obtenerCopropiedadesParaSupervision($supervisorId);
        $puestos = [];
        $totalCriticas = 0;
        $operativos = 0;
        $enAlerta = 0;

        foreach ($copropiedades as $copropiedad) {
            $copropiedadId = is_array($copropiedad) ? $copropiedad['id'] : $copropiedad->id;
            $nombre = is_array($copropiedad) ? $copropiedad['nombre'] : $copropiedad->nombre;
            $ciudad = is_array($copropiedad) ? ($copropiedad['ciudad'] ?? 'Bogotá') : ($copropiedad->ciudad ?? 'Bogotá');
            $direccion = is_array($copropiedad) ? ($copropiedad['direccion'] ?? '') : ($copropiedad->direccion ?? '');

            $minutas = $this->repository->listarMinutasPorCopropiedad($copropiedadId);
            $ultimaMinuta = !empty($minutas) ? end($minutas) : null;

            // Contar urgentes en 24h
            $urgentes24h = 0;
            $limite24h = Carbon::now()->subHours(24);
            $guardaActivo = 'Guarda Asignado';
            $ultimoRelevoAt = null;

            foreach (array_reverse($minutas) as $m) {
                if ($m->prioridad === 'urgente' && Carbon::parse($m->created_at)->gte($limite24h)) {
                    $urgentes24h++;
                }
                if ($m->tipo_evento === 'cambio_turno' && !$ultimoRelevoAt) {
                    $ultimoRelevoAt = Carbon::parse($m->created_at)->toIso8601String();
                }
            }

            $totalCriticas += $urgentes24h;

            // Determinar estado semafórico
            $estadoSemaforo = 'NORMAL';
            if ($urgentes24h > 0) {
                $estadoSemaforo = 'CRITICO';
            } elseif ($ultimaMinuta && abs(Carbon::now()->diffInHours(Carbon::parse($ultimaMinuta->created_at))) >= 12) {
                $estadoSemaforo = 'CRITICO';
            } elseif ($ultimaMinuta && $ultimaMinuta->prioridad === 'advertencia') {
                $estadoSemaforo = 'ADVERTENCIA';
            } elseif (empty($minutas)) {
                $estadoSemaforo = 'ADVERTENCIA';
            }

            if ($estadoSemaforo === 'NORMAL') {
                $operativos++;
            } else {
                $enAlerta++;
            }

            $puestos[] = [
                'id' => $copropiedadId,
                'nombre' => $nombre,
                'ciudad' => $ciudad,
                'direccion' => $direccion,
                'estado_semaforo' => $estadoSemaforo,
                'novedades_urgentes_24h' => $urgentes24h,
                'ultimo_relevo_at' => $ultimoRelevoAt,
                'ultima_minuta' => $ultimaMinuta ? [
                    'id' => $ultimaMinuta->id,
                    'tipo_evento' => $ultimaMinuta->tipo_evento,
                    'prioridad' => $ultimaMinuta->prioridad,
                    'descripcion' => $ultimaMinuta->descripcion,
                    'hash_inmutable' => $ultimaMinuta->hash_inmutable,
                    'created_at' => Carbon::parse($ultimaMinuta->created_at)->toIso8601String(),
                ] : null,
            ];
        }

        return [
            'kpis' => [
                'total_puestos' => count($puestos),
                'puestos_operativos' => $operativos,
                'puestos_alerta' => $enAlerta,
                'alertas_criticas' => $totalCriticas,
            ],
            'puestos' => $puestos,
        ];
    }

    public function calcularHashMinuta(
        string $prevHash,
        string $timestamp,
        string $copropiedadId,
        string $guardaId,
        string $tipoEvento,
        string $prioridad,
        string $descripcion,
        ?string $evidenciaFotoPath
    ): string {
        $payload = implode('|', [
            $prevHash,
            $timestamp,
            $copropiedadId,
            $guardaId,
            $tipoEvento,
            $prioridad,
            $descripcion,
            $evidenciaFotoPath ?? '',
        ]);

        return hash('sha256', $payload);
    }
}
