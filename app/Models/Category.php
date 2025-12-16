<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_id',
        'code',
        'name',
        'slug',
        'description',
        'img',
        'image',
        'status',
        'add_by',
        'update_by',
        'cancel',
        'parent_id',
        'seller_code'
    ];

    // Don't use SoftDeletes trait if column doesn't exist
    // protected $dates = ['deleted_at'];

    // Relationship with Wholesaler
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    // Relationship with Products
    public function products()
    {
        return $this->hasMany(WholesalerProduct::class, 'category_id');
    }

    // Scope for wholesaler categories
    public function scopeForWholesaler($query, $wholesalerId)
    {
        $wholesaler = 0;
        return $query->where('wholesaler_id', $wholesaler->id);
    }

    // Get image URL
    public function getImageUrlAttribute()
    {
        if ($this->image && file_exists(public_path('storage/' . $this->image))) {
            return asset('storage/' . $this->image);
        }
        // Fallback to old img column
        if ($this->img && file_exists(public_path('storage/' . $this->img))) {
            return asset('storage/' . $this->img);
        }
        return asset('assets/images/default-category.png');
    }

    // Get status badge class
    public function getStatusBadgeAttribute()
    {
        $status = $this->status ?? 'active';
        return $status == 'active' ? 'badge bg-success' : 'badge bg-danger';
    }

    // Get status text
    public function getStatusTextAttribute()
    {
        $status = $this->status ?? 'active';
        return ucfirst($status);
    }
}
