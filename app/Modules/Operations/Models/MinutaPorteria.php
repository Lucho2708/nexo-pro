<?php

namespace App\Modules\Operations\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Bitácora inmutable de eventos, rondas y novedades de seguridad en garita.
 *
 * [ASPECTO TÉCNICO / UI]
 * Persistido en 'operations.minuta_porteria'.
 */
class MinutaPorteria extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'operations.minuta_porteria';

    protected $fillable = [
        'copropiedad_id',
        'guarda_id',
        'tipo_evento',
        'prioridad',
        'descripcion',
    ];

    public function getTable()
    {
        return DB::getDriverName() === 'pgsql' ? 'operations.minuta_porteria' : 'minuta_porteria';
    }

    public function copropiedad()
    {
        return $this->belongsTo(Copropiedad::class);
    }

    public function guarda()
    {
        return $this->belongsTo(User::class, 'guarda_id');
    }
}
