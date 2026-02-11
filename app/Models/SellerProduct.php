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
        'image', // Database column is 'image' after rename migration
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

        if ($qty === 0) {
            return 'out_of_stock';
        } elseif ($qty <= $minQty) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

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
}
