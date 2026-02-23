<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    /**
     * Get the product that belongs to the cart
     */
    public function product()
    {
        return $this->belongsTo(WholesalerProduct::class, 'product_id');
    }

    /**
     * Get the user that owns the cart
     */
    public function user()
    {
        return $this->belongsTo(Wholesaler::class, 'user_id');
    }

    /**
     * Get the subtotal for this cart item
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }
}
