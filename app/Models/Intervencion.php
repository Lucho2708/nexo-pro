<?php

namespace App\Models;

use App\Modules\IAM\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Intervencion extends Model
{
    use HasFactory;

    protected $table = 'intervenciones';

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

    public function asamblea(): BelongsTo
    {
        return $this->belongsTo(Asamblea::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
