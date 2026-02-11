<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerShop extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'shop_name',
        'shop_slug',
        'description',
        'contact_email',
        'contact_phone',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'logo',
        'banner',
        'return_policy',
        'shipping_policy',
        'tax_rate',
        'status'
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function products()
    {
        return $this->hasMany(SellerProduct::class, 'seller_id', 'seller_id');
    }
}
