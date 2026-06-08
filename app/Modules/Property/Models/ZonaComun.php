<?php

namespace App\Modules\Property\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\Multitenantable;

class ZonaComun extends Model
{
    use HasFactory, HasUuids, Multitenantable;

    protected static function newFactory()
    {
        return \App\Modules\Property\Database\Factories\ZonaComunFactory::new();
    }

    protected $table = 'property.zonas_comunes';

    public function tenant()
    {
        return $this->belongsTo(Copropiedad::class, 'copropiedad_id');
    }

    protected $fillable = [
        'copropiedad_id',
        'nombre',
        'descripcion',
        'capacidad_maxima',
        'imagen_path',
        'costo',
        'settings',
        'activa'
    ];

    protected $casts = [
        'settings' => 'array',
        'costo' => 'decimal:2',
        'activa' => 'boolean',
    ];

    public function copropiedad()
    {
        return $this->belongsTo(Copropiedad::class);
    }

    public function reservas()
    {
        return $this->hasMany(Reserva::class, 'zona_id');
    }
}
