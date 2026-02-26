<?php
// app/Models/EmployeePriceChange.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePriceChange extends Model
{
    use HasFactory;

    protected $table = 'employee_price_changes';

    protected $fillable = [
        'product_id',
        'product_type',
        'employee_id',
        'old_display_price',
        'new_display_price',
        'old_stock_price',
        'new_stock_price',
        'old_discount',
        'new_discount',
        'old_packaging_cost',
        'new_packaging_cost',
        'old_final_price',
        'new_final_price',
        'price_difference',
        'percentage_change',
        'change_reason',
        'rejection_reason',
        'status',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
    ];

    protected $casts = [
        'old_display_price' => 'decimal:2',
        'new_display_price' => 'decimal:2',
        'old_stock_price' => 'decimal:2',
        'new_stock_price' => 'decimal:2',
        'old_discount' => 'decimal:2',
        'new_discount' => 'decimal:2',
        'old_packaging_cost' => 'decimal:2',
        'new_packaging_cost' => 'decimal:2',
        'old_final_price' => 'decimal:2',
        'new_final_price' => 'decimal:2',
        'price_difference' => 'decimal:2',
        'percentage_change' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product that this price change belongs to
     */
    public function product()
    {
        return $this->belongsTo(SellerProduct::class, 'product_id');
    }

    /**
     * Get the employee who requested this price change
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get the employee who approved this price change
     */
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    /**
     * Get the employee who rejected this price change
     */
    public function rejector()
    {
        return $this->belongsTo(Employee::class, 'rejected_by');
    }

    /**
     * Scope for pending price changes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved price changes
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected price changes
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for seller products
     */
    public function scopeForSeller($query)
    {
        return $query->where('product_type', 'seller');
    }

    /**
     * Scope for wholesaler products
     */
    public function scopeForWholesaler($query)
    {
        return $query->where('product_type', 'wholesaler');
    }

    /**
     * Get formatted old price
     */
    public function getFormattedOldPriceAttribute()
    {
        return 'Rs. ' . number_format($this->old_display_price, 2);
    }

    /**
     * Get formatted new price
     */
    public function getFormattedNewPriceAttribute()
    {
        return 'Rs. ' . number_format($this->new_display_price, 2);
    }

    /**
     * Get formatted price difference
     */
    public function getFormattedDifferenceAttribute()
    {
        $sign = $this->price_difference >= 0 ? '+' : '';
        return $sign . 'Rs. ' . number_format(abs($this->price_difference), 2);
    }

    /**
     * Get formatted percentage change
     */
    public function getFormattedPercentageAttribute()
    {
        $sign = $this->percentage_change >= 0 ? '+' : '';
        return $sign . number_format($this->percentage_change, 2) . '%';
    }

    /**
     * Get status badge HTML
     */
    public function getStatusBadgeAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ];

        $color = $colors[$this->status] ?? 'secondary';

        return '<span class="badge bg-' . $color . '">' . ucfirst($this->status) . '</span>';
    }

    /**
     * Check if price change is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Check if price change is approved
     */
    public function isApproved()
    {
        return $this->status === 'approved';
    }

    /**
     * Check if price change is rejected
     */
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
}
