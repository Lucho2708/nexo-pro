<?php

namespace App\Traits;

use App\Scopes\TenantScope;
use Illuminate\Support\Facades\Auth;

trait Multitenantable
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function bootMultitenantable()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->current_copropiedad_id && !isset($model->copropiedad_id)) {
                    $model->copropiedad_id = $user->current_copropiedad_id;
                }
            }
        });
    }
}
