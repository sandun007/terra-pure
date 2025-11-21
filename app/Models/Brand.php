<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'img',
        'seller_code',
        'wholesaler_id' // Added this
    ];

    // Relationship with Wholesaler
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    // Relationship with Products
    public function products()
    {
        return $this->hasMany(Product::class, 'brand', 'name');
    }

    // Scope for wholesaler brands
    public function scopeForWholesaler($query, $wholesalerId)
    {
        return $query->where('wholesaler_id', $wholesalerId);
    }
}
