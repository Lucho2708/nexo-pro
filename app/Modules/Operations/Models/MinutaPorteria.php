<?php

namespace App\Modules\Operations\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Modules\Property\Models\Copropiedad;
use App\Modules\IAM\Models\User;
use DomainException;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Bitácora inmutable forense de eventos, rondas y novedades de seguridad en garita.
 * Cumple con requerimientos de la Superintendencia de Vigilancia y Seguridad Privada.
 *
 * [ASPECTO TÉCNICO / UI]
 * Persistido en 'operations.minuta_porteria'. Incluye cadena criptográfica SHA-256
 * (prev_hash y hash_inmutable) y protección anti-mutación que rechaza updates o deletes.
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
        'hash_inmutable',
        'prev_hash',
        'evidencia_foto_path',
        'firmado_digitalmente',
    ];

    protected $casts = [
        'firmado_digitalmente' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Blindaje Forense: Impide cualquier mutación o borrado de registros una vez creados.
     */
    protected static function booted(): void
    {
        static::updating(function () {
            throw new DomainException('La minuta digital forense es inmutable y no permite modificaciones.');
        });

        static::deleting(function () {
            throw new DomainException('La minuta digital forense es inmutable y no permite eliminaciones.');
        });
    }

    public function getTable()
    {
        try {
            return DB::getDriverName() === 'pgsql' ? 'operations.minuta_porteria' : 'minuta_porteria';
        } catch (\Throwable) {
            return 'operations.minuta_porteria';
        }
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
