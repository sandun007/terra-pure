<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerShop extends Model
{
    use HasFactory;

    protected $table = 'seller_shops';

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

    /**
     * Relationship with seller
     */
    public function seller()
    {
        return $this->belongsTo(Saler::class, 'seller_id');
    }

    /**
     * Relationship with products
     */
    public function products()
    {
        return $this->hasMany(SellerProduct::class, 'seller_id', 'seller_id');
    }

    /**
     * Get shop URL
     */
    public function getUrlAttribute()
    {
        return route('shop.index', ['slug' => $this->shop_slug ?? $this->id]);
    }

    /**
     * Generate unique slug
     */
    public static function generateUniqueSlug($name, $sellerId)
    {
        $slug = \Str::slug($name);
        $count = self::where('shop_slug', 'LIKE', "{$slug}%")
            ->where('seller_id', $sellerId)
            ->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
