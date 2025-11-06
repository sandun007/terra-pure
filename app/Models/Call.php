<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    use HasFactory;
    protected $fillable=[
        'order_code',
        'date',
        'customer_name',
        'customer_phone_01',
        'customer_phone_02',
        'status'
    ];
}
