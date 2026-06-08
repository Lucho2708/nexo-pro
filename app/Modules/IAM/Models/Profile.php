<?php

namespace App\Modules\IAM\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use HasUuids;

    protected $table = 'iam.profiles';

    protected $fillable = [
        'user_id',
        'avatar_path',
        'phone',
        'birth_date',
        'preferences',
        'bio'
    ];

    protected $casts = [
        'preferences' => 'array',
        'birth_date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
