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
 * Representa el registro de acceso de un visitante o vehículo en la garita de seguridad.
 * Permite controlar en tiempo real el ingreso, tiempo de permanencia y autorización hacia las unidades.
 *
 * [ASPECTO TÉCNICO / UI]
 * Modelo Eloquent persistido en 'operations.visitas' (PostgreSQL particionado / SQLite test).
 * Emplea HasUuids como identificador primario y relaciones con copropiedad, unidad y guarda.
 */
class Visita extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'operations.visitas';

    protected $fillable = [
        'copropiedad_id',
        'unidad_id',
        'visitante_nombre',
        'visitante_documento',
        'tipo_visita',
        'placa_vehiculo',
        'empresa_servicio',
        'tiempo_limite_minutos',
        'pre_autorizado',
        'pase_visita_id',
        'estado',
        'ingreso_at',
        'salida_at',
        'guarda_id',
        'observaciones',
    ];

    protected $casts = [
        'pre_autorizado' => 'boolean',
        'ingreso_at' => 'datetime',
        'salida_at' => 'datetime',
    ];

    public function getTable()
    {
        return DB::getDriverName() === 'pgsql' ? 'operations.visitas' : 'visitas';
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
