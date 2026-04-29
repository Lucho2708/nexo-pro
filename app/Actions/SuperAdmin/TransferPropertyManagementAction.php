<?php

namespace App\Actions\SuperAdmin;

use App\Models\Copropiedad;
use App\Models\User;
use App\Mail\Admin\WelcomeManagementMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class TransferPropertyManagementAction
{
    /**
     * Transfer management of a SINGLE property from one admin to another.
     *
     * @param Copropiedad $copropiedad
     * @param int|null $oldAdminId
     * @param int $newAdminId
     * @return void
     */
    public function execute(Copropiedad $copropiedad, ?string $oldAdminId, string $newAdminId): void
    {
        DB::transaction(function () use ($copropiedad, $oldAdminId, $newAdminId) {
            $newAdmin = User::findOrFail($newAdminId);

            // 1. Detach old admin ONLY from this property
            if ($oldAdminId) {
                $oldAdmin = User::find($oldAdminId);
                if ($oldAdmin) {
                    $oldAdmin->managedCopropiedades()->detach($copropiedad->id);
                    
                    // Reset current context if it was this property
                    if ($oldAdmin->current_copropiedad_id === $copropiedad->id) {
                        $nextProperty = $oldAdmin->managedCopropiedades()->first();
                        $oldAdmin->update(['current_copropiedad_id' => $nextProperty?->id]);
                    }
                }
            }

            // 2. Attach new admin
            $newAdmin->managedCopropiedades()->syncWithoutDetaching([$copropiedad->id]);
            
            // Set as current context for UI convenience
            $newAdmin->update(['current_copropiedad_id' => $copropiedad->id]);

            // 3. Log Audit Trail
            Log::info("MANAGEMENT_TRANSFER", [
                'copropiedad_id' => $copropiedad->id,
                'old_admin_id' => $oldAdminId,
                'new_admin_id' => $newAdminId,
                'authorized_by' => auth()->id()
            ]);

            // 4. Send Welcome Mail
            Mail::to($newAdmin->email)->send(new WelcomeManagementMail($copropiedad));
        });
    }
}
