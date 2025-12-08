<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_id',
        'code',
        'name',
        'image',  // Changed from 'img' to 'image'
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    // Relationship with Wholesaler
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    // Relationship with Wholesaler Products
    public function wholesalerProducts()
    {
        return $this->hasMany(WholesalerProduct::class, 'brand_id');
    }

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/default-brand.png');
    }

    // Scope for active brands
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for wholesaler brands
    public function scopeForWholesaler($query, $wholesalerId)
    {
        return $query->where('wholesaler_id', $wholesalerId);
    }
}
