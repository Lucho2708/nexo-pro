<?php

namespace App\Modules\Asamblea\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Opcion extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'asamblea.opciones';

    protected $fillable = [
        'pregunta_id',
        'titulo',
        'order',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return \App\Modules\Asamblea\Database\Factories\OpcionFactory::new();
    }

    /**
     * Get the question that owns the option.
     */
    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class);
    }

    /**
     * Get the votes for this option.
     */
    public function votos(): HasMany
    {
        return $this->hasMany(Voto::class);
    }
}
