<?php

namespace App\Modules\Asamblea\Models;

use App\Modules\IAM\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Intervencion extends Model
{
    use HasFactory;

    protected $table = 'asamblea.intervenciones';

    protected $fillable = [
        'asamblea_id',
        'user_id',
        'status',
        'requested_at',
        'started_at',
        'finished_at',
        'duration_seconds',
        'notes',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory<static>
     */
    protected static function newFactory()
    {
        return \App\Modules\Asamblea\Database\Factories\IntervencionFactory::new();
    }

    public function asamblea(): BelongsTo
    {
        return $this->belongsTo(Asamblea::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
