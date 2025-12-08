<?php
// app/Models/ProductReview.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'customer_name',
        'customer_email',
        'rating',
        'review',
        'approved'
    ];

    public function product()
    {
        return $this->belongsTo(WholesalerProduct::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
