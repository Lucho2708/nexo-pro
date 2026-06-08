<?php

namespace App\Modules\Operations\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemModule extends Model
{
    use HasFactory;

    protected $table = 'operations.system_modules';

    protected $fillable = ['name', 'key', 'description', 'is_active'];
}
