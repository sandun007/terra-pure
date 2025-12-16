<?php
// app/Models/ShopProduct.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'wholesaler_id',
        'product_id',
        'slug',
        'is_featured',
        'is_active',
        'sort_order',
        'variants',
        'view_count',
        'sold_count',
        'rating',
        'review_count'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'variants' => 'array',
        'rating' => 'decimal:2'
    ];

    // Relationships
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function product()
    {
        return $this->belongsTo(WholesalerProduct::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ShopProductImage::class);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('view_count', 'desc');
    }

    public function scopeBestSelling($query)
    {
        return $query->orderBy('sold_count', 'desc');
    }

    public function scopeTopRated($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    // Accessors
    public function getMainImageAttribute()
    {
        return $this->images->where('is_primary', true)->first() ??
            $this->images->first() ??
            $this->product->image;
    }

    public function getDisplayPriceAttribute()
    {
        return $this->product->display_price;
    }

    public function getWholesalePriceAttribute()
    {
        return $this->product->wholesale_price;
    }

    public function getStockAttribute()
    {
        return $this->product->qty;
    }

    // Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function updateRating()
    {
        $this->rating = $this->reviews()->avg('rating') ?? 0;
        $this->review_count = $this->reviews()->count();
        $this->save();
    }

    public function generateSlug()
    {
        $slug = Str::slug($this->product->name);
        $count = 1;

        while (self::where('slug', $slug)->exists()) {
            $slug = Str::slug($this->product->name) . '-' . $count++;
        }

        return $slug;
    }
}
