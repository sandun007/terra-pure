<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    // ─── Relationships ────────────────────────────────────────────────────────

    public function orders()
    {
        return $this->hasMany(Order::class, 'saler_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'saler_id', 'id');
    }

    public function referrals()
    {
        return $this->hasMany(Referral::class, 'level_01_seller_01', 'code');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * Profile image URL using Storage::disk('public')->url()
     * Falls back to default avatar if neither img nor profile_image is set.
     */
    public function getProfileImageUrlAttribute(): string
    {
        if ($this->img) {
            return Storage::disk('public')->url($this->img);
        }

        if ($this->profile_image) {
            return Storage::disk('public')->url($this->profile_image);
        }

        return asset('asset/img/default-avatar.png');
    }

    /**
     * NIC front image URL using Storage::disk('public')->url()
     */
    public function getNicFrontUrlAttribute(): ?string
    {
        return $this->NIC_front
            ? Storage::disk('public')->url($this->NIC_front)
            : null;
    }

    /**
     * NIC back image URL using Storage::disk('public')->url()
     */
    public function getNicBackUrlAttribute(): ?string
    {
        return $this->NIC_back
            ? Storage::disk('public')->url($this->NIC_back)
            : null;
    }

    /**
     * Full address string assembled from location fields.
     */
    public function getFullLocationAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->district,
            $this->state,
            $this->country,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
