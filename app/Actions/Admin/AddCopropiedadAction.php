<?php

namespace App\Actions\Admin;

use App\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use Illuminate\Support\Facades\DB;

class AddCopropiedadAction
{
    /**
     * Add a new copropiedad to an existing administrator.
     *
     * @param User $user
     * @param array $data
     * @return Copropiedad
     */
    public function execute(User $user, array $data): Copropiedad
    {
        return DB::transaction(function () use ($user, $data) {
            // 1. Create the Copropiedad
            $copropiedad = Copropiedad::create([
                'nit' => $data['nit'],
                'nombre' => $data['nombre'],
                'direccion' => $data['direccion'],
                'ciudad' => $data['ciudad'],
                'plan' => $data['plan'] ?? 'basic',
                'unidades_totales' => $data['unidades_totales'] ?? 0,
                'torres' => $data['torres'] ?? 0,
            ]);

            // 2. Attach the current admin to the new copropiedad
            $user->managedCopropiedades()->attach($copropiedad->id);

            // 3. Switch context automatically to the new property
            $user->update(['current_copropiedad_id' => $copropiedad->id]);

            return $copropiedad;
        });
    }
}
