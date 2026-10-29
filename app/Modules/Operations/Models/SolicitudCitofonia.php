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
 * [LÓGICA DE NEGOCIO / UX - HU-SEC-05]
 * Representa una solicitud de citofonía virtual enviada al residente por WhatsApp o Push.
 * Proporciona un enlace interactivo y seguro para autorizar o denegar el ingreso del visitante.
 */
class SolicitudCitofonia extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'operations.solicitudes_citofonia';

    protected $fillable = [
        'copropiedad_id',
        'unidad_id',
        'guarda_id',
        'visitante_nombre',
        'visitante_documento',
        'tipo_visita',
        'token_respuesta',
        'estado',
        'respondido_por_id',
        'respondido_at',
        'expira_at',
    ];

    protected $casts = [
        'respondido_at' => 'datetime',
        'expira_at' => 'datetime',
    ];

    public function getTable()
    {
        return DB::getDriverName() === 'pgsql' ? 'operations.solicitudes_citofonia' : 'solicitudes_citofonia';
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

    public function respondidoPor()
    {
        return $this->belongsTo(User::class, 'respondido_por_id');
    }
}
