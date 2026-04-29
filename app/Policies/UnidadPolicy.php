<?php

namespace App\Policies;

use App\Models\Unidad;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UnidadPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Unidad $unidad): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $unidad->copropiedad_id === $user->current_copropiedad_id;
        }

        // Si es Owner, debe estar vinculado a esta unidad
        return $user->unidades()->where('unidades.id', $unidad->id)->exists();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Unidad $unidad): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Solo Admin o SuperAdmin pueden editar unidades
        if ($user->isAdmin()) {
            return $unidad->copropiedad_id === $user->current_copropiedad_id;
        }

        return false;
    }
}
