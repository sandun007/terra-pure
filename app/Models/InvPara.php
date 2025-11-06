<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvPara extends Model
{
    use HasFactory;
    protected $fillable=[
        'company_name',
        'address',
        'phone_no_01',
        'phone_no_02',
        'email',
        'logo',
        'category_code',
        'brand_code',
        'product_code',
        'seller_code',
        'supplier_code',
        'order_code',
        'stock_code',
        'grn_code',
        'inv_no',
    ];
}
