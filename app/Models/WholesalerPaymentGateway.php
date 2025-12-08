<?php
// app/Models/WholesalerPaymentGateway.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class WholesalerPaymentGateway extends Model
{
    use HasFactory;

    protected $fillable = [
        'wholesaler_id',
        'gateway_name',
        'gateway_type',
        'api_key',
        'api_secret',
        'api_url',
        'webhook_url',
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

    // Accessors for decrypted values
    public function getDecryptedApiKeyAttribute()
    {
        return Crypt::decrypt($this->api_key);
    }

    public function getDecryptedApiSecretAttribute()
    {
        return Crypt::decrypt($this->api_secret);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }
}
