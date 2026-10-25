<?php

namespace App\Modules\Operations\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * [LÓGICA DE NEGOCIO / UX]
 * Representa a un prospecto o decisor de una copropiedad (administrador, consejero o constructor)
 * que interactuó con la calculadora de ROI o el formulario de demostración en la Landing Page.
 * Permite segmentar por tamaño y priorizar la atención comercial según su volumen de unidades.
 *
 * [ASPECTO TÉCNICO / UI]
 * Modelo Eloquent persistido en la tabla 'operations.leads' (esquema segregado PostgreSQL con fallback
 * a SQLite para pruebas en memoria). Emplea UUID v4 como identificador inmutable y SoftDeletes para auditoría.
 */
class Lead extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'operations.leads';

    protected $fillable = [
        'nombre',
        'email',
        'telefono',
        'copropiedad_nombre',
        'unidades_totales',
        'rol',
        'segmento',
        'cuota_promedio_mensual',
        'ahorro_estimado_anual',
        'horas_ahorradas_mes',
        'cartera_recuperable_estimada',
        'estado',
        'score',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'unidades_totales' => 'integer',
        'cuota_promedio_mensual' => 'decimal:2',
        'ahorro_estimado_anual' => 'decimal:2',
        'cartera_recuperable_estimada' => 'decimal:2',
        'horas_ahorradas_mes' => 'integer',
        'score' => 'integer',
        'metadata' => 'array',
    ];

    /**
     * Resuelve el nombre de la tabla tolerando entornos SQLite en memoria durante tests.
     */
    public function getTable()
    {
        if (config('database.default') === 'sqlite' || DB::getDriverName() === 'sqlite') {
            return 'leads';
        }
        return parent::getTable();
    }

    /**
     * Scope para prospectos Enterprise (>= 300 unidades).
     */
    public function scopeEnterprise($query)
    {
        return $query->where('segmento', 'enterprise');
    }

    /**
     * Scope para prospectos nuevos pendientes de contacto.
     */
    public function scopeNuevo($query)
    {
        return $query->where('estado', 'nuevo');
    }

    /**
     * Determina si el prospecto pertenece al segmento prioritario Enterprise.
     */
    public function isEnterprise(): bool
    {
        return $this->segmento === 'enterprise' || $this->unidades_totales >= 300;
    }

    /**
     * Determina si el lead tiene un puntaje comercial de alta calificación.
     */
    public function isHighlyQualified(): bool
    {
        return $this->score >= 75;
    }
}
