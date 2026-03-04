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

        // price audit
        'last_price_updated_by',
        'last_price_updated_at',
        'price_changes_count',

        // exchange audit (make sure these columns exist in migration)
        'original_wholesaler_id',
        'exchange_count',
        'exchange_history',
        'last_exchanged_at',
        'last_exchanged_by',
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

        'qty' => 'integer',
        'total_sold' => 'integer',
        'min_stock_level' => 'integer',

        'average_rating' => 'float',
        'total_reviews' => 'integer',
        'total_ratings' => 'integer',

        'last_price_updated_at' => 'datetime',
        'price_changes_count' => 'integer',

        // exchange
        'exchange_count' => 'integer',
        'exchange_history' => 'array',
        'last_exchanged_at' => 'datetime',
    ];

    protected $appends = [
        'final_price',
        'display_price_formatted',
        'final_price_formatted',
        'stock_status',
        'stock_status_color',
        'image_url',
    ];

    /* =========================================================
     | Relationships
     ========================================================= */

    public function wholesaler(): BelongsTo
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function originalWholesaler(): BelongsTo
    {
        return $this->belongsTo(Wholesaler::class, 'original_wholesaler_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function lastPriceUpdatedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'last_price_updated_by');
    }

    public function lastExchangedBy(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'last_exchanged_by');
    }

    /** ✅ Price history table (APPROVED changes history) */
    public function priceHistories(): HasMany
    {
        return $this->hasMany(WholesalerProductPriceChange::class, 'product_id');
    }

    public function latestPriceHistory()
    {
        return $this->hasOne(WholesalerProductPriceChange::class, 'product_id')->latest();
    }

    /** ✅ Exchanges */
    public function sourceExchanges(): HasMany
    {
        return $this->hasMany(WholesalerProductExchange::class, 'source_product_id');
    }

    public function destinationExchanges(): HasMany
    {
        return $this->hasMany(WholesalerProductExchange::class, 'destination_product_id');
    }

    /**
     * Get all exchanges (source + destination) as collection
     * NOTE: this is NOT a real Eloquent relation; it's a helper method.
     */
    public function allExchanges()
    {
        return $this->sourceExchanges()
            ->get()
            ->merge($this->destinationExchanges()->get())
            ->sortByDesc('created_at')
            ->values();
    }

    /* =========================================================
     | Scopes
     ========================================================= */

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

    public function scopeExchanged($query)
    {
        return $query->where('exchange_count', '>', 0);
    }

    public function scopeNeverExchanged($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('exchange_count')->orWhere('exchange_count', 0);
        });
    }

    public function scopeExchangedByEmployee($query, $employeeId)
    {
        return $query->where('last_exchanged_by', $employeeId);
    }

    /* =========================================================
     | Accessors (ONE VERSION ONLY)
     ========================================================= */

    /** ✅ Display price (fallback to selling_price) */
    public function getDisplayPriceAttribute($value): float
    {
        if (!is_null($value)) {
            return (float) $value;
        }

        if (!is_null($this->attributes['selling_price'] ?? null)) {
            return (float) $this->attributes['selling_price'];
        }

        return 0.00;
    }

    public function getDisplayPriceFormattedAttribute(): string
    {
        return 'Rs. ' . number_format($this->display_price, 2);
    }

    /** ✅ Final price after discount */
    public function getFinalPriceAttribute(): float
    {
        $price = (float) ($this->display_price ?? 0);

        $discount = (float) ($this->discount ?? 0);
        if ($discount > 0) {
            $price = $price - ($price * ($discount / 100));
        }

        return round($price, 2);
    }

    public function getFinalPriceFormattedAttribute(): string
    {
        return 'Rs. ' . number_format($this->final_price, 2);
    }

    public function getOriginalPriceAttribute(): float
    {
        return (float) ($this->attributes['selling_price'] ?? 0);
    }

    public function getTotalCostAttribute(): float
    {
        $cost = (float) ($this->cost_price ?? 0);
        $packaging = (float) ($this->packaging_cost ?? 0);
        return round($cost + $packaging, 2);
    }

    public function getProfitMarginPercentageAttribute(): float
    {
        $totalCost = $this->total_cost;
        if ($totalCost > 0) {
            return round((($this->final_price - $totalCost) / $totalCost) * 100, 2);
        }
        return 0;
    }

    public function getProfitAmountAttribute(): float
    {
        return round($this->final_price - $this->total_cost, 2);
    }

    public function getHasDiscountAttribute(): bool
    {
        return !is_null($this->discount) && $this->discount > 0;
    }

    public function getDiscountPercentageAttribute(): string
    {
        return $this->has_discount ? ($this->discount . '% OFF') : '';
    }

    public function getStockStatusAttribute(): string
    {
        if ((int)$this->qty === 0) return 'Out of Stock';
        if ((int)$this->qty <= (int)$this->min_stock_level) return 'Low Stock';
        return 'In Stock';
    }

    public function getStockStatusColorAttribute(): string
    {
        if ((int)$this->qty === 0) return 'danger';
        if ((int)$this->qty <= (int)$this->min_stock_level) return 'warning';
        return 'success';
    }

    public function getImageUrlAttribute(): string
    {
        if (!$this->image) {
            return asset('images/default-product.png');
        }

        return Storage::disk('public')->exists($this->image)
            ? asset('storage/' . $this->image)
            : asset('images/default-product.png');
    }

    /* =========================================================
     | Business Methods
     ========================================================= */

    /**
     * ✅ ONLY use this when a price change is APPROVED (wholesaler approved).
     * This method updates product + saves history in WholesalerProductPriceChange.
     */
    public function applyApprovedPriceChange(array $prices, int $employeeId, ?string $notes = null): self
    {
        $oldPrices = [
            'selling_price' => (float)($this->selling_price ?? 0),
            'display_price' => (float)($this->display_price ?? 0),
            'discount' => (float)($this->discount ?? 0),
            'final_price' => (float)($this->final_price ?? 0),
        ];

        if (array_key_exists('selling_price', $prices)) {
            $this->selling_price = $prices['selling_price'];
            if (!array_key_exists('display_price', $prices)) {
                $this->display_price = $prices['selling_price'];
            }
        }

        if (array_key_exists('display_price', $prices)) {
            $this->display_price = $prices['display_price'];
        }

        if (array_key_exists('discount', $prices)) {
            $this->discount = $prices['discount'];
        }

        if (array_key_exists('profit_margin', $prices)) {
            $this->profit_margin = $prices['profit_margin'];
        }

        $this->last_price_updated_by = $employeeId;
        $this->last_price_updated_at = now();
        $this->price_changes_count = ($this->price_changes_count ?? 0) + 1;

        $this->save();

        $newPrices = [
            'selling_price' => (float)($this->selling_price ?? 0),
            'display_price' => (float)($this->display_price ?? 0),
            'discount' => (float)($this->discount ?? 0),
            'final_price' => (float)($this->final_price ?? 0),
        ];

        $this->priceHistories()->create([
            'employee_id' => $employeeId,
            'old_prices' => $oldPrices,
            'new_prices' => $newPrices,
            'notes' => $notes,
        ]);

        return $this->fresh();
    }

    /**
     * ✅ Update exchange statistics (call when exchange created)
     */
    public function updateExchangeStats(WholesalerProductExchange $exchange): void
    {
        $this->exchange_count = ($this->exchange_count ?? 0) + 1;
        $this->last_exchanged_at = now();
        $this->last_exchanged_by = $exchange->employee_id;

        $history = $this->exchange_history ?? [];
        $history[] = [
            'exchange_id' => $exchange->id,
            'date' => now()->toDateTimeString(),
            'type' => $exchange->exchange_type,
            'from_wholesaler' => $exchange->source_wholesaler_id,
            'to_wholesaler' => $exchange->destination_wholesaler_id,
            'employee_id' => $exchange->employee_id,
        ];

        if (count($history) > 10) {
            $history = array_slice($history, -10);
        }

        $this->exchange_history = $history;
        $this->save();
    }

    public function hasBeenExchanged(): bool
    {
        return ($this->exchange_count ?? 0) > 0;
    }

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

    /* =========================================================
     | Boot
     ========================================================= */

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->code)) {
                $product->code = 'PRD-' . strtoupper(uniqid());
            }

            if (is_null($product->min_stock_level)) {
                $product->min_stock_level = 5;
            }
        });

        static::updating(function ($product) {
            if (
                $product->isDirty('selling_price') ||
                $product->isDirty('display_price') ||
                $product->isDirty('discount')
            ) {
                $product->price_changes_count = ($product->price_changes_count ?? 0) + 1;
                $product->last_price_updated_at = now();
            }
        });
    }
}
