<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'wholesaler_id',
        'order_number',
        'date', // Add this if column exists
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
        'date' => 'datetime', // Add this if column exists
        'status_updated_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    // Boot method to automatically set date if not provided
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // If date column exists and not set, set it to now
            if (Schema::hasColumn('orders', 'date') && !$order->date) {
                $order->date = now();
            }

            // Ensure order_date is set
            if (!$order->order_date) {
                $order->order_date = now();
            }
        });
    }

    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function orderItems(): HasMany
    {
        if (Schema::hasTable('wholesaler_order_items')) {
            return $this->hasMany(WholesalerOrderItem::class, 'order_id');
        } else {
            return $this->hasMany(OrderItem::class);
        }
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

    public function getOrderNoAttribute()
    {
        return $this->order_number;
    }
}
