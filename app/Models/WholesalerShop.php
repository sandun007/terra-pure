<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'status',
        'rating',
        'total_reviews',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
    ];

    // Relationships
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function products()
    {
        return $this->hasMany(WholesalerProduct::class, 'wholesaler_id', 'wholesaler_id')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc');
    }

    public function activeProducts()
    {
        return $this->hasMany(WholesalerProduct::class, 'wholesaler_id', 'wholesaler_id')
            ->where('status', 'active')
            ->where('qty', '>', 0);
    }

    // Accessors
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : asset('images/default-shop-logo.png');
    }

    public function getBannerUrlAttribute()
    {
        return $this->banner ? asset('storage/' . $this->banner) : asset('images/default-shop-banner.jpg');
    }

    public function getFormattedRatingAttribute()
    {
        return number_format($this->rating, 1);
    }

    // Methods
    public function updateRating()
    {
        $reviews = ProductReview::whereHas('product', function ($query) {
            $query->where('wholesaler_id', $this->wholesaler_id);
        })->where('is_approved', true)->get();

        if ($reviews->count() > 0) {
            $this->rating = $reviews->avg('rating');
            $this->total_reviews = $reviews->count();
            $this->save();
        }
    }
}
