<?php

namespace NexoPro\Porteria\Contracts;

use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;

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
}
