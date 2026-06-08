<?php

namespace App\Services\Financial; // Lo mantendré en este namespace ya que a veces implica cobros

namespace App\Services\Operations;

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
}
