<?php

namespace App\Modules\Operations\Models;

use App\Modules\IAM\Models\User;

use App\Modules\Property\Models\Copropiedad;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SystemLog extends Model
{
    use HasUuids;

    protected $table = 'operations.system_logs';

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
