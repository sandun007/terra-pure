<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_code',
        'category_id',
        'brand_id',
        'code',
        'name',
        'slug',
        'sku',
        'description',
        'stock_price',
        'display_price',
        'previous_display_price',
        'discount',
        'profit_margin',
        'packaging_cost',
        'qty',
        'min_qty',
        'total_sold',
        'features',
        'barcode',
        'img',
        'type',
        'add_by',
        'update_by',
        'status',
        'cancel',
        'average_rating',
        'total_reviews',
        'total_ratings',
        'last_price_updated_by',
        'last_price_updated_at',
        'price_changes_count'
    ];

    protected $casts = [
        'stock_price' => 'decimal:2',
        'display_price' => 'decimal:2',
        'previous_display_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'profit_margin' => 'decimal:2',
        'packaging_cost' => 'decimal:2',
        'last_price_updated_at' => 'datetime',
        'qty' => 'integer',
        'min_qty' => 'integer',
        'total_sold' => 'integer',
        'cancel' => 'integer',
    ];

    protected $appends = [
        'final_price',
        'total_cost',
        'profit_margin_percentage',
        'profit_amount',
        'image_url',
        'stock_status'
    ];

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // Generate slug automatically
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = $product->generateSlug();
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = $product->generateSlug();
            }
        });
    }

    /**
     * Relationships
     */

    // Seller relationship
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_code', 'code');
    }

    // Category relationship (using category_id)
    public function categoryModel(): BelongsTo
    {
        return $this->belongsTo(SellerCategory::class, 'category_id', 'id');
    }

    // Brand relationship (using brand_id)
    public function brandModel(): BelongsTo
    {
        return $this->belongsTo(SellerBrand::class, 'brand_id', 'id');
    }

    // Alias for category
    public function category(): BelongsTo
    {
        return $this->categoryModel();
    }

    // Alias for brand
    public function brand(): BelongsTo
    {
        return $this->brandModel();
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('cancel', 0);
    }

    public function scopeForSeller($query, $sellerCode)
    {
        return $query->where('seller_code', $sellerCode);
    }

    public function scopeStockItem($query)
    {
        return $query->where('type', 'stock_item');
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('qty <= min_qty')->where('qty', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('qty', 0);
    }

    public function scopeInStock($query)
    {
        return $query->where('qty', '>', 0);
    }

    /**
     * Accessors - Price Calculations
     */

    // Final price after discount
    public function getFinalPriceAttribute(): float
    {
        $price = $this->display_price ?? 0;
        $discount = $this->discount ?? 0;

        if ($discount > 0) {
            $price = $price - ($price * ($discount / 100));
        }
        return round($price, 2);
    }

    // Total cost including packaging
    public function getTotalCostAttribute(): float
    {
        $stockPrice = $this->stock_price ?? 0;
        $packagingCost = $this->packaging_cost ?? 0;
        return round($stockPrice + $packagingCost, 2);
    }

    // Profit margin percentage
    public function getProfitMarginPercentageAttribute(): float
    {
        $totalCost = $this->total_cost;

        if ($totalCost > 0) {
            $profit = $this->final_price - $totalCost;
            if ($profit > 0) {
                return round(($profit / $totalCost) * 100, 2);
            }
        }
        return 0;
    }

    // Profit amount
    public function getProfitAmountAttribute(): float
    {
        return round($this->final_price - $this->total_cost, 2);
    }

    // Gross profit (before expenses)
    public function getGrossProfitAttribute(): float
    {
        return round($this->final_price - ($this->stock_price ?? 0), 2);
    }

    // Markup percentage
    public function getMarkupPercentageAttribute(): float
    {
        $stockPrice = $this->stock_price ?? 0;
        $displayPrice = $this->display_price ?? 0;

        if ($stockPrice > 0) {
            $markup = $displayPrice - $stockPrice;
            return round(($markup / $stockPrice) * 100, 2);
        }
        return 0;
    }

    /**
     * Stock Management Accessors
     */
    public function getStockStatusAttribute(): string
    {
        $qty = $this->qty ?? 0;
        $minQty = $this->min_qty ?? 0;

        if ($qty === 0) {
            return 'out_of_stock';
        } elseif ($qty <= $minQty) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getIsLowStockAttribute(): bool
    {
        return ($this->qty ?? 0) <= ($this->min_qty ?? 0) && ($this->qty ?? 0) > 0;
    }

    public function getIsOutOfStockAttribute(): bool
    {
        return ($this->qty ?? 0) === 0;
    }

    public function getIsInStockAttribute(): bool
    {
        return ($this->qty ?? 0) > 0;
    }

    public function getStockPercentageAttribute(): float
    {
        $qty = $this->qty ?? 0;
        $minQty = $this->min_qty ?? 0;
        $safeLevel = $minQty * 2; // Safe level is double the minimum

        if ($safeLevel > 0) {
            return min(100, round(($qty / $safeLevel) * 100, 2));
        }
        return 0;
    }

    /**
     * Image URL Accessor
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->img) {
            return asset('images/default-product.png');
        }

        // Check if it's a full URL
        if (filter_var($this->img, FILTER_VALIDATE_URL)) {
            return $this->img;
        }

        // Check if image exists in storage
        if (Storage::disk('public')->exists($this->img)) {
            return asset('storage/' . $this->img);
        }

        return asset('images/default-product.png');
    }

    /**
     * Methods
     */

    /**
     * Generate slug from product name
     */
    private function generateSlug(): string
    {
        $slug = Str::slug($this->name);
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? null)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    /**
     * Update product quantity
     */
    public function updateQuantity(int $quantity, string $action = 'add'): bool
    {
        switch ($action) {
            case 'add':
                $this->qty += $quantity;
                break;
            case 'subtract':
                $this->qty -= $quantity;
                if ($this->qty < 0) $this->qty = 0;
                break;
            case 'set':
                $this->qty = $quantity;
                break;
        }

        return $this->save();
    }

    /**
     * Record sale
     */
    public function recordSale(int $quantity): bool
    {
        if ($quantity > $this->qty) {
            return false;
        }

        $this->qty -= $quantity;
        $this->total_sold += $quantity;

        return $this->save();
    }

    /**
     * Check if product has discount
     */
    public function hasDiscount(): bool
    {
        return ($this->discount ?? 0) > 0;
    }

    /**
     * Get discount amount
     */
    public function getDiscountAmount(): float
    {
        $displayPrice = $this->display_price ?? 0;
        $discount = $this->discount ?? 0;
        return round($displayPrice * ($discount / 100), 2);
    }

    /**
     * Get total value of current stock
     */
    public function getStockValue(): float
    {
        $qty = $this->qty ?? 0;
        $stockPrice = $this->stock_price ?? 0;
        return round($qty * $stockPrice, 2);
    }

    /**
     * Get total potential sales value
     */
    public function getPotentialSalesValue(): float
    {
        $qty = $this->qty ?? 0;
        return round($qty * $this->final_price, 2);
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(): bool
    {
        $this->status = $this->status === 'active' ? 'inactive' : 'active';
        return $this->save();
    }
}
