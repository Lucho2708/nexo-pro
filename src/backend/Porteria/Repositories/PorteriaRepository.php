<?php

namespace NexoPro\Porteria\Repositories;

use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use NexoPro\Porteria\Contracts\PorteriaRepositoryInterface;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Implementación concreta del repositorio de garita usando modelos Eloquent.
 *
 * [ASPECTO TÉCNICO / UI]
 * Patrón Repository (01-solid-oop.md) para encapsular las llamadas de persistencia de operaciones.
 */
class PorteriaRepository implements PorteriaRepositoryInterface
{
    public function guardarVisita(array $datos): Visita
    {
        return Visita::create($datos);
    }

    public function actualizarVisita(Visita $visita, array $datos): Visita
    {
        $visita->update($datos);
        return $visita->fresh() ?? $visita;
    }

    public function guardarRecibo(array $datos): ReciboPublico
    {
        return ReciboPublico::create($datos);
    }

    public function guardarPaquete(array $datos): Paquete
    {
        return Paquete::create($datos);
    }

    public function actualizarPaquete(Paquete $paquete, array $datos): Paquete
    {
        $paquete->update($datos);
        return $paquete->fresh() ?? $paquete;
    }

    public function guardarParqueadero(array $datos): ParqueaderoRegistro
    {
        return ParqueaderoRegistro::create($datos);
    }

    public function actualizarParqueadero(ParqueaderoRegistro $registro, array $datos): ParqueaderoRegistro
    {
        $registro->update($datos);
        return $registro->fresh() ?? $registro;
    }
}
