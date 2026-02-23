<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SellerBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'code',
        'name',
        'description',  // Make sure this is in your fillable array
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    protected $appends = ['image_url'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function seller()
    {
        return $this->belongsTo(Saler::class, 'seller_id');
    }

    public function products()
    {
        // This uses brand_id as the foreign key in seller_products table
        return $this->hasMany(SellerProduct::class, 'brand_id', 'id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
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
        return asset('images/default-brand.png');
    }
}
