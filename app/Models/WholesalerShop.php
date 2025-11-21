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
        'logo',
        'banner',
        'contact_email',
        'contact_phone',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'social_links',
        'return_policy',
        'shipping_policy',
        'is_verified',
        'status'
    ];

    protected $casts = [
        'social_links' => 'array',
        'is_verified' => 'boolean',
    ];

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }
}
