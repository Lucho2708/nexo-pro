<?php

namespace App\Modules\IAM\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    use HasUuids;

    protected $table = 'iam.permissions';

    protected $fillable = ['name', 'display_name'];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'iam.permission_role');
    }
}
