<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalerProductPriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'employee_id',
        'old_cost_price',
        'old_selling_price',
        'old_discount',
        'old_final_price',
        'new_cost_price',
        'new_selling_price',
        'new_discount',
        'new_final_price',
        'notes',
        'change_type'
    ];

    protected $casts = [
        'old_cost_price' => 'decimal:2',
        'old_selling_price' => 'decimal:2',
        'old_discount' => 'decimal:2',
        'old_final_price' => 'decimal:2',
        'new_cost_price' => 'decimal:2',
        'new_selling_price' => 'decimal:2',
        'new_discount' => 'decimal:2',
        'new_final_price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(WholesalerProduct::class, 'product_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // Calculate price change percentage
    public function getPriceChangePercentageAttribute()
    {
        if ($this->old_final_price > 0) {
            return (($this->new_final_price - $this->old_final_price) / $this->old_final_price) * 100;
        }
        return 0;
    }
}
