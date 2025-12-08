<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Employee extends Authenticatable
{
    use HasFactory,Notifiable, HasApiTokens;

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
        'nic_back'
    ];
}
