<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerCurfoxBusiness extends Model
{
    protected $table = 'seller_curfox_businesses';

    protected $fillable = [
        'seller_id', 'curfox_id', 'ref_no', 'business_name', 'merchant_id',
        'is_default', 'address_line_1', 'address_line_2', 'address_line_3',
        'city_id', 'city_name', 'phone', 'postal_code',
        'bank_account_name', 'bank_account_number', 'bank_name', 'bank_branch',
        'raw_response',
    ];

    protected $casts = [
        'is_default'   => 'boolean',
        'raw_response' => 'array',
    ];

    public function seller()
    {
        return $this->belongsTo(Saler::class, 'seller_id');
    }

    // The merchant_business_id to use when dispatching to Curfox
    // Curfox API uses data.id (the row ID), NOT data.merchant_id for business selection
    public function getCurfoxDispatchId(): string
    {
        return (string) ($this->curfox_id ?? '');
    }
}
