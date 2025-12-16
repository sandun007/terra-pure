<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalerShop extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_id',
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
        'status'
    ];

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function products()
    {
        return $this->hasMany(WholesalerProduct::class, 'wholesaler_id', 'wholesaler_id');
    }
}
