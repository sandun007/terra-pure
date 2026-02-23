<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SellerOrderItem extends Model
{
    use HasFactory;

    protected $table = 'seller_order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_code',
        'product_name',
        'quantity',
        'unit_price',
        'total_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    /**
     * Relationship with order
     */
    public function order()
    {
        return $this->belongsTo(SellerOrder::class, 'order_id');
    }

    /**
     * Relationship with product
     */
    public function product()
    {
        return $this->belongsTo(SellerProduct::class, 'product_id');
    }

    /**
     * Calculate subtotal
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}
