<?php
// app/Models/WholesalerProduct.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WholesalerProduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wholesaler_products';

    protected $fillable = [
        'wholesaler_id',
        'code',
        'name',
        'description',
        'cost_price',
        'selling_price',
        'display_price',
        'discount',
        'packaging_cost',
        // 'final_price', // REMOVED - this is a generated column
        'qty',
        'min_stock_level',
        'category_id',
        'brand_id',
        'features',
        'barcode',
        'image',
        'status',
        'employee_approval_status',
        'approved_by_employee',
        'approved_at',
        'rejection_reason',
        'display_in_seller_shop',
        'last_price_updated_by',
        'last_price_updated_at',
        'price_changes_count',
        'exchange_count',
        'last_exchanged_at',
        'last_exchanged_by',
        'original_wholesaler_id'
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'display_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'packaging_cost' => 'decimal:2',
        // 'final_price' => 'decimal:2', // REMOVED - this is a generated column
        'qty' => 'integer',
        'min_stock_level' => 'integer',
        'display_in_seller_shop' => 'boolean',
        'approved_at' => 'datetime',
        'last_price_updated_at' => 'datetime',
        'last_exchanged_at' => 'datetime',
        'price_changes_count' => 'integer',
        'exchange_count' => 'integer',
    ];

    protected $attributes = [
        'employee_approval_status' => 'pending',
        'display_in_seller_shop' => false,
        'price_changes_count' => 0,
        'exchange_count' => 0,
        'discount' => 0,
        'packaging_cost' => 0,
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        // Add any sensitive fields here
    ];

    /**
     * The attributes that should be appended to the model's array form.
     */
    protected $appends = [
        'final_price', // Make final_price an accessor instead of a database column
        'formatted_final_price',
        'profit_margin',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // No need to set final_price - it's generated
        });

        static::updating(function ($product) {
            // No need to set final_price - it's generated
        });
    }

    /**
     * Get the final price attribute (accessor, not database column)
     */
    public function getFinalPriceAttribute()
    {
        $displayPrice = $this->display_price ?? $this->selling_price ?? 0;
        $discount = $this->discount ?? 0;

        if ($discount > 0) {
            return round($displayPrice * (1 - $discount / 100), 2);
        }

        return round($displayPrice, 2);
    }

    /**
     * Get formatted final price
     */
    public function getFormattedFinalPriceAttribute()
    {
        return 'Rs. ' . number_format($this->final_price, 2);
    }

    /**
     * Get the profit margin percentage
     */
    public function getProfitMarginAttribute()
    {
        if ($this->cost_price > 0 && $this->final_price > 0) {
            return round((($this->final_price - $this->cost_price) / $this->final_price) * 100, 2);
        }
        return 0;
    }

    // ─── Relationships ─────────────────────────────────────────────────────

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by_employee');
    }

    public function lastPriceUpdatedBy()
    {
        return $this->belongsTo(Employee::class, 'last_price_updated_by');
    }

    public function lastExchangedBy()
    {
        return $this->belongsTo(Employee::class, 'last_exchanged_by');
    }

    public function originalWholesaler()
    {
        return $this->belongsTo(Wholesaler::class, 'original_wholesaler_id');
    }

    public function priceChanges()
    {
        return $this->hasMany(WholesalerProductPriceChange::class, 'product_id');
    }

    public function sourceExchanges()
    {
        return $this->hasMany(WholesalerProductExchange::class, 'source_product_id');
    }

    public function destinationExchanges()
    {
        return $this->hasMany(WholesalerProductExchange::class, 'destination_product_id');
    }

    // ─── Scopes ────────────────────────────────────────────────────────────

    public function scopePendingApproval($query)
    {
        return $query->where('employee_approval_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('employee_approval_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('employee_approval_status', 'rejected');
    }

    public function scopeAvailableInSellerShop($query)
    {
        return $query->where('employee_approval_status', 'approved')
            ->where('display_in_seller_shop', true)
            ->where('status', 'active');
    }

    public function scopeForWholesaler($query, $wholesalerId)
    {
        return $query->where('wholesaler_id', $wholesalerId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeInStock($query)
    {
        return $query->where('qty', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('qty', '<=', 0);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('qty > 0 AND qty <= min_stock_level');
    }

    // ─── Accessors ─────────────────────────────────────────────────────────

    public function getFormattedSellingPriceAttribute()
    {
        return 'Rs. ' . number_format($this->selling_price, 2);
    }

    public function getFormattedDisplayPriceAttribute()
    {
        return 'Rs. ' . number_format($this->display_price, 2);
    }

    public function getFormattedCostPriceAttribute()
    {
        return 'Rs. ' . number_format($this->cost_price, 2);
    }

    public function getFormattedDiscountAttribute()
    {
        return $this->discount > 0 ? $this->discount . '%' : 'No Discount';
    }

    public function getApprovalBadgeAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger'
        ];

        $color = $colors[$this->employee_approval_status] ?? 'secondary';
        $text = ucfirst($this->employee_approval_status);

        return '<span class="badge bg-' . $color . '">' . $text . '</span>';
    }

    public function getStatusBadgeAttribute()
    {
        $color = $this->status === 'active' ? 'success' : 'secondary';
        $text = ucfirst($this->status);

        return '<span class="badge bg-' . $color . '">' . $text . '</span>';
    }

    public function getStockStatusAttribute()
    {
        if ($this->qty <= 0) {
            return [
                'status' => 'out_of_stock',
                'badge' => 'danger',
                'text' => 'Out of Stock',
                'class' => 'text-danger'
            ];
        } elseif ($this->qty <= $this->min_stock_level) {
            return [
                'status' => 'low_stock',
                'badge' => 'warning',
                'text' => 'Low Stock',
                'class' => 'text-warning'
            ];
        }
        return [
            'status' => 'in_stock',
            'badge' => 'success',
            'text' => 'In Stock',
            'class' => 'text-success'
        ];
    }

    public function getStockStatusBadgeAttribute()
    {
        $status = $this->stock_status;
        return '<span class="badge bg-' . $status['badge'] . '">' . $status['text'] . '</span>';
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/no-image.png');
    }

    public function getShopDisplayStatusAttribute()
    {
        return $this->display_in_seller_shop ? 'Visible in Shop' : 'Hidden from Shop';
    }

    public function getShopDisplayBadgeAttribute()
    {
        if ($this->display_in_seller_shop) {
            return '<span class="badge bg-success">Visible in Shop</span>';
        }
        return '<span class="badge bg-secondary">Hidden from Shop</span>';
    }

    // ─── Methods ───────────────────────────────────────────────────────────

    public function approve(Employee $employee, $notes = null)
    {
        $this->employee_approval_status = 'approved';
        $this->approved_by_employee = $employee->id;
        $this->approved_at = now();
        $this->display_in_seller_shop = true;
        $this->rejection_reason = null;
        $this->save();

        return $this;
    }

    public function reject(Employee $employee, $reason)
    {
        $this->employee_approval_status = 'rejected';
        $this->approved_by_employee = $employee->id;
        $this->approved_at = now();
        $this->display_in_seller_shop = false;
        $this->rejection_reason = $reason;
        $this->save();

        return $this;
    }

    public function toggleShopDisplay($status = null)
    {
        if ($status !== null) {
            $this->display_in_seller_shop = $status;
        } else {
            $this->display_in_seller_shop = !$this->display_in_seller_shop;
        }
        $this->save();

        return $this;
    }

    public function isApproved(): bool
    {
        return $this->employee_approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->employee_approval_status === 'pending';
    }

    public function isRejected(): bool
    {
        return $this->employee_approval_status === 'rejected';
    }

    public function isAvailableInShop(): bool
    {
        return $this->isApproved() &&
            $this->display_in_seller_shop &&
            $this->status === 'active' &&
            $this->qty > 0;
    }

    public function isInStock(): bool
    {
        return $this->qty > 0;
    }

    public function isLowStock(): bool
    {
        return $this->qty > 0 && $this->qty <= $this->min_stock_level;
    }

    public function isOutOfStock(): bool
    {
        return $this->qty <= 0;
    }

    public function updateExchangeStats($exchange)
    {
        $this->increment('exchange_count');
        $this->last_exchanged_at = now();

        if ($exchange instanceof WholesalerProductExchange) {
            if ($exchange->source_product_id == $this->id) {
                $this->last_exchanged_by = $exchange->employee_id;
                $this->original_wholesaler_id = $exchange->destination_wholesaler_id;
            } elseif ($exchange->destination_product_id == $this->id) {
                $this->last_exchanged_by = $exchange->employee_id;
                $this->original_wholesaler_id = $exchange->source_wholesaler_id;
            }
        }

        $this->save();

        return $this;
    }

    public function decreaseStock($quantity = 1)
    {
        if ($this->qty >= $quantity) {
            $this->decrement('qty', $quantity);
            return true;
        }
        return false;
    }

    public function increaseStock($quantity = 1)
    {
        $this->increment('qty', $quantity);
        return $this;
    }

    public function updatePrice($newPrice, Employee $employee = null, $type = 'selling_price')
    {
        $oldPrice = $this->$type;

        $this->$type = $newPrice;

        if ($employee) {
            $this->last_price_updated_by = $employee->id;
            $this->last_price_updated_at = now();
        }

        $this->increment('price_changes_count');
        $this->save();

        return [
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
            'difference' => $newPrice - $oldPrice
        ];
    }

    // ─── Static Methods ────────────────────────────────────────────────────

    public static function generateProductCode($wholesalerId = null)
    {
        $prefix = 'PRD/';

        if ($wholesalerId) {
            $prefix = 'WS-' . $wholesalerId . '/';
        }

        $lastProduct = self::where('code', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastProduct) {
            $lastNumber = (int) substr($lastProduct->code, strrpos($lastProduct->code, '/') + 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    public static function getPendingApprovalCount()
    {
        return self::where('employee_approval_status', 'pending')->count();
    }

    public static function getApprovedTodayCount()
    {
        return self::where('employee_approval_status', 'approved')
            ->whereDate('approved_at', today())
            ->count();
    }

    public static function getLowStockCount()
    {
        return self::where('status', 'active')
            ->whereRaw('qty > 0 AND qty <= min_stock_level')
            ->count();
    }

    public static function getOutOfStockCount()
    {
        return self::where('status', 'active')
            ->where('qty', '<=', 0)
            ->count();
    }
}
