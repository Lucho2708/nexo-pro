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
 * Registra facturas de servicios públicos recibidas en garita por apartamento (Acueducto, Energía, Gas, etc.).
 *
 * [ASPECTO TÉCNICO / UI]
 * Modelo persistido en 'operations.recibos_publicos'. Emplea UUID y timestamps.
 */
class ReciboPublico extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'operations.recibos_publicos';

    protected $fillable = [
        'copropiedad_id',
        'unidad_id',
        'tipo_servicio',
        'empresa_emisora',
        'periodo_facturado',
        'estado',
        'recibido_por_guarda_id',
        'entregado_at',
        'entregado_a_nombre',
    ];

    protected $casts = [
        'entregado_at' => 'datetime',
    ];

    public function getTable()
    {
        return DB::getDriverName() === 'pgsql' ? 'operations.recibos_publicos' : 'recibos_publicos';
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
