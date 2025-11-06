<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

class Saler extends Authenticatable
{
    use HasApiTokens, Notifiable;

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
        'NIC_no',
        'NIC_front',
        'NIC_back',
        'username',
        'password',
        'view_password',
        'type'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Automatically hash password when it's set
    public function setPasswordAttribute($value)
    {
        if (!Hash::needsRehash($value)) {
            $this->attributes['password'] = $value;
        } else {
            $this->attributes['password'] = Hash::make($value);
        }
    }
}
