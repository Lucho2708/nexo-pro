<?php

namespace App\Services\Pqrs;

use App\Models\Pqrs;
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
        return $this->repository->getCountsByStatus($copropiedadId);
    }
}
