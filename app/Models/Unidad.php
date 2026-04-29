<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Multitenantable;

class Unidad extends Model
{
    use HasFactory, HasUuids, Multitenantable;

    protected $table = 'unidades';
    public function tenant()
    {
        return $this->belongsTo(Copropiedad::class, 'copropiedad_id');
    }

    protected $fillable = [
        'copropiedad_id',
        'nombre',
        'torre',
        'piso',
        'coeficiente',
        'propietario_nombre',
        'propietario_identificacion',
        'email_contacto',
        'saldo_actual',
    ];

    public function copropiedad()
    {
        return $this->belongsTo(Copropiedad::class);
    }

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class);
    }

    /**
     * Get the users/owners associated with this unit.
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'unidad_user')
            ->withPivot('role')
            ->withTimestamps();
    }
}
