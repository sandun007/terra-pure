<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Wholesaler extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'code',
        'name',
        'address',
        'city',
        'postal_code',
        'district',
        'phone',
        'whatsapp',
        'email',
        'username',
        'password',
        'business_name',
        'img',
        'type'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
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

    // Relationships
    public function payments()
    {
        return $this->hasMany(WholesalerPayment::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(WholesalerPaymentMethod::class);
    }

    public function shop()
    {
        return $this->hasOne(WholesalerShop::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    // Accessor for profile image URL
    public function getProfileImageUrlAttribute()
    {
        if ($this->img) {
            return asset('storage/' . $this->img);
        }
        return asset('asset/img/default-avatar.png');
    }
}
