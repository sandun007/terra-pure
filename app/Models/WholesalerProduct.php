<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class WholesalerProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_id',
        'category_id',
        'brand_id',
        'code',
        'name',
        'slug',
        'sku',
        'description',
        'cost_price',
        'selling_price',
        'discount',
        'packaging_cost',
        'qty',
        'min_stock_level',
        'features',
        'barcode',
        'image',
        'status',
        'average_rating',
        'total_reviews',
        'total_ratings'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'packaging_cost' => 'decimal:2',
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
        return $this->qty <= $this->min_stock_level;
    }

    /**
     * Calculate final selling price after discount.
     */
    public function getFinalPriceAttribute(): float
    {
        if ($this->discount > 0) {
            return $this->selling_price - ($this->selling_price * ($this->discount / 100));
        }
        return $this->selling_price;
    }

    /**
     * Calculate total cost including packaging.
     */
    public function getTotalCostAttribute(): float
    {
        return $this->cost_price + $this->packaging_cost;
    }

    /**
     * Calculate profit margin.
     */
    public function getProfitMarginAttribute(): float
    {
        $totalCost = $this->total_cost;
        $sellingPrice = $this->final_price;

        if ($totalCost > 0) {
            return (($sellingPrice - $totalCost) / $totalCost) * 100;
        }

        return 0;
    }

    /**
     * Calculate profit amount.
     */
    public function getProfitAmountAttribute(): float
    {
        return $this->final_price - $this->total_cost;
    }

    // Add these methods to WholesalerProduct model

    // In WholesalerProduct model, update the reviews method:
    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id')->where('status', 'active')->orderBy('created_at', 'desc');
    }

    public function activeReviews()
    {
        return $this->reviews()->where('status', 'active')->orderBy('created_at', 'desc');
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

    /**
     * Get the display price for customers.
     */
    public function getDisplayPriceAttribute()
    {
        return $this->final_price;
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
    /**
     * Check if product is out of stock.
     */
    public function isOutOfStock(): bool
    {
        return $this->qty === 0;
    }

    /**
     * Check if product needs restocking.
     */
    public function needsRestocking(): bool
    {
        return $this->qty <= $this->min_stock_level;
    }

    /**
     * Update stock quantity.
     */
    public function updateStock($quantity, $type = 'add')
    {
        if ($type === 'add') {
            $this->increment('qty', $quantity);
        } elseif ($type === 'subtract') {
            $this->decrement('qty', $quantity);
        } elseif ($type === 'set') {
            $this->update(['qty' => $quantity]);
        }

        return $this;
    }

    /**
     * Calculate recommended reorder quantity.
     */
    public function getReorderQuantity(): int
    {
        // Calculate based on min_stock_level and average monthly sales
        return max($this->min_stock_level * 3, 100);
    }
}
