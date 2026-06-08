<?php

namespace App\Modules\IAM\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegalDocument extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'iam.legal_documents';

    protected static function newFactory()
    {
        return \Database\Factories\LegalDocumentFactory::new();
    }

    protected $fillable = [
        'type',
        'title',
        'body',
        'version',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function consents()
    {
        return $this->hasMany(LegalConsent::class);
    }

    public static function getActive(string $type)
    {
        return self::where('type', $type)->where('is_active', true)->orderByDesc('created_at')->first();
    }
}
