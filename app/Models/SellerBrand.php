<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Add this import

class SellerBrand extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'code',
        'name',
        'image',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];

    protected $appends = ['image_url'];

    // Relationship with seller
    public function seller()
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    // Relationship with products
    public function products()
    {
        return $this->hasMany(SellerProduct::class, 'brand_id', 'id');
    }

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return $this->getDefaultImageUrl();
        }

        // Check if it's already a full URL
        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        // Check if image exists in storage
        if (Storage::disk('public')->exists($this->image)) {
            return Storage::disk('public')->url($this->image);
        }

        // Try alternative paths
        return $this->getAlternativeImageUrl();
    }

    /**
     * Get default image URL
     */
    private function getDefaultImageUrl()
    {
        // Check for default image in multiple locations
        $defaultPaths = [
            'images/default-brand.png',
            'storage/images/default-brand.png',
            'vendor/images/default-brand.png',
        ];

        foreach ($defaultPaths as $path) {
            if (file_exists(public_path($path))) {
                return asset($path);
            }
        }

        // Return a placeholder if no default image found
        return 'https://via.placeholder.com/150?text=No+Image';
    }

    /**
     * Get alternative image URL if main path fails
     */
    private function getAlternativeImageUrl()
    {
        // Try direct public path
        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        // Try with storage path
        if (file_exists(storage_path('app/public/' . $this->image))) {
            return asset('storage/' . $this->image);
        }

        // Return default if all fails
        return $this->getDefaultImageUrl();
    }
}
