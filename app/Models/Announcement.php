<?php

namespace App\Models;

use App\Modules\IAM\Models\User;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'copropiedad_id',
        'user_id',
        'title',
        'message',
        'type',
        'target_role',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function copropiedad()
    {
        return $this->belongsTo(Copropiedad::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function usersRead()
    {
        return $this->belongsToMany(User::class, 'announcement_user')
            ->withPivot('read_at')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active and currently valid announcements.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>=', now());
            });
    }
}
