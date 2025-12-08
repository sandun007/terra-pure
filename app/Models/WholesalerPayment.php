<?php
// app/Models/WholesalerPayment.php

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
        'payment_type',
        'payment_method',
        'status',
        'description',
        'notes',
        'metadata',
        'processed_at',
        'completed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'metadata' => 'array',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    // Scopes
    public function scopeCredit($query)
    {
        return $query->where('type', 'credit');
    }

    public function scopeDebit($query)
    {
        return $query->where('type', 'debit');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
