<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable=[
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
        'status'
    ];
}
