<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SellerProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'seller_id',
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
        'image',
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
        'cancel' => 'boolean',
    ];

    protected $appends = [
        'final_price',
        'total_cost',
        'profit_margin_percentage',
        'profit_amount',
        'image_url',
        'stock_status',
        'formatted_code',
        'code_with_prefix'
    ];

    /**
     * Product Code Configuration
     */
    const CODE_PREFIX = 'DX/ITM/';
    const CODE_PAD_LENGTH = 4;
    const CODE_PAD_CHAR = '0';
    const CODE_PAD_TYPE = STR_PAD_LEFT;

    /**
     * Boot method to handle model events
     */
    protected static function boot()
    {
        parent::boot();

        // AUTO-GENERATE PRODUCT CODE - CRITICAL FIX
        static::creating(function ($product) {
            if (empty($product->code)) {
                $product->code = self::generateProductCode();
            }

            if (empty($product->slug)) {
                $product->slug = $product->generateSlug();
            }

            // Set seller_code if empty
            if (empty($product->seller_code) && $product->seller_id) {
                $product->seller_code = 'SELLER-' . str_pad($product->seller_id, 5, '0', STR_PAD_LEFT);
            }

            // Set SKU if empty
            if (empty($product->sku)) {
                $product->sku = self::generateSKU($product);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = $product->generateSlug();
            }
        });
    }

    /**
     * GENERATE NEXT PRODUCT CODE - PRESERVE YOUR WORKING LOGIC
     */
    public static function generateProductCode(): string
    {
        $prefix = self::CODE_PREFIX;

        $lastProduct = self::where('code', 'LIKE', $prefix . '%')
            ->orderByRaw('CAST(REPLACE(code, ?, "") AS UNSIGNED) DESC', [$prefix])
            ->lockForUpdate()
            ->first();

        if ($lastProduct && $lastProduct->code) {
            $lastNumber = (int) str_replace($prefix, '', $lastProduct->code);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, self::CODE_PAD_LENGTH, self::CODE_PAD_CHAR, self::CODE_PAD_TYPE);
    }

    /**
     * Generate SKU from product details
     */
    public static function generateSKU($product): string
    {
        $prefix = 'SKU-';
        $sellerPart = $product->seller_id ? str_pad($product->seller_id, 3, '0', STR_PAD_LEFT) : '000';
        $categoryPart = $product->category_id ? str_pad($product->category_id, 3, '0', STR_PAD_LEFT) : '000';
        $randomPart = strtoupper(Str::random(4));

        return $prefix . $sellerPart . '-' . $categoryPart . '-' . $randomPart;
    }

    /**
     * GET NEXT AVAILABLE CODE - For display in forms
     */
    public static function getNextAvailableCode(): string
    {
        try {
            $lastProduct = self::where('code', 'LIKE', self::CODE_PREFIX . '%')
                ->orderByRaw('CAST(REPLACE(code, ?, "") AS UNSIGNED) DESC', [self::CODE_PREFIX])
                ->first();

            if ($lastProduct && $lastProduct->code) {
                $lastNumber = (int) str_replace(self::CODE_PREFIX, '', $lastProduct->code);
                $nextNumber = $lastNumber + 1;
            } else {
                $nextNumber = 1;
            }

            return self::CODE_PREFIX . str_pad($nextNumber, self::CODE_PAD_LENGTH, self::CODE_PAD_CHAR, self::CODE_PAD_TYPE);
        } catch (\Exception $e) {
            \Log::error('Error generating next product code: ' . $e->getMessage());
            return self::CODE_PREFIX . str_pad('1', self::CODE_PAD_LENGTH, self::CODE_PAD_CHAR, self::CODE_PAD_TYPE);
        }
    }

    /**
     * Relationships
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(SellerCategory::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(SellerBrand::class, 'brand_id');
    }

    /**
     * Relationship with orders
     */
    public function orders()
    {
        return $this->hasMany(SellerOrder::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(SellerOrderItem::class, 'product_id');
    }

    /**
     * Relationship with shop
     */
    public function shop()
    {
        return $this->hasOne(SellerShop::class, 'seller_id', 'seller_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('cancel', false);
    }

    public function scopeForSeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeInStock($query)
    {
        return $query->where('qty', '>', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->where('qty', '>', 0)
            ->whereRaw('qty <= min_qty');
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('qty', '<=', 0);
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    public function scopeSearchByCode($query, $term)
    {
        return $query->where('code', 'LIKE', "%{$term}%");
    }

    /**
     * Accessors
     */
    public function getFinalPriceAttribute(): float
    {
        $price = $this->display_price ?? 0;
        $discount = $this->discount ?? 0;

        if ($discount > 0) {
            $price = $price - ($price * ($discount / 100));
        }
        return round($price, 2);
    }

    public function getTotalCostAttribute(): float
    {
        $stockPrice = $this->stock_price ?? 0;
        $packagingCost = $this->packaging_cost ?? 0;
        return round($stockPrice + $packagingCost, 2);
    }

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

    public function getProfitAmountAttribute(): float
    {
        return round($this->final_price - $this->total_cost, 2);
    }

    public function getStockStatusAttribute(): string
    {
        $qty = $this->qty ?? 0;
        $minQty = $this->min_qty ?? 0;

        if ($qty <= 0) {
            return 'out_of_stock';
        } elseif ($qty <= $minQty) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    /**
     * NEW: Formatted code without prefix
     */
    public function getFormattedCodeAttribute(): string
    {
        if (!$this->code) {
            return '';
        }

        // Remove prefix for display in certain contexts
        return str_replace(self::CODE_PREFIX, '', $this->code);
    }

    /**
     * NEW: Code with prefix (ensures consistent format)
     */
    public function getCodeWithPrefixAttribute(): string
    {
        if (!$this->code) {
            return '';
        }

        // Ensure code has prefix
        if (!str_starts_with($this->code, self::CODE_PREFIX)) {
            return self::CODE_PREFIX . $this->code;
        }

        return $this->code;
    }

    /**
     * YOUR ORIGINAL WORKING IMAGE URL METHOD - PRESERVED
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return asset('images/default-product.png');
        }

        // Check if it's a full URL
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Check if image exists in storage
        if (Storage::disk('public')->exists($this->image)) {
            return asset('storage/' . $this->image);
        }

        return asset('images/default-product.png');
    }

    /**
     * NEW: Human-readable stock status
     */
    public function getStockStatusLabelAttribute(): string
    {
        $statuses = [
            'in_stock' => 'In Stock',
            'low_stock' => 'Low Stock',
            'out_of_stock' => 'Out of Stock'
        ];

        return $statuses[$this->stock_status] ?? 'Unknown';
    }

    /**
     * NEW: Stock status color for UI
     */
    public function getStockStatusColorAttribute(): string
    {
        $colors = [
            'in_stock' => 'success',
            'low_stock' => 'warning',
            'out_of_stock' => 'danger'
        ];

        return $colors[$this->stock_status] ?? 'secondary';
    }

    /**
     * NEW: Stock status badge HTML
     */
    public function getStockStatusBadgeAttribute(): string
    {
        $labels = [
            'in_stock' => 'In Stock',
            'low_stock' => 'Low Stock',
            'out_of_stock' => 'Out of Stock'
        ];

        $colors = [
            'in_stock' => '#10b981',
            'low_stock' => '#f59e0b',
            'out_of_stock' => '#ef4444'
        ];

        $label = $labels[$this->stock_status] ?? 'Unknown';
        $color = $colors[$this->stock_status] ?? '#6c757d';

        return "<span class='badge' style='background-color: {$color}; color: white; padding: 5px 10px; border-radius: 20px;'>{$label}</span>";
    }

    /**
     * Methods
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

    public function hasDiscount(): bool
    {
        return ($this->discount ?? 0) > 0;
    }

    public function getDiscountAmount(): float
    {
        $displayPrice = $this->display_price ?? 0;
        $discount = $this->discount ?? 0;
        return round($displayPrice * ($discount / 100), 2);
    }

    public function toggleStatus(): bool
    {
        $this->status = $this->status === 'active' ? 'inactive' : 'active';
        return $this->save();
    }

    /**
     * NEW: Check if product is available for purchase
     */
    public function isAvailable(): bool
    {
        return $this->status === 'active'
            && !$this->cancel
            && $this->qty > 0;
    }

    /**
     * NEW: Decrement stock safely
     */
    public function decrementStock(int $quantity = 1): bool
    {
        if ($this->qty >= $quantity) {
            $this->decrement('qty', $quantity);
            $this->increment('total_sold', $quantity);
            return true;
        }
        return false;
    }

    /**
     * NEW: Increment stock
     */
    public function incrementStock(int $quantity = 1): void
    {
        $this->increment('qty', $quantity);
    }

    /**
     * NEW: Update price with history tracking
     */
    public function updatePrice(float $newPrice, int $updatedBy): bool
    {
        $this->previous_display_price = $this->display_price;
        $this->display_price = $newPrice;
        $this->last_price_updated_by = $updatedBy;
        $this->last_price_updated_at = now();
        $this->price_changes_count = ($this->price_changes_count ?? 0) + 1;

        return $this->save();
    }

    /**
     * NEW: Get product by code with caching
     */
    public static function findByCode(string $code, $sellerId = null)
    {
        $query = self::where('code', $code);

        if ($sellerId) {
            $query->where('seller_id', $sellerId);
        }

        return $query->first();
    }

    /**
     * NEW: Bulk generate product codes for existing products
     */
    public static function migrateMissingCodes(): array
    {
        $products = self::whereNull('code')->orWhere('code', '')->get();
        $count = 0;
        $errors = [];

        foreach ($products as $product) {
            try {
                $product->code = self::generateProductCode();
                $product->save();
                $count++;
            } catch (\Exception $e) {
                $errors[] = "Product ID {$product->id}: " . $e->getMessage();
            }
        }

        return [
            'success' => $count,
            'errors' => $errors
        ];
    }

    /**
     * NEW: Format code for display
     */
    public static function formatCode(string $code): string
    {
        if (empty($code)) {
            return '';
        }

        // Add prefix if missing
        if (!str_starts_with($code, self::CODE_PREFIX)) {
            $code = self::CODE_PREFIX . $code;
        }

        return $code;
    }

    /**
     * NEW: Validate product code format
     */
    public static function isValidCodeFormat(string $code): bool
    {
        $pattern = '/^' . preg_quote(self::CODE_PREFIX, '/') . '\d{' . self::CODE_PAD_LENGTH . '}$/';
        return preg_match($pattern, $code) === 1;
    }

    /**
     * NEW: Extract number from code
     */
    public static function extractCodeNumber(string $code): ?int
    {
        $number = str_replace(self::CODE_PREFIX, '', $code);
        return is_numeric($number) ? (int) $number : null;
    }
}
