<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable=[
        'code',
        'name',
        'img',
        'status',
        'add_by',
        'update_by',
        'cancel',
        'seller_code'
    ];
}
