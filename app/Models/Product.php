<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

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
        'stock',
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

    public function brand()
{
    return $this->belongsTo(Brand::class, 'brand_id');
}

public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

    // Relationship with Order Items
    public function orderItems(): HasMany
    {
        if (Schema::hasTable('wholesaler_order_items')) {
            return $this->hasMany(WholesalerOrderItem::class, 'product_id');
        } else {
            // return $this->hasMany(OrderItem::class);
            return $this->hasMany(WholesalerOrderItem::class, 'product_id'); // Default to wholesaler items
        }
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
