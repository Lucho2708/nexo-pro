<?php

namespace App\Models;

use App\Modules\IAM\Models\User;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'user_id',
        'copropiedad_id',
        'subject',
        'description',
        'category',
        'priority',
        'status',
        'resolution_notes'
    ];

    /**
     * El usuario que generó el ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * La copropiedad (contexto) desde donde se reporta.
     */
    public function copropiedad(): BelongsTo
    {
        return $this->belongsTo(Copropiedad::class);
    }
}
