<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pregunta extends Model
{
    use HasFactory;

    protected $fillable = [
        'asamblea_id',
        'titulo',
        'descripcion',
        'tipo', // simple, multiple
        'status', // draft, open, closed
    ];

    /**
     * Get the asamblea that owns the question.
     */
    public function asamblea(): BelongsTo
    {
        return $this->belongsTo(Asamblea::class);
    }

    /**
     * Get the options for this question.
     */
    public function opciones(): HasMany
    {
        return $this->hasMany(Opcion::class);
    }

    /**
     * Get the votes for this question.
     */
    public function votos(): HasMany
    {
        return $this->hasMany(Voto::class);
    }
}
