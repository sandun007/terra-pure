<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable=[
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
        'seller_code'
    ];
}
