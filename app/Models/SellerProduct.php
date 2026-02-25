<?php
// app/Models/Seller.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Seller extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'seller';

    protected $fillable = [
        'name',
        'email',
        'password',
        'code',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'status',
        'approved_at',
        'approved_by',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'last_login_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the products for the seller
     */
    public function products()
    {
        return $this->hasMany(SellerProduct::class, 'seller_id');
    }

    /**
     * Get the shop for the seller
     */
    public function shop()
    {
        return $this->hasOne(SellerShop::class, 'seller_id');
    }

    /**
     * Get the orders for the seller
     */
    public function orders()
    {
        return $this->hasMany(SellerOrder::class, 'seller_id');
    }

    /**
     * Get the calls for the seller
     */
    public function calls()
    {
        return $this->hasMany(SellerCall::class, 'seller_id');
    }

    /**
     * Get the approver employee
     */
    public function approver()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    /**
     * Get the rejector employee
     */
    public function rejector()
    {
        return $this->belongsTo(Employee::class, 'rejected_by');
    }

    /**
     * Check if seller is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Check if seller is approved
     */
    public function isApproved()
    {
        return !is_null($this->approved_at);
    }

    /**
     * Get formatted code
     */
    public function getFormattedCodeAttribute()
    {
        return 'SELLER-' . str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->country,
            $this->zip_code,
        ]);

        return implode(', ', $parts);
    }
}
