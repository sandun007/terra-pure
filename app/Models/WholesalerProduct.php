<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'display_price',
        'previous_selling_price',
        'discount',
        'profit_margin',
        'packaging_cost',
        'qty',
        'total_sold',
        'min_stock_level',
        'features',
        'barcode',
        'image',
        'status',
        'average_rating',
        'total_reviews',
        'total_ratings',
        'last_price_updated_by',
        'last_price_updated_at',
        'price_changes_count'
    ];

    protected $casts = [
        'cost_price' => 'float',
        'selling_price' => 'float',
        'display_price' => 'float',
        'previous_selling_price' => 'float',
        'discount' => 'float',
        'profit_margin' => 'float',
        'packaging_cost' => 'float',
        'features' => 'array',
        'last_price_updated_at' => 'datetime',
        'qty' => 'integer',
        'total_sold' => 'integer',
        'min_stock_level' => 'integer',
        'average_rating' => 'float',
        'total_reviews' => 'integer',
        'total_ratings' => 'integer',
        'price_changes_count' => 'integer',
    ];

    protected $appends = ['final_price', 'display_price_formatted', 'final_price_formatted'];

    /**
     * Relationships
     */
    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get price changes for this product
     */
    public function priceChanges(): HasMany
    {
        return $this->hasMany(EmployeePriceChange::class, 'product_id');
    }

    /**
     * Get latest price change
     */
    public function latestPriceChange()
    {
        return $this->hasOne(EmployeePriceChange::class, 'product_id')->latest();
    }

    /**
     * Get the employee who last updated the price
     */
    public function lastPriceUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'last_price_updated_by');
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(WholesalerProductPriceChange::class, 'product_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class, 'product_id')
            ->where('status', 'active')
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForWholesaler($query, $wholesalerId)
    {
        return $query->where('wholesaler_id', $wholesalerId);
    }

    public function scopeInStock($query)
    {
        return $query->where('qty', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('qty <= min_stock_level');
    }

    /**
     * Accessors - Price Calculations
     */

    /**
     * Get display price (what wholesaler sees)
     * Returns selling_price if display_price is null
     *
     * @return float
     */
    public function getDisplayPriceAttribute(): float
    {
        // If display_price is not null, use it
        if (!is_null($this->attributes['display_price'] ?? null)) {
            return (float) $this->attributes['display_price'];
        }

        // If selling_price is set, use it
        if (!is_null($this->attributes['selling_price'] ?? null)) {
            return (float) $this->attributes['selling_price'];
        }

        // Default fallback
        return 0.00;
    }

    /**
     * Get display price as formatted string (for backward compatibility)
     */
    public function getDisplayPriceFormattedAttribute(): string
    {
        return 'Rs. ' . number_format($this->display_price, 2);
    }

    /**
     * Final price after discount
     *
     * @return float
     */
    public function getFinalPriceAttribute(): float
    {
        $price = $this->display_price;

        if (!is_null($this->discount) && $this->discount > 0) {
            $price = $price - ($price * ($this->discount / 100));
        }

        return round($price, 2);
    }

    /**
     * Get final price as formatted string
     */
    public function getFinalPriceFormattedAttribute(): string
    {
        return 'Rs. ' . number_format($this->final_price, 2);
    }

    /**
     * Get original price (selling price) - safe method
     */
    public function getOriginalPriceAttribute(): float
    {
        return (float) ($this->attributes['selling_price'] ?? 0);
    }

    // Total cost including packaging
    public function getTotalCostAttribute(): float
    {
        $cost = (float) ($this->cost_price ?? 0);
        $packaging = (float) ($this->packaging_cost ?? 0);
        return round($cost + $packaging, 2);
    }

    // Profit margin percentage
    public function getProfitMarginPercentageAttribute(): float
    {
        $totalCost = $this->total_cost;
        if ($totalCost > 0) {
            return round((($this->final_price - $totalCost) / $totalCost) * 100, 2);
        }
        return 0;
    }

    // Profit amount
    public function getProfitAmountAttribute(): float
    {
        return round($this->final_price - $this->total_cost, 2);
    }

    /**
     * Check if product has discount
     */
    public function getHasDiscountAttribute(): bool
    {
        return !is_null($this->discount) && $this->discount > 0;
    }

    /**
     * Get discount percentage as string
     */
    public function getDiscountPercentageAttribute(): string
    {
        if ($this->has_discount) {
            return $this->discount . '% OFF';
        }
        return '';
    }

    /**
     * Stock Management
     */
    public function isLowStock(): bool
    {
        return $this->qty <= $this->min_stock_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->qty === 0;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->qty === 0) {
            return 'Out of Stock';
        }
        if ($this->qty <= $this->min_stock_level) {
            return 'Low Stock';
        }
        return 'In Stock';
    }

    public function getStockStatusColorAttribute(): string
    {
        if ($this->qty === 0) {
            return 'danger';
        }
        if ($this->qty <= $this->min_stock_level) {
            return 'warning';
        }
        return 'success';
    }

    /**
     * Update prices with automatic display price calculation
     */
    public function updatePricesWithMargin(array $prices, $employeeId, $notes = null)
    {
        // Store old prices
        $oldPrices = [
            'selling_price' => $this->selling_price,
            'display_price' => $this->display_price,
            'discount' => $this->discount,
            'final_price' => $this->final_price,
        ];

        // Update selling price
        if (isset($prices['selling_price'])) {
            $this->selling_price = $prices['selling_price'];
            // Only update display_price if it's not explicitly set or is null
            if (is_null($this->display_price) || !isset($prices['display_price'])) {
                $this->display_price = $prices['selling_price'];
            }
        }

        // Update display price if provided
        if (isset($prices['display_price'])) {
            $this->display_price = $prices['display_price'];
        }

        // Update discount if provided
        if (isset($prices['discount'])) {
            $this->discount = $prices['discount'];
        }

        // Update profit margin if provided
        if (isset($prices['profit_margin'])) {
            $this->profit_margin = $prices['profit_margin'];
        }

        $this->last_price_updated_by = $employeeId;
        $this->last_price_updated_at = now();
        $this->price_changes_count = ($this->price_changes_count ?? 0) + 1;

        $this->save();

        // Store new prices
        $newPrices = [
            'selling_price' => $this->selling_price,
            'display_price' => $this->display_price,
            'discount' => $this->discount,
            'final_price' => $this->final_price,
        ];

        // Log price change
        $this->priceHistories()->create([
            'employee_id' => $employeeId,
            'old_prices' => $oldPrices,
            'new_prices' => $newPrices,
            'notes' => $notes,
        ]);

        return $this->fresh();
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/default-product.png');
        }

        return Storage::disk('public')->exists($this->image)
            ? asset('storage/' . $this->image)
            : asset('images/default-product.png');
    }

    /**
     * Get product details for API/JSON response
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'display_price' => $this->display_price,
            'display_price_formatted' => $this->display_price_formatted,
            'final_price' => $this->final_price,
            'final_price_formatted' => $this->final_price_formatted,
            'has_discount' => $this->has_discount,
            'discount_percentage' => $this->discount_percentage,
            'qty' => $this->qty,
            'stock_status' => $this->stock_status,
            'image_url' => $this->image_url,
            'category' => $this->category?->name,
            'brand' => $this->brand?->name,
        ];
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Auto-generate code if not provided
            if (empty($product->code)) {
                $product->code = 'PRD-' . strtoupper(uniqid());
            }

            // Set default min_stock_level if not set
            if (is_null($product->min_stock_level)) {
                $product->min_stock_level = 5;
            }
        });

        static::updating(function ($product) {
            // Track if price changed
            if ($product->isDirty('selling_price') || $product->isDirty('display_price') || $product->isDirty('discount')) {
                $product->price_changes_count = ($product->price_changes_count ?? 0) + 1;
                $product->last_price_updated_at = now();
            }
        });
    }
}
