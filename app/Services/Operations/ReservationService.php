<?php

namespace App\Services\Operations;

use App\Models\Reserva;
use App\Models\ZonaComun;
use App\Repositories\Interfaces\ReservationRepositoryInterface;
use App\Repositories\Interfaces\ZonaComunRepositoryInterface;
use App\Repositories\Interfaces\FinancialRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Notifications\ReservationStatusNotification;

class ReservationService
{
    public function __construct(
        private ReservationRepositoryInterface $repository,
        private ZonaComunRepositoryInterface $zonaRepository,
        private FinancialRepositoryInterface $financialRepository
    ) {}

    public function createReservation(array $data, string $userId): Reserva
    {
        return DB::transaction(function () use ($data, $userId) {
            // Bloqueo pesimista de la zona para evitar condiciones de carrera en el conteo y solapamiento
            $zona = $this->zonaRepository->findById($data['zona_id']);
            if (!$zona || !$zona->activa) {
                throw new \Exception("La zona común no se encuentra disponible.");
            }
            
            // Re-fetch with lock if needed, but in many DBs a simple select inside transaction is enough 
            // depending on isolation level. To be safe as per requirements:
            $zona = ZonaComun::where('id', $zona->id)->lockForUpdate()->first();

            $fecha = Carbon::parse($data['fecha']);
            
            // 1. Validar anticipación
            $anticipacionMinima = $zona->settings['min_dias_anticipacion'] ?? 0;
            if ($fecha->isBefore(now()->addDays($anticipacionMinima)->startOfDay())) {
                throw new \Exception("La reserva debe realizarse con al menos {$anticipacionMinima} días de anticipación.");
            }

            // 2. Validar restricción por mora
            $bloquearSiMora = $zona->settings['bloquear_si_mora'] ?? true;
            if ($bloquearSiMora) {
                $unidad = $this->financialRepository->getUnidad($data['unidad_id']);
                if ($unidad->saldo_actual > 0) {
                    throw new \Exception("La unidad presenta saldos pendientes. No es posible realizar reservas.");
                }
            }

            // 3. Validar máximo de reservas por mes
            $maxMensual = $zona->settings['max_reservas_mes'] ?? 2;
            $existentes = $this->repository->countByUnitAndMonth(
                $data['unidad_id'], 
                $data['zona_id'], 
                $fecha->year, 
                $fecha->month
            );
            if ($existentes >= $maxMensual) {
                throw new \Exception("Se ha alcanzado el límite máximo de {$maxMensual} reservas mensuales para esta zona.");
            }

            // 4. Validar solapamiento (Overlap)
            $overlap = $this->repository->checkOverlap(
                $data['zona_id'], 
                $data['fecha'], 
                $data['hora_inicio'], 
                $data['hora_fin']
            );

            if ($overlap) {
                throw new \Exception("El horario seleccionado ya se encuentra ocupado.");
            }

            $data['user_id'] = $userId;
            $data['estado'] = 'pendiente';

            return $this->repository->create($data);
        });
    }

    public function updateStatus(string $id, string $status, ?string $notes = null): bool
    {
        $data = ['estado' => $status];
        if ($notes) $data['notas_admin'] = $notes;

        $updated = $this->repository->update($id, $data);
        
        if ($updated) {
            $reserva = $this->repository->findById($id);
            $reserva->user->notify(new ReservationStatusNotification($reserva));
        }

        return $updated;
    }

    public function cancelReservation(string $id): bool
    {
        return $this->repository->update($id, ['estado' => 'cancelada']);
    }

    public function getAllForAdmin(string $copropiedadId)
    {
        return $this->repository->getByCopropiedad($copropiedadId);
    }
}
