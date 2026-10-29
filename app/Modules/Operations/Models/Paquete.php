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
 * Registra encomiendas y correspondencia recibidas en garita para las unidades.
 *
 * [ASPECTO TÉCNICO / UI]
 * Modelo persistido en 'operations.paquetes'.
 */
class Paquete extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'operations.paquetes';

    protected $fillable = [
        'copropiedad_id',
        'unidad_id',
        'empresa_mensajeria',
        'codigo_guia',
        'descripcion',
        'estado',
        'pin_entrega',
        'notificado_at',
        'recibido_por_guarda_id',
        'entregado_at',
        'entregado_a_nombre',
    ];

    protected $casts = [
        'entregado_at' => 'datetime',
    ];

    public function getTable()
    {
        return DB::getDriverName() === 'pgsql' ? 'operations.paquetes' : 'paquetes';
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
        return $this->belongsTo(User::class, 'recibido_por_guarda_id');
    }
}
