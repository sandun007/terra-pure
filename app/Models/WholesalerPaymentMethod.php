<?php
// app/Models/WholesalerPaymentMethod.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalerPaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_id',
        'method_type',
        'bank_name',
        'account_name',
        'account_number',
        'branch',
        'mobile_provider',
        'is_default',
        'status',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    // Accessor for formatted method display
    public function getFormattedMethodAttribute()
    {
        if ($this->method_type == 'bank') {
            return $this->bank_name . ' - ' . $this->account_number;
        } else {
            return ucfirst($this->mobile_provider) . ' - ' . $this->account_number;
        }
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeBank($query)
    {
        return $query->where('method_type', 'bank');
    }

    public function scopeMobile($query)
    {
        return $query->where('method_type', 'mobile');
    }
}
