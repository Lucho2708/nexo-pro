<?php

namespace App\Services\Operations;

use App\Modules\Operations\Models\Reserva;
use App\Modules\Property\Models\ZonaComun;
use App\Repositories\Interfaces\ZonaComunRepositoryInterface;

class ZonaComunService
{
    public function __construct(
        private ZonaComunRepositoryInterface $repository
    ) {}

    public function getAllByCopropiedad(string $copropiedadId)
    {
        return $this->repository->getByCopropiedad($copropiedadId);
    }

    public function getMetrics(string $copropiedadId): array
    {
        $zonas = $this->repository->getByCopropiedad($copropiedadId);
        $totalZonas = $zonas->count();
        $activas = $zonas->where('activa', true)->count();
        $inactivas = $totalZonas - $activas;
        $aforoTotal = (int) $zonas->sum('capacidad_maxima');

        $porcentajeOperatividad = $totalZonas > 0 
            ? round(($activas / $totalZonas) * 100, 1) 
            : 100.0;

        $reservasMes = Reserva::where('copropiedad_id', $copropiedadId)
            ->whereMonth('fecha', now()->month)
            ->whereYear('fecha', now()->year)
            ->count();

        return [
            'total_zonas'             => $totalZonas,
            'zonas_activas'           => $activas,
            'zonas_inactivas'         => $inactivas,
            'aforo_total_pax'         => $aforoTotal,
            'porcentaje_operatividad' => $porcentajeOperatividad,
            'reservas_mes'            => $reservasMes,
        ];
    }

    public function createZona(array $data, string $copropiedadId): ZonaComun
    {
        $data['copropiedad_id'] = $copropiedadId;
        $data['activa'] = $data['activa'] ?? true;
        
        return $this->repository->create($data);
    }

    public function updateZona(string $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function toggleStatus(string $id): bool
    {
        $zona = $this->repository->findById($id);
        if (!$zona) return false;

        return $this->repository->update($id, ['activa' => !$zona->activa]);
    }

    public function deleteZonaSafely(string $id): array
    {
        $zona = $this->repository->findById($id);
        if (!$zona) {
            return [
                'success' => false,
                'message' => 'La zona común solicitada no existe o ya fue eliminada.',
            ];
        }

        // Validar si existen reservas futuras pendientes, aprobadas o pagadas
        $hasActiveReservations = Reserva::where('zona_id', $id)
            ->where('fecha', '>=', now()->toDateString())
            ->whereIn('estado', ['pendiente', 'aprobada', 'pagada'])
            ->exists();

        if ($hasActiveReservations) {
            return [
                'success' => false,
                'message' => 'No es posible eliminar la zona común porque tiene reservas activas o pendientes asociadas. Cancele o reubique las reservas primero.',
            ];
        }

        $this->repository->delete($id);

        return [
            'success' => true,
            'message' => 'Zona común eliminada exitosamente del inventario de la copropiedad.',
        ];
    }
}

