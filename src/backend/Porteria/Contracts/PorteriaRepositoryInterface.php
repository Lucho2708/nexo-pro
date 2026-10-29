<?php

namespace NexoPro\Porteria\Contracts;

use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use App\Modules\Operations\Models\MinutaPorteria;
use App\Modules\Operations\Models\PaseVisita;
use App\Modules\Operations\Models\SolicitudCitofonia;
use App\Modules\IAM\Models\User;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Define el contrato de persistencia para el módulo de portería bajo el Repository Pattern.
 *
 * [ASPECTO TÉCNICO / UI]
 * Interfaz que desacopla la capa de negocio (PorteriaService) del ORM Eloquent,
 * permitiendo inyección de mocks y pruebas unitarias deterministas sin base de datos activa.
 */
interface PorteriaRepositoryInterface
{
    public function guardarVisita(array $datos): Visita;

    public function actualizarVisita(Visita $visita, array $datos): Visita;

    public function guardarRecibo(array $datos): ReciboPublico;

    public function guardarPaquete(array $datos): Paquete;

    public function actualizarPaquete(Paquete $paquete, array $datos): Paquete;

    public function guardarParqueadero(array $datos): ParqueaderoRegistro;

    public function actualizarParqueadero(ParqueaderoRegistro $registro, array $datos): ParqueaderoRegistro;

    public function contarVehiculosActivosPorTipo(string $copropiedadId, string $tipoEspacio): int;

    public function buscarGuardaPorPin(string $copropiedadId, string $pin): ?User;

    public function registrarMinuta(array $datos): MinutaPorteria;

    public function obtenerUltimaMinutaPorCopropiedad(string $copropiedadId): ?MinutaPorteria;

    public function listarMinutasPorCopropiedad(string $copropiedadId): array;

    public function obtenerCopropiedadesParaSupervision(?string $supervisorId = null): array;

    public function guardarPaseVisita(array $datos): PaseVisita;

    public function buscarPaseVisitaPorToken(string $token): ?PaseVisita;

    public function actualizarPaseVisita(PaseVisita $pase, array $datos): PaseVisita;

    public function guardarSolicitudCitofonia(array $datos): SolicitudCitofonia;

    public function buscarSolicitudCitofoniaPorToken(string $token): ?SolicitudCitofonia;

    public function actualizarSolicitudCitofonia(SolicitudCitofonia $solicitud, array $datos): SolicitudCitofonia;
}
