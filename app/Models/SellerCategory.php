<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class SellerCategory extends Model
{
    use HasFactory, SoftDeletes;

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

    protected $appends = ['image_url'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    public function sellerProducts()
    {
        return $this->hasMany(SellerProduct::class, 'category_id');
    }

    public function wholesalerProducts()
    {
        return $this->hasMany(WholesalerProduct::class, 'category', 'name');
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSellerCategories($query, $sellerId)
    {
        return $query->where(function($q) use ($sellerId) {
            $q->where('seller_id', $sellerId)
                ->orWhere(function($sub) {
                    $sub->whereNull('seller_id')
                        ->where('seller_code', 1);
                });
        });
    }

    public function scopeWholesalerCategories($query, $wholesalerId)
    {
        return $query->where(function($q) use ($wholesalerId) {
            $q->where('wholesaler_id', $wholesalerId)
                ->orWhere(function($sub) {
                    $sub->whereNull('wholesaler_id')
                        ->where('seller_code', 0);
                });
        });
    }

    public function scopeOwnedBySeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    /*
    |--------------------------------------------------------------------------
    | Image Accessor
    |--------------------------------------------------------------------------
    */

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return $this->defaultImage();
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        return Storage::disk('public')->url($this->image);
    }

    /*
    |--------------------------------------------------------------------------
    | Default Image
    |--------------------------------------------------------------------------
    */

    private function defaultImage()
    {
        return asset('images/default-category.png');
    }
}
