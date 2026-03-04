<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WholesalerShop extends Model
{
    use HasFactory;

    protected $table = 'wholesaler_shops';

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
        'tax_rate',
        'status'
    ];

    /**
     * Relationship with wholesaler
     */
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class, 'wholesaler_id');
    }

    /**
     * Relationship with products
     */
    public function products()
    {
        return $this->hasMany(WholesalerProduct::class, 'wholesaler_id', 'wholesaler_id');
    }

    /**
     * Get shop URL
     */
    public function getUrlAttribute()
    {
        return route('wholesaler.shop.index', ['slug' => $this->shop_slug ?? $this->id]);
    }

    /**
     * Generate unique slug
     */
    public static function generateUniqueSlug($name, $wholesalerId)
    {
        $slug = Str::slug($name);
        $count = self::where('shop_slug', 'LIKE', "{$slug}%")
            ->where('wholesaler_id', $wholesalerId)
            ->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
