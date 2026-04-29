<?php

namespace App\Actions\Auth;

use App\Models\Copropiedad;
use App\Models\User;
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
                'plan' => $dto->plan ?? 'basic',
                'unidades_totales' => $dto->unidades_totales ?? 0,
                'torres' => $dto->torres ?? 0,
            ]);

            // 2. Create the Administrator User
            $user = new User([
                'name' => $dto->name,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'terms_accepted_at' => now(), // SIC Compliance (Ley 1581)
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
