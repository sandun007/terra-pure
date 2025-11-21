<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalerPaymentMethod extends Model
{
    use HasFactory;

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
        'is_default' => 'boolean',
    ];

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }
}
