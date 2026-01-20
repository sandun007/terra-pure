<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePriceChange extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'employee_id',
        'old_cost_price',
        'new_cost_price',
        'old_selling_price',
        'new_selling_price',
        'old_final_price',
        'new_final_price',
        'price_difference',
        'percentage_change',
        'notes',
        'change_reason',
    ];

    protected $casts = [
        'old_cost_price' => 'decimal:2',
        'new_cost_price' => 'decimal:2',
        'old_selling_price' => 'decimal:2',
        'new_selling_price' => 'decimal:2',
        'old_final_price' => 'decimal:2',
        'new_final_price' => 'decimal:2',
        'price_difference' => 'decimal:2',
        'percentage_change' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function product()
    {
        return $this->belongsTo(WholesalerProduct::class, 'product_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Accessors
     */
    public function getFormattedOldSellingPriceAttribute()
    {
        return 'Rs. ' . number_format($this->old_selling_price, 2);
    }

    public function getFormattedNewSellingPriceAttribute()
    {
        return 'Rs. ' . number_format($this->new_selling_price, 2);
    }

    public function getFormattedPriceDifferenceAttribute()
    {
        $sign = $this->price_difference >= 0 ? '+' : '';
        return $sign . 'Rs. ' . number_format(abs($this->price_difference), 2);
    }

    public function getFormattedPercentageChangeAttribute()
    {
        $sign = $this->percentage_change >= 0 ? '+' : '';
        return $sign . number_format($this->percentage_change, 2) . '%';
    }

    /**
     * Scopes
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}
