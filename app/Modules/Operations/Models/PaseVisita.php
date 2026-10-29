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
 * [LÓGICA DE NEGOCIO / UX - HU-SEC-04]
 * Representa un pase de visita digital generado por un residente (con código QR y PIN).
 * Permite pre-autorizar ingresos individuales o por lotes de eventos familiares sin llamadas a garita.
 */
class PaseVisita extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'operations.pases_visita';

    protected $fillable = [
        'copropiedad_id',
        'unidad_id',
        'residente_id',
        'token_acceso',
        'codigo_pin',
        'visitante_nombre',
        'visitante_documento',
        'placa_vehiculo',
        'tipo_pase',
        'valido_desde',
        'valido_hasta',
        'usos_permitidos',
        'usos_consumidos',
        'estado',
    ];

    protected $casts = [
        'valido_desde' => 'datetime',
        'valido_hasta' => 'datetime',
        'usos_permitidos' => 'integer',
        'usos_consumidos' => 'integer',
    ];

    public function getTable()
    {
        return DB::getDriverName() === 'pgsql' ? 'operations.pases_visita' : 'pases_visita';
    }

    public function copropiedad()
    {
        return $this->belongsTo(Copropiedad::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function residente()
    {
        return $this->belongsTo(User::class, 'residente_id');
    }

    public function estaVigente(\DateTimeInterface $momento = null): bool
    {
        $ahora = $momento ?? now();

        if ($this->estado !== 'activo') {
            return false;
        }

        if ($this->usos_consumidos >= $this->usos_permitidos) {
            return false;
        }

        if ($ahora < $this->valido_desde || $ahora > $this->valido_hasta) {
            return false;
        }

        return true;
    }
}
