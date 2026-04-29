<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Asamblea extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'copropiedad_id',
        'titulo',
        'fecha',
        'status', // scheduled, in_progress, finished
        'settings',
    ];

    protected $casts = [
        'fecha' => 'datetime',
        'settings' => 'array',
    ];

    /**
     * Get the preguntas for the asamblea.
     */
    public function preguntas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Pregunta::class);
    }

    /**
     * Get the copropiedad that owns the asamblea.
     */
    public function copropiedad(): BelongsTo
    {
        return $this->belongsTo(Copropiedad::class);
    }

    /**
     * Get the dynamic log table name for this specific assembly.
     * Format: aslog_{short_id}
     */
    public function getLogTableName(): string
    {
        $shortId = substr($this->id, 0, 8);
        return "aslog_{$shortId}";
    }
}
