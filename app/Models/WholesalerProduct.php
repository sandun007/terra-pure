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
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'display_price' => 'decimal:2',
        'previous_selling_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'packaging_cost' => 'decimal:2',
        'features' => 'array',
        'last_price_updated_at' => 'datetime',
    ];

    protected $appends = ['display_price', 'final_price'];

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
    public function priceChanges()
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
    public function lastPriceUpdatedBy()
    {
        return $this->belongsTo(Employee::class, 'last_price_updated_by');
    }

    public function priceHistories(): HasMany
    {
        return $this->hasMany(WholesalerProductPriceChange::class, 'product_id');
    }

    public function reviews()
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

    /**
     * Accessors - Price Calculations
     */

    // Get display price (what wholesaler sees) - always from display_price column
    public function getDisplayPriceAttribute(): float
    {
        return $this->display_price ?? $this->selling_price;
    }

    // Final price after discount
    public function getFinalPriceAttribute(): float
    {
        $price = $this->display_price ?? $this->selling_price;
        if ($this->discount > 0) {
            $price = $price - ($price * ($this->discount / 100));
        }
        return round($price, 2);
    }

    // Total cost including packaging
    public function getTotalCostAttribute(): float
    {
        return round($this->cost_price + $this->packaging_cost, 2);
    }

    // Profit margin percentage
    public function getProfitMarginPercentageAttribute(): float
    {
        if ($this->total_cost > 0) {
            return round((($this->final_price - $this->total_cost) / $this->total_cost) * 100, 2);
        }
        return 0;
    }

    // Profit amount
    public function getProfitAmountAttribute(): float
    {
        return round($this->final_price - $this->total_cost, 2);
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

    /**
     * Update prices with automatic display price calculation
     */
    public function updatePricesWithMargin(array $prices, $employeeId, $notes = null)
    {
        // Store old prices
        $oldPrices = [
            'selling_price' => $this->selling_price,
            'display_price' => $this->display_price ?? $this->selling_price,
            'discount' => $this->discount,
            'final_price' => $this->final_price,
        ];

        // Update selling price
        if (isset($prices['selling_price'])) {
            $this->selling_price = $prices['selling_price'];
            $this->display_price = $prices['selling_price']; // Also update display price
        }

        // Update discount if provided
        if (isset($prices['discount'])) {
            $this->discount = $prices['discount'];
        }

        // Update profit margin if provided
        if (isset($prices['profit_margin'])) {
            $this->profit_margin = $prices['profit_margin'];
        }

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
