<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDeliveryCall extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employee_delivery_call';

    protected $fillable = [
        'seller_id',
        'product_id',
        'call_id',
        'order_code',
        'call_number',
        'date',
        'status_date',
        'customer_name',
        'customer_phone_01',
        'customer_phone_02',
        'customer_address',
        'city',
        'district',
        'item_code',
        'item_name',
        'qty',
        'price',
        'total',
        'status',
        'remark',
        'platform',
        'ad_name',
        'campaign_name',
        'lead_status',
        'call_duration',
        'call_recording_url',
        'download_status',
    ];

    protected $casts = [
        'date' => 'date',
        'status_date' => 'datetime',
        'qty' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'download_status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(SellerProduct::class, 'product_id');
    }

    public function originalCall()
    {
        return $this->belongsTo(SellerCall::class, 'call_id');
    }
}
