<?php

namespace App\Modules\Property\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ComponenteUnidad extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'property.componentes_unidad';

    protected $fillable = [
        'nombre',
    ];

    /**
     * Relación con los tipos de unidad que usan este componente.
     */
    public function tiposUnidad(): BelongsToMany
    {
        return $this->belongsToMany(TipoUnidad::class, 'componente_tipo_unidad', 'componente_id', 'tipo_unidad_id')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}
