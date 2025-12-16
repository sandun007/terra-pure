<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', // This should be wholesaler_product_id
        'user_id',
        'rating',
        'review',
        'status', // Changed from approved to status
        'wholesaler_id',
        'response',
        'response_date'
    ];

    protected $casts = [
        'rating' => 'integer',
        'response_date' => 'datetime'
    ];

    // Update relationship to use correct column name
    public function product()
    {
        return $this->belongsTo(WholesalerProduct::class, 'product_id');
    }

    public function wholesalerProduct()
    {
        return $this->belongsTo(WholesalerProduct::class, 'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope for active reviews
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
