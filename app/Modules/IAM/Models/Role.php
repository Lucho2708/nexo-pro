<?php

namespace App\Modules\IAM\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasUuids;

    protected $table = 'iam.roles';

    protected $fillable = ['name', 'display_name', 'description'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'iam.permission_role');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'iam.role_user');
    }
}
