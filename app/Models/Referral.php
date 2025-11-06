<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;
    protected $fillable = [
        'level_01_seller_01',
        'level_01_seller_02',
        'level_02_seller_01',
        'level_02_seller_02',
        'level_03_seller_01',
        'level_03_seller_02'
    ];
}
