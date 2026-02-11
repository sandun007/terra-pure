<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Saler extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'postal_code',
        'district',
        'state',
        'country',
        'phone',
        'whatsapp',
        'email',
        'NIC_no',
        'NIC_front',
        'NIC_back',
        'username',
        'password',
        'view_password',
        'type',
        'img',
        'status',
        'profile_image',
        'order_count',
        'total_sales',
        'deposit',
        'cal_deposit',
        'cost'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'total_sales' => 'decimal:2',
        'deposit' => 'decimal:2',
        'cal_deposit' => 'decimal:2',
        'cost' => 'decimal:2',
    ];

    // Automatically hash password when it's set
    public function setPasswordAttribute($value)
    {
        if (!empty($value) && !Hash::needsRehash($value)) {
            $this->attributes['password'] = $value;
        } elseif (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // Relationships - FIXED to match your database schema

    /**
     * Get orders for this seller
     * IMPORTANT: Check your orders table to see which column links to seller
     * Common options: saler_id, seller_id, seller_code
     */
    public function orders()
    {
        // Option 1: If orders table has 'saler_id' column
        return $this->hasMany(Order::class, 'saler_id', 'id');

        // Option 2: If orders table has 'seller_id' column
        // return $this->hasMany(Order::class, 'seller_id', 'id');

        // Option 3: If orders table has 'seller_code' column linked to seller 'code'
        // return $this->hasMany(Order::class, 'seller_code', 'code');
    }

    /**
     * Get products for this seller
     * IMPORTANT: Check your products table to see which column links to seller
     */
    public function products()
    {
        // Option 1: If products table has 'saler_id' column
        return $this->hasMany(Product::class, 'saler_id', 'id');

        // Option 2: If products table has 'seller_id' column
        // return $this->hasMany(Product::class, 'seller_id', 'id');

        // Option 3: If products table has 'seller_code' column linked to seller 'code'
        // return $this->hasMany(Product::class, 'seller_code', 'code');
    }

    /**
     * Get referrals for this seller
     */
    public function referrals()
    {
        return $this->hasMany(Referral::class, 'level_01_seller_01', 'code');
    }

    // Accessor for profile image URL
    public function getProfileImageUrlAttribute()
    {
        // Check for img field first (used in wholesaler)
        if ($this->img) {
            return asset('storage/' . $this->img);
        }

        // Check for profile_image field (used in seller migration)
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }

        return asset('asset/img/default-avatar.png');
    }

    // Accessor for full location
    public function getFullLocationAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->district,
            $this->state,
            $this->country,
            $this->postal_code
        ]);

        return implode(', ', $parts);
    }

    // Accessor for NIC front image URL
    public function getNicFrontUrlAttribute()
    {
        return $this->NIC_front ? asset('storage/' . $this->NIC_front) : null;
    }

    // Accessor for NIC back image URL
    public function getNicBackUrlAttribute()
    {
        return $this->NIC_back ? asset('storage/' . $this->NIC_back) : null;
    }

    // Scope for active sellers
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for pending sellers
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
