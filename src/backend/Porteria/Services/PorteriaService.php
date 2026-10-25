<?php

namespace NexoPro\Porteria\Services;

use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use NexoPro\Porteria\Contracts\PorteriaServiceInterface;
use NexoPro\Porteria\Contracts\PorteriaRepositoryInterface;
use NexoPro\Porteria\Repositories\PorteriaRepository;
use NexoPro\Porteria\DTOs\RegistrarVisitaDTO;
use NexoPro\Porteria\DTOs\RegistrarReciboDTO;
use NexoPro\Porteria\DTOs\RegistrarPaqueteDTO;
use NexoPro\Porteria\DTOs\RegistrarParqueaderoDTO;
use Carbon\Carbon;

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
        return $this->repository->guardarVisita([
            'copropiedad_id' => $dto->copropiedadId,
            'unidad_id' => $dto->unidadId,
            'visitante_nombre' => $dto->visitanteNombre,
            'visitante_documento' => $dto->visitanteDocumento,
            'tipo_visita' => $dto->tipoVisita,
            'placa_vehiculo' => $dto->placaVehiculo,
            'empresa_servicio' => $dto->empresaServicio,
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
        return $this->repository->guardarPaquete([
            'copropiedad_id' => $dto->copropiedadId,
            'unidad_id' => $dto->unidadId,
            'empresa_mensajeria' => $dto->empresaMensajeria,
            'codigo_guia' => $dto->codigoGuia,
            'descripcion' => $dto->descripcion,
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
}
