<?php

namespace App\Modules\Operations\Models;

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Multitenantable;

class Pqrs extends Model
{
    use HasFactory, HasUuids, Multitenantable;

    protected $table = 'operations.pqrs';

    protected static function newFactory()
    {
        return \Database\Factories\PqrsFactory::new();
    }

    public function tenant()
    {
        return $this->belongsTo(Copropiedad::class, 'copropiedad_id');
    }

    protected $fillable = [
        'unidad_id',
        'user_id',
        'copropiedad_id',
        'tipo',
        'asunto',
        'mensaje',
        'prioridad',
        'estado',
        'respuesta',
        'fecha_respuesta',
        'adjuntos',
    ];

    protected $casts = [
        'adjuntos'       => 'array',
        'fecha_respuesta' => 'datetime',
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
