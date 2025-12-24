<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Employee extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $guard = 'employee';

    protected $fillable = [
        'code',
        'name',
        'nic_no',
        'mobile_no',
        'whatsapp_no',
        'email',
        'address',
        'post_code',
        'username',
        'password',
        'view_password',
        'district',
        'nic_front',
        'nic_back',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relationships
    public function priceChanges()
    {
        return $this->hasMany(PriceChangeLog::class);
    }

    public function orderStatusChanges()
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        return $initials;
    }
}
