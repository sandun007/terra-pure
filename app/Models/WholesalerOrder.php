<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WholesalerOrder extends Model
{
    protected $table = 'orders'; // Make sure table name matches

    protected $fillable = [
        'wholesaler_id',
        'order_no', // This is what the error shows
        'order_number', // You might have both fields
        'subtotal',
        'tax',
        'shipping',
        'total_amount',
        'payment_method',
        'payment_status',
        'status',
        'shipping_address',
        'customer_name',
        'customer_email',
        'customer_phone',
        'notes',
        'order_date',
        'status_updated_at',
        'status_notes',
        'cancelled_at'
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'status_updated_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedStatusAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getFormattedPaymentStatusAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->payment_status));
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rs. ' . number_format($this->total_amount, 2);
    }
}
