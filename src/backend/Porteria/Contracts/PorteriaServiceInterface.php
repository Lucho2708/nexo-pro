<?php

namespace NexoPro\Porteria\Contracts;

use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use NexoPro\Porteria\DTOs\RegistrarVisitaDTO;
use NexoPro\Porteria\DTOs\RegistrarReciboDTO;
use NexoPro\Porteria\DTOs\RegistrarPaqueteDTO;
use NexoPro\Porteria\DTOs\RegistrarParqueaderoDTO;

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

    public function registrarIngresoVehiculo(RegistrarParqueaderoDTO $dto): ParqueaderoRegistro;

    public function marcarSalidaVehiculo(ParqueaderoRegistro $registro): ParqueaderoRegistro;

    public function calcularDisponibilidadParqueadero(int $capacidadTotal, int $cuposOcupados): array;
}
