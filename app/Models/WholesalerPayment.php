<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalerPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_id',
        'transaction_id',
        'amount',
        'type',
        'payment_method',
        'status',
        'description',
        'metadata',
        'paid_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }
}
