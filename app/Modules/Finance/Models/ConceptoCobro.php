<?php

namespace App\Modules\Finance\Models;

use App\Modules\Property\Models\Copropiedad;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoCobro extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'finance.conceptos_cobro';

    protected $fillable = [
        'copropiedad_id',
        'nombre',
        'codigo',
        'descripcion',
        'valor_fijo',
        'es_obligatorio',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return \Database\Factories\ConceptoCobroFactory::new();
    }

    public function copropiedad()
    {
        return $this->belongsTo(Copropiedad::class);
    }
}
