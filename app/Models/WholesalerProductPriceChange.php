<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalerProductPriceChange extends Model
{
    use HasFactory;

    protected $table = 'wholesaler_product_price_changes';

    protected $fillable = [
        'product_id',
        'wholesaler_id',
        'employee_id',
        'old_cost_price',
        'old_selling_price',
        'old_display_price',
        'old_discount',
        'old_final_price',
        'new_cost_price',
        'new_selling_price',
        'new_display_price',
        'new_discount',
        'new_final_price',
        'price_difference',
        'percentage_change',
        'change_type',
        'change_reason',
        'notes',
        'change_data',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'old_cost_price' => 'decimal:2',
        'old_selling_price' => 'decimal:2',
        'old_display_price' => 'decimal:2',
        'old_discount' => 'decimal:2',
        'old_final_price' => 'decimal:2',
        'new_cost_price' => 'decimal:2',
        'new_selling_price' => 'decimal:2',
        'new_display_price' => 'decimal:2',
        'new_discount' => 'decimal:2',
        'new_final_price' => 'decimal:2',
        'price_difference' => 'decimal:2',
        'percentage_change' => 'decimal:2',
        'change_data' => 'array',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(WholesalerProduct::class);
    }

    /**
     * Get the wholesaler
     */
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    /**
     * Get the employee who made the change
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the employee who approved the change
     */
    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    /**
     * Scope for pending changes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved changes
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for changes by employee
     */
    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope for changes by wholesaler
     */
    public function scopeByWholesaler($query, $wholesalerId)
    {
        return $query->where('wholesaler_id', $wholesalerId);
    }

    /**
     * Scope for changes by product
     */
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Get price change direction (increase/decrease)
     */
    public function getDirectionAttribute(): string
    {
        if ($this->price_difference > 0) {
            return 'increase';
        } elseif ($this->price_difference < 0) {
            return 'decrease';
        }
        return 'unchanged';
    }

    /**
     * Check if price increased
     */
    public function isIncrease(): bool
    {
        return $this->price_difference > 0;
    }

    /**
     * Check if price decreased
     */
    public function isDecrease(): bool
    {
        return $this->price_difference < 0;
    }

    /**
     * Get formatted price difference
     */
    public function getFormattedDifferenceAttribute(): string
    {
        $sign = $this->price_difference > 0 ? '+' : ($this->price_difference < 0 ? '-' : '');
        return $sign . 'Rs. ' . number_format(abs($this->price_difference), 2);
    }

    /**
     * Get formatted percentage change
     */
    public function getFormattedPercentageAttribute(): string
    {
        $sign = $this->percentage_change > 0 ? '+' : ($this->percentage_change < 0 ? '-' : '');
        return $sign . number_format(abs($this->percentage_change), 2) . '%';
    }

    /**
     * Approve the price change
     */
    public function approve($employeeId)
    {
        $this->status = 'approved';
        $this->approved_by = $employeeId;
        $this->approved_at = now();
        $this->save();
    }

    /**
     * Reject the price change
     */
    public function reject($reason = null)
    {
        $this->status = 'rejected';
        $this->notes = $reason ? ($this->notes ? $this->notes . "\n\nRejection reason: " . $reason : 'Rejection reason: ' . $reason) : $this->notes;
        $this->save();
    }
}
