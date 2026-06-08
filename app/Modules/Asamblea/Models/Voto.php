<?php

namespace App\Modules\Asamblea\Models;

use App\Modules\IAM\Models\User;
use App\Modules\Property\Models\Unidad;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Voto extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'asamblea.votos';

    protected $fillable = [
        'pregunta_id',
        'user_id',
        'unidad_id',
        'opcion_id',
        'peso', // Coeficiente de la unidad
    ];

    protected $casts = [
        'peso' => 'decimal:5'
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return \App\Modules\Asamblea\Database\Factories\VotoFactory::new();
    }

    /**
     * Get the question.
     */
    public function pregunta(): BelongsTo
    {
        return $this->belongsTo(Pregunta::class);
    }

    /**
     * Get the user who voted.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the unit associated with the vote.
     */
    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }

    /**
     * Get the option selected.
     */
    public function opcion(): BelongsTo
    {
        return $this->belongsTo(Opcion::class);
    }
}
