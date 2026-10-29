<?php

namespace NexoPro\Porteria\Contracts;

use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use App\Modules\Operations\Models\PaseVisita;
use App\Modules\Operations\Models\SolicitudCitofonia;
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

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Define las operaciones atómicas de garita para control de accesos, recepción de facturas,
 * paquetería y control de capacidad vehicular en portería.
 *
 * [ASPECTO TÉCNICO / UI]
 * Interfaz de Clean Architecture inyectada en controladores bajo el principio DIP (Inversión de Dependencias).
 */
interface PorteriaServiceInterface
{
    public function registrarVisita(RegistrarVisitaDTO $dto): Visita;

    public function marcarSalidaVisita(Visita $visita): Visita;

    public function registrarRecibo(RegistrarReciboDTO $dto): ReciboPublico;

    public function recepcionarPaquete(RegistrarPaqueteDTO $dto): Paquete;

    public function entregarPaquete(Paquete $paquete, string $entregadoANombre): Paquete;

    public function entregarPaqueteConPin(Paquete $paquete, string $pin, string $entregadoANombre): Paquete;

    public function relevarTurno(RelevarTurnoDTO $dto): array;

    public function registrarIngresoVehiculo(RegistrarParqueaderoDTO $dto): ParqueaderoRegistro;

    public function marcarSalidaVehiculo(ParqueaderoRegistro $registro): ParqueaderoRegistro;

    public function calcularDisponibilidadParqueadero(int $capacidadTotal, int $cuposOcupados): array;

    public function crearPaseVisita(CrearPaseVisitaDTO $dto): PaseVisita;

    public function validarIngresoConPase(ValidarPaseVisitaDTO $dto): Visita;

    public function solicitarCitofoniaVirtual(SolicitarCitofoniaDTO $dto): SolicitudCitofonia;

    public function responderCitofoniaVirtual(string $tokenRespuesta, string $respuesta, string $respondidoPorId): SolicitudCitofonia;

    public function esTiempoPermanenciaExcedido(Visita $visita, ?\DateTimeInterface $momentoActual = null): bool;

    public function registrarIngresoVehiculoConCapacidad(RegistrarParqueaderoDTO $dto, int $capacidadMaxima): ParqueaderoRegistro;

    public function liquidarTarifaVehiculo(ParqueaderoRegistro $registro, PoliticaTarifariaDTO $politica, ?\DateTimeInterface $salidaMomento = null): array;

    public function procesarPagoParqueadero(ParqueaderoRegistro $registro, string $metodoPago, string $referenciaPago): ParqueaderoRegistro;

    public function marcarSalidaVehiculoConValidacionPago(ParqueaderoRegistro $registro): ParqueaderoRegistro;

    public function registrarMinutaForense(RegistrarMinutaForenseDTO $dto): MinutaPorteria;

    public function verificarIntegridadCadenaMinuta(string $copropiedadId): array;

    public function tieneAlertaUrgenteActiva(string $copropiedadId): bool;

    public function obtenerDashboardSupervisionB2B(?string $supervisorId = null): array;
}
