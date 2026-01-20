<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'employee';

    // SIMPLIFIED - REMOVED MOBILE AND DOCUMENT FIELDS
    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'nic_no',
        'post_code',
        'username',
        'password',
        'view_password',
        'district',
        'img',
        'role',
        'status',
        'last_login_at',
        'last_login_ip'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'view_password'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'last_login_at' => 'datetime',
    ];

    protected $appends = [
        'profile_image_url',
        'full_address'
    ];

    // Automatically hash password when it's set
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    // Accessor for profile image URL
    public function getProfileImageUrlAttribute()
    {
        if ($this->img) {
            // Check if it's already a full URL or relative path
            if (filter_var($this->img, FILTER_VALIDATE_URL)) {
                return $this->img;
            }

            if (Storage::disk('public')->exists($this->img)) {
                return asset('storage/' . $this->img);
            }
        }
        return asset('asset/img/default-avatar.png');
    }

    // Accessor for full address
    public function getFullAddressAttribute()
    {
        $address = [];
        if ($this->address) $address[] = $this->address;
        if ($this->district) $address[] = $this->district;
        if ($this->post_code) $address[] = $this->post_code;

        return implode(', ', $address);
    }

    // Relationships
    public function priceChanges()
    {
        return $this->hasMany(EmployeePriceChange::class);
    }

    // Method to check if employee is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    // Method to check if employee is manager
    public function isManager()
    {
        return $this->role === 'manager';
    }

    // Method to check if employee is staff
    public function isStaff()
    {
        return $this->role === 'staff';
    }

    // Method to get role badge color
    public function getRoleBadgeColor()
    {
        return match($this->role) {
            'admin' => 'danger',
            'manager' => 'warning',
            'staff' => 'success',
            default => 'secondary'
        };
    }

    // Method to get status badge color
    public function getStatusBadgeColor()
    {
        return $this->status === 'active' ? 'success' : 'danger';
    }

    // Update last login
    public function updateLastLogin($ip = null)
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => $ip ?? request()->ip()
        ]);
    }
}
