<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'barcode',
        'name',
        'brand',
        'category',
        'description',
        'type',
        'img',
        'add_by',
        'update_by',
        'status',
        'cancel',
        'stock_price',
        'display_price',
        'discount',
        'qty',
        'min_qty',
        'packing_cost',
        'seller_code',
        'wholesaler_id', // Added this
        'wholesale_price',
        'min_order_quantity'
    ];

    // Cast attributes
    protected $casts = [
        'wholesale_price' => 'decimal:2',
        'min_order_quantity' => 'integer',
    ];

    // Relationship with Wholesaler
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    // Relationship with Order Items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scope for wholesaler products
    public function scopeForWholesaler($query, $wholesalerId)
    {
        return $query->where('wholesaler_id', $wholesalerId);
    }

    // Check if product is low stock
    public function getIsLowStockAttribute()
    {
        return $this->qty < 10;
    }

    // Get stock status
    public function getStockStatusAttribute()
    {
        if ($this->qty == 0) {
            return 'out-of-stock';
        } elseif ($this->qty < 10) {
            return 'low-stock';
        } else {
            return 'in-stock';
        }
    }
}
