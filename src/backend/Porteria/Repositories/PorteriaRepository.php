<?php

namespace NexoPro\Porteria\Repositories;

use App\Modules\Operations\Models\Visita;
use App\Modules\Operations\Models\ReciboPublico;
use App\Modules\Operations\Models\Paquete;
use App\Modules\Operations\Models\ParqueaderoRegistro;
use App\Modules\Operations\Models\MinutaPorteria;
use App\Modules\Operations\Models\PaseVisita;
use App\Modules\Operations\Models\SolicitudCitofonia;
use App\Modules\IAM\Models\User;
use Illuminate\Support\Facades\Hash;
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

    public function contarVehiculosActivosPorTipo(string $copropiedadId, string $tipoEspacio): int
    {
        return ParqueaderoRegistro::where('copropiedad_id', $copropiedadId)
            ->where('tipo_espacio', $tipoEspacio)
            ->where('estado', 'activo')
            ->count();
    }

    public function buscarGuardaPorPin(string $copropiedadId, string $pin): ?User
    {
        $guardas = User::where('role', 'security')
            ->where('is_active', true)
            ->where(function ($q) use ($copropiedadId) {
                $q->where('current_copropiedad_id', $copropiedadId)
                  ->orWhereNull('current_copropiedad_id');
            })
            ->get();

        foreach ($guardas as $guarda) {
            $guardPin = $guarda->pin_porteria ?? null;
            if ($guardPin && (Hash::check($pin, $guardPin) || $guardPin === $pin)) {
                return $guarda;
            }
        }

        return null;
    }

    public function registrarMinuta(array $datos): MinutaPorteria
    {
        return MinutaPorteria::create($datos);
    }

    public function obtenerUltimaMinutaPorCopropiedad(string $copropiedadId): ?MinutaPorteria
    {
        return MinutaPorteria::where('copropiedad_id', $copropiedadId)
            ->latest('created_at')
            ->first();
    }

    public function listarMinutasPorCopropiedad(string $copropiedadId): array
    {
        return MinutaPorteria::where('copropiedad_id', $copropiedadId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->all();
    }

    public function obtenerCopropiedadesParaSupervision(?string $supervisorId = null): array
    {
        return \App\Modules\Property\Models\Copropiedad::select([
            'id', 'nombre', 'ciudad', 'direccion', 'unidades_totales'
        ])->get()->toArray();
    }

    public function guardarPaseVisita(array $datos): PaseVisita
    {
        return PaseVisita::create($datos);
    }

    public function buscarPaseVisitaPorToken(string $token): ?PaseVisita
    {
        return PaseVisita::where('token_acceso', $token)
            ->orWhere('codigo_pin', $token)
            ->first();
    }

    public function actualizarPaseVisita(PaseVisita $pase, array $datos): PaseVisita
    {
        $pase->update($datos);
        return $pase->fresh() ?? $pase;
    }

    public function guardarSolicitudCitofonia(array $datos): SolicitudCitofonia
    {
        return SolicitudCitofonia::create($datos);
    }

    public function buscarSolicitudCitofoniaPorToken(string $token): ?SolicitudCitofonia
    {
        return SolicitudCitofonia::where('token_respuesta', $token)->first();
    }

    public function actualizarSolicitudCitofonia(SolicitudCitofonia $solicitud, array $datos): SolicitudCitofonia
    {
        $solicitud->update($datos);
        return $solicitud->fresh() ?? $solicitud;
    }
}
