<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Traits\Multitenantable;

class Reserva extends Model
{
    use HasFactory, HasUuids, Multitenantable;

    public function tenant()
    {
        return $this->belongsTo(Copropiedad::class, 'copropiedad_id');
    }

    protected $fillable = [
        'zona_id',
        'user_id',
        'unidad_id',
        'copropiedad_id',
        'fecha',
        'hora_inicio',
        'hora_fin',
        'cantidad_personas',
        'monto_pagado',
        'estado',
        'notas_admin'
    ];

    protected $casts = [
        'fecha' => 'date',
        'monto_pagado' => 'decimal:2',
    ];

    public function zona()
    {
        return $this->belongsTo(ZonaComun::class, 'zona_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }
}
