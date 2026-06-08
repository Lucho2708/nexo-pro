<?php

namespace App\Modules\Property\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TipoUnidad extends Model
{
    use HasFactory, HasUuids;

    protected static function newFactory()
    {
        return \App\Modules\Property\Database\Factories\TipoUnidadFactory::new();
    }

    protected $table = 'property.tipos_unidad';

    protected $fillable = [
        'copropiedad_id',
        'nombre',
        'area_m2',
    ];

    /**
     * El conjunto al que pertenece este tipo de unidad.
     */
    public function copropiedad(): BelongsTo
    {
        return $this->belongsTo(Copropiedad::class);
    }

    /**
     * Los componentes que definen este tipo de unidad (Habitaciones, baños, etc).
     */
    public function componentes(): BelongsToMany
    {
        return $this->belongsToMany(ComponenteUnidad::class, 'property.componente_tipo_unidad', 'tipo_unidad_id', 'componente_id')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }

    /**
     * Las unidades reales que usan este modelo.
     */
    public function unidades(): HasMany
    {
        return $this->hasMany(Unidad::class, 'tipo_unidad_id');
    }
}
