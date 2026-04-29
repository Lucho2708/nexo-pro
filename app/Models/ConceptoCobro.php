<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConceptoCobro extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'copropiedad_id',
        'nombre',
        'codigo',
        'descripcion',
        'valor_fijo',
        'es_obligatorio',
    ];

    public function copropiedad()
    {
        return $this->belongsTo(Copropiedad::class);
    }
}
