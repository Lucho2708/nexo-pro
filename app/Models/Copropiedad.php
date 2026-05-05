<?php

namespace App\Models;

use App\Modules\IAM\Models\User;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Copropiedad extends Model
{
    /** @use HasFactory<\Database\Factories\CopropiedadFactory> */
    use HasFactory, HasUuids;

    protected $table = 'copropiedades';

    /**
     * Define columns that are actual table columns (not virtual data).
     * This avoids the "data" column issues in SQLite.
     */
    public static function getCustomColumns(): array
    {
        return [
            'id', 'nit', 'nombre', 'direccion', 'ciudad', 'plan', 
            'license_status', 'license_expires_at',
            'settings', 'unidades_totales', 'torres', 'area_construida_total', 'created_at', 'updated_at'
        ];
    }

    protected $fillable = [
        'nit',
        'nombre',
        'direccion',
        'ciudad',
        'plan',
        'license_status',
        'license_expires_at',
        'settings',
        'unidades_totales',
        'torres',
        'unit_types_locked',
        'area_construida_total',
    ];
protected $casts = [
    'settings' => 'array',
    'unidades_totales' => 'integer',
    'torres' => 'integer',
    'unit_types_locked' => 'boolean',
];

/**
 * Canonical default settings structure.
...
     */
    public static function defaultSettings(): array
    {
        return [
            'payments_enabled'  => false,
            'can_charge_online' => false,
            'payment_gateway'   => 'wompi',
            'pqrs_enabled'      => true,
            'reservas_enabled'  => true,
            'asamblea_virtual_enabled' => false,
            'interes_mora'      => 1.5,
            'dia_cobro'         => 1,
            'gateways' => [
                'wompi' => [
                    'enabled' => false,
                    'label' => 'Wompi (PSE, Tarjetas, Nequi)',
                    'type' => 'gateway'
                ],
                'aval' => [
                    'enabled' => false,
                    'label' => 'Aval Pay Center',
                    'type' => 'redirect',
                    'url' => ''
                ],
                'manual' => [
                    'enabled' => true,
                    'label' => 'Efectivo / Consignación',
                    'type' => 'instruction'
                ]
            ]
        ];
    }

    /**
     * Check if a feature toggle is enabled (uses defaults if not set).
     */
    public function featureEnabled(string $key): bool
    {
        $defaults = self::defaultSettings();
        return (bool) ($this->settings[$key] ?? $defaults[$key] ?? false);
    }

    /**
     * Alias for backwards-compatibility.
     */
    public function hasFeature(string $feature): bool
    {
        return $this->featureEnabled($feature);
    }

    /**
     * Check if the Super Admin specifically granted online charging.
     */
    public function canChargeOnline(): bool
    {
        return $this->featureEnabled('can_charge_online') && $this->featureEnabled('payments_enabled');
    }

    /**
     * Get a specific setting or default.
     */
    public function getSetting(string $key, $default = null): mixed
    {
        return $this->settings[$key] ?? self::defaultSettings()[$key] ?? $default;
    }

    /**
     * Get the users currently active in this copropiedad.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'current_copropiedad_id');
    }

    /**
     * Get the administrators officially managed by this copropiedad.
     */
    public function administradores(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'admin_copropiedad', 'copropiedad_id', 'user_id');
    }

    /**
     * Get the units for this copropiedad.
     */
    public function unidades(): HasMany
    {
        return $this->hasMany(Unidad::class, 'copropiedad_id');
    }

    public function tiposUnidad(): HasMany
    {
        return $this->hasMany(TipoUnidad::class, 'copropiedad_id');
    }
}
