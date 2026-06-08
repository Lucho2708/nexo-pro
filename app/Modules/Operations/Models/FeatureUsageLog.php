<?php

namespace App\Modules\Operations\Models;

use App\Modules\IAM\Models\User;

use App\Modules\Property\Models\Copropiedad;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureUsageLog extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'operations.feature_usage_logs';

    public $timestamps = false;

    protected $fillable = ['user_id', 'copropiedad_id', 'feature', 'action', 'metadata', 'used_at'];

    protected $casts = [
        'used_at' => 'datetime',
        'metadata' => 'array',
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
