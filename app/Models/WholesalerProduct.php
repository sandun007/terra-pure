<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WholesalerProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_id',
        'code',
        'name',
        'description',
        'display_price',
        'wholesale_price',
        'qty',
        'min_order_quantity',
        'category_id',
        'brand_id',
        'weight',
        'dimensions',
        'features',
        'barcode',
        'image',
        'status',
        'average_rating',
        'total_reviews',
        'total_ratings'
    ];

    protected $casts = [
        'display_price' => 'decimal:2',
        'wholesale_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'features' => 'array',
    ];

    /**
     * Get the wholesaler that owns the product.
     */
    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(Wholesaler::class);
    }

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the product.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include products for a specific wholesaler.
     */
    public function scopeForWholesaler($query, $wholesalerId)
    {
        return $query->where('wholesaler_id', $wholesalerId);
    }

    /**
     * Check if product is low in stock.
     */
    public function isLowStock(): bool
    {
        return $this->qty < 10;
    }

    /**
     * Get the final price after discount.
     */
    public function getFinalPriceAttribute(): float
    {
        return $this->display_price;
    }
    public function reviews()
    {
        return $this->hasMany(ProductReview::class)->where('approved', true)->orderBy('created_at', 'desc');
    }

    public function calculateAverageRating()
    {
        $totalRatings = $this->reviews()->sum('rating');
        $totalReviews = $this->reviews()->count();

        $averageRating = $totalReviews > 0 ? $totalRatings / $totalReviews : 0;

        $this->update([
            'average_rating' => round($averageRating, 2),
            'total_reviews' => $totalReviews,
            'total_ratings' => $totalRatings
        ]);
    }

    public function getPriceAttribute($value)
    {
        // If display_price is set, use it, otherwise use wholesale_price
        return $this->display_price ?: $this->wholesale_price;
    }

    /**
     * Get the image URL.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return asset('images/default-product.png');
        }

        return Storage::disk('public')->exists($this->image)
            ? asset('storage/' . $this->image)
            : asset('images/default-product.png');
    }
}
