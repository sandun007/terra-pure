<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WholesalerOrder extends Model
{
    // SoftDeletes removed since deleted_at column doesn't exist in database

    protected $fillable = [
        'wholesaler_id',
        'order_number',
        'subtotal',
        'tax',
        'shipping',
        'total_amount',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'payment_method',
        'payment_status',
        'status',
        'notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];



    // Relationships
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function items()
    {
        return $this->hasMany(WholesalerOrderItem::class, 'order_id');
    }

    // Accessors
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'processing' => 'primary',
            'shipped' => 'success',
            'delivered' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
            'refunded' => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    public function getPaymentMethodTextAttribute()
    {
        return ucwords(str_replace('_', ' ', $this->payment_method));
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rs. ' . number_format($this->total_amount, 2);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }
    // In WholesalerOrder model

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class, 'order_id');
    }
}
