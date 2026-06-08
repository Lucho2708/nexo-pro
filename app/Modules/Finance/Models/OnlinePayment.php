<?php

namespace App\Modules\Finance\Models;

use App\Modules\IAM\Models\User;

use App\Modules\Property\Models\Unidad;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnlinePayment extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'finance.online_payments';

    protected $fillable = [
        'user_id',
        'unidad_id',
        'amount',
        'currency',
        'reference',
        'wompi_id',
        'status',
        'payment_method',
        'signature',
        'gateway_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function unidad(): BelongsTo
    {
        return $this->belongsTo(Unidad::class);
    }
}
