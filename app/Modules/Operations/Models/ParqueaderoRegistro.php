<?php

namespace App\Modules\Operations\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Modules\IAM\Models\User;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Registra entradas y salidas vehiculares para control de aforo y bahías de parqueadero.
 *
 * [ASPECTO TÉCNICO / UI]
 * Persistido en 'operations.parqueaderos_registro'.
 */
class ParqueaderoRegistro extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'operations.parqueaderos_registro';

    protected $fillable = [
        'copropiedad_id',
        'unidad_id',
        'tipo_espacio',
        'placa_vehiculo',
        'numero_bahia',
        'estado',
        'ingreso_at',
        'salida_at',
        'guarda_id',
    ];

    protected $casts = [
        'ingreso_at' => 'datetime',
        'salida_at' => 'datetime',
    ];

    public function getTable()
    {
        return DB::getDriverName() === 'pgsql' ? 'operations.parqueaderos_registro' : 'parqueaderos_registro';
    }

    public function copropiedad()
    {
        return $this->belongsTo(Copropiedad::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function guarda()
    {
        return $this->belongsTo(User::class, 'guarda_id');
    }
}
