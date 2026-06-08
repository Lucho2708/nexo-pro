<?php
namespace App\Modules\Finance\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Modules\Property\Models\Copropiedad;
use App\Modules\Property\Models\Unidad;
use App\Traits\Multitenantable;

class Transaccion extends Model
{
    use HasFactory, HasUuids, Multitenantable;

    protected $table = 'finance.transacciones';
    public function tenant()
    {
        return $this->belongsTo(Copropiedad::class, 'copropiedad_id');
    }

    protected $fillable = [
        'copropiedad_id',
        'user_id',
        'unidad_id',
        'concepto_id',
        'tipo',
        'monto',
        'fecha',
        'referencia',
        'soporte_path',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\TransaccionFactory::new();
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function concepto()
    {
        return $this->belongsTo(ConceptoCobro::class, 'concepto_id');
    }
}
