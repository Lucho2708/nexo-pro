<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    protected $fillable = [
        'level_name',
        'level',
        'message',
        'context',
        'env',
        'copropiedad_id',
        'user_id'
    ];

    protected $casts = [
        'context' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function copropiedad()
    {
        return $this->belongsTo(Copropiedad::class);
    }
}
