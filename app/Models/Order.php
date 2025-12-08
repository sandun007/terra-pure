<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_no',
        'date',
        'delivery_date',
        'seller_no',
        'item_code',
        'item_name',
        'qty',
        'price',
        'total',
        'customer_name',
        'customer_phone_01',
        'customer_phone_02',
        'customer_address',
        'city',
        'remark',
        'status_date',
        'status',
        'wholesaler_id', // Added this
        'customer_id',
        'total_amount',
        'payment_status',
        'shipping_address'
    ];

    // Cast attributes
    protected $casts = [
        'total_amount' => 'decimal:2',
        'date' => 'date',
        'delivery_date' => 'date',
        'status_date' => 'datetime',
    ];

    // Relationship with Wholesaler
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    // Relationship with Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // Relationship with Order Items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope for wholesaler orders
    public function scopeForWholesaler($query, $wholesalerId)
    {
        return $query->where('wholesaler_id', $wholesalerId);
    }

    // Get status badge color
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'confirmed' => 'primary',
            'processing' => 'info',
            'shipped' => 'secondary',
            'delivered' => 'success',
            'cancelled' => 'danger'
        ];

        return $colors[$this->status] ?? 'secondary';
    }
}
