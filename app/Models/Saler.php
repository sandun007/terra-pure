<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Saler extends Model
{
    use HasFactory;
    protected $fillable=[
        'code',
        'name',
        'address',
        'district',
        'phone',
        'whatsapp',
        'email',
        'NIC_no',
        'NIC_front',
        'NIC_back',
        'username',
        'password',
        'view_password'
    ];
}
