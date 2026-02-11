<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerOrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'quantity',
        'unit_price',
        'total_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(SellerOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(SellerProduct::class, 'product_id');
    }
}
