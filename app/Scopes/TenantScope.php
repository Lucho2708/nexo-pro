<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class TenantScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Los Super Admins ven todo en el mando central.
            // Pero si están en un contexto de copropiedad (current_copropiedad_id set), filtramos.
            // Sin embargo, por defecto, SuperAdmin no debería estar filtrado.
            if ($user->isSuperAdmin()) {
                return;
            }

            if ($user->current_copropiedad_id) {
                $builder->where($model->getTable() . '.copropiedad_id', $user->current_copropiedad_id);
            } else {
                // Si no tiene contexto, no debería ver nada de tenant (seguridad estricta)
                $builder->whereRaw('1 = 0');
            }
        }
    }
}
