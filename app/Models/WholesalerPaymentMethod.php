<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WholesalerPaymentMethod extends Model
{
    protected $fillable = [
        'wholesaler_id',
        'method_type',
        'account_name',
        'account_number',
        'bank_name',
        'branch',
        'mobile_provider',
        'is_default',
        'status'
    ];

    protected $casts = [
        'is_default' => 'boolean'
    ];

    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function getFullMethodAttribute()
    {
        if ($this->method_type === 'bank') {
            return "{$this->bank_name} - {$this->account_number} ({$this->account_name})";
        }

        return "{$this->mobile_provider} - {$this->account_number} ({$this->account_name})";
    }
}
