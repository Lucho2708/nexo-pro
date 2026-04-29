<?php

namespace App\Policies;

use App\Models\Pqrs;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PqrsPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Pqrs $pqrs): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Si es Admin, debe pertenecer a la misma Copropiedad
        if ($user->isAdmin()) {
            return $pqrs->unidad->copropiedad_id === $user->current_copropiedad_id;
        }

        // Si es Owner, debe ser el creador de la PQRS
        return $pqrs->user_id === $user->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Pqrs $pqrs): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            // El admin solo puede responder si es de su copropiedad
            return $pqrs->unidad->copropiedad_id === $user->current_copropiedad_id;
        }

        // El owner solo puede reabrir su propia PQRS (lógica actual del controller)
        return $pqrs->user_id === $user->id;
    }
}
