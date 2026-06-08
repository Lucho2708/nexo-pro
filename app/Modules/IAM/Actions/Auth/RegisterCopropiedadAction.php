<?php

namespace App\Modules\IAM\Actions\Auth;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use App\Events\CopropiedadCreated;
use App\Notifications\WelcomeAdminNotification;
use App\DTOs\RegisterCopropiedadDTO;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterCopropiedadAction
{
    /**
     * Create a new copropiedad and its primary administrator.
     *
     * @param RegisterCopropiedadDTO $dto
     * @return User
     */
    public function execute(RegisterCopropiedadDTO $dto): User
    {
        return DB::transaction(function () use ($dto) {
            // 1. Create the Copropiedad
            $copropiedad = Copropiedad::create([
                'nit' => $dto->nit,
                'nombre' => $dto->nombre_copropiedad,
                'direccion' => $dto->direccion,
                'ciudad' => $dto->ciudad,
                'plan' => $dto->is_standalone ? 'standalone' : ($dto->plan ?? 'basic'),
                'unidades_totales' => $dto->unidades_totales ?? 0,
                'torres' => $dto->torres ?? 0,
                'settings' => array_merge(Copropiedad::defaultSettings(), [
                    'is_standalone' => $dto->is_standalone,
                    'asamblea_virtual_active' => $dto->is_standalone // Forzado si es standalone
                ])
            ]);

            // 2. Create the Administrator User
            $user = new User([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'terms_accepted_at' => now(), // SIC Compliance (Ley 1581)
                'is_standalone' => $dto->is_standalone
            ]);
            $user->role = 'admin';
            $user->current_copropiedad_id = $copropiedad->id;
            $user->save();

            // 3. Attach the admin to the copropiedad pivot table
            $user->managedCopropiedades()->attach($copropiedad->id);

            // 4. Send Welcome Notification
            $user->notify(new WelcomeAdminNotification($user, $copropiedad));

            event(new CopropiedadCreated($copropiedad));

            return $user;
        });
    }
}
