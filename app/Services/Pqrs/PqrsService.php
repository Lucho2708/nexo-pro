<?php

namespace App\Services\Pqrs;

use App\Modules\Operations\Models\Pqrs;
use App\Repositories\Interfaces\PqrsRepositoryInterface;
use Illuminate\Support\Facades\Log;

class PqrsService
{
    public function __construct(
        private PqrsRepositoryInterface $repository
    ) {}

    public function createPqrs(array $data, string $userId): Pqrs
    {
        $data['user_id'] = $userId;
        $data['estado'] = 'abierto';
        
        $pqrs = $this->repository->create($data);
        
        // Aquí dispararíamos eventos en el futuro
        // event(new PqrsCreated($pqrs));
        
        return $pqrs;
    }

    public function respondPqrs(string $id, string $respuesta, bool $shouldClose): bool
    {
        $data = [
            'respuesta' => $respuesta,
            'fecha_respuesta' => now(),
            'estado' => $shouldClose ? 'cerrado' : 'en_proceso'
        ];

        return $this->repository->update($id, $data);
    }

    public function reopenPqrs(string $id): bool
    {
        return $this->repository->update($id, ['estado' => 'reabierto']);
    }

    public function getDashboardMetrics(string $copropiedadId): array
    {
        $rawCounts = $this->repository->getCountsByStatus($copropiedadId);

        $abierto = (int) ($rawCounts['abierto'] ?? 0);
        $enProceso = (int) ($rawCounts['en_proceso'] ?? 0);
        $reabierto = (int) ($rawCounts['reabierto'] ?? 0);
        $cerrado = (int) ($rawCounts['cerrado'] ?? 0);

        $total = $abierto + $enProceso + $reabierto + $cerrado;
        $tasaResolucion = $total > 0 ? round(($cerrado / $total) * 100, 1) : 0;
        $urgentesActivas = $this->repository->getUrgentCount($copropiedadId);

        return [
            'abierto' => $abierto,
            'en_proceso' => $enProceso,
            'reabierto' => $reabierto,
            'cerrado' => $cerrado,
            'total' => $total,
            'tasa_resolucion' => $tasaResolucion,
            'urgentes_activas' => $urgentesActivas,
        ];
    }
}
