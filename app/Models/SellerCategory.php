<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerCategory extends Model
{
    use HasFactory, SoftDeletes;

    // Specify the table name
    protected $table = 'seller_categories';

    protected $fillable = [
        'seller_id',
        'wholesaler_id',
        'code',
        'name',
        'image',
        'status',
        'add_by',
        'update_by',
        'cancel',
        'seller_code',
    ];

    // Seller relationship
    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    // Wholesaler relationship
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    // Seller products
    public function sellerProducts()
    {
        return $this->hasMany(SellerProduct::class, 'category_id');
    }

    // Wholesaler products
    public function wholesalerProducts()
    {
        return $this->hasMany(WholesalerProduct::class, 'category', 'name');
    }

    // Scope for seller categories
    public function scopeSellerCategories($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId)
            ->orWhere(function($q) {
                $q->whereNull('seller_id')
                    ->where('seller_code', 1);
            });
    }

    // Scope for wholesaler categories
    public function scopeWholesalerCategories($query, $wholesalerId)
    {
        return $query->where('wholesaler_id', $wholesalerId)
            ->orWhere(function($q) {
                $q->whereNull('wholesaler_id')
                    ->where('seller_code', 0);
            });
    }
}
