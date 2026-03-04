<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SelfSellerDeliveryCall extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'self_seller_delivery_calls';

    protected $fillable = [
        'seller_id',
        'product_id',
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
        'destination_city_id',
        'destination_state_name',
        'origin_city_id',
        'origin_city_name',
        'origin_state_name',
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
        'curfox_waybill',
        'curfox_dispatched_at',
        'curfox_error',
    ];

    protected $casts = [
        'date' => 'date',
        'status_date' => 'datetime',
        'qty' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'download_status' => 'boolean',
        'curfox_dispatched_at' => 'datetime',
    ];

    /**
     * Generate a unique call number for self-seller
     */
    public static function generateCallNumber($sellerId)
    {
        $prefix = 'SC-' . str_pad($sellerId, 3, '0', STR_PAD_LEFT) . '-';

        $lastCall = self::where('call_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        if ($lastCall) {
            $lastNumber = (int) str_replace($prefix, '', $lastCall->call_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Relationships
     */
    public function seller()
    {
        return $this->belongsTo(Saler::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(SellerProduct::class, 'product_id');
    }

    /**
     * Origin helpers
     */
    public function hasCustomOrigin(): bool
    {
        return !empty($this->origin_city_name);
    }

    public function effectiveOriginCityName(): string
    {
        return $this->origin_city_name ?? 'Default Origin';
    }

    public function effectiveOriginStateName(): string
    {
        return $this->origin_state_name ?? 'Default State';
    }

    public function effectiveOriginCityId(): ?int
    {
        return $this->origin_city_id;
    }

    /**
     * Scope for status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get next status
     */
    public function getNextStatusAttribute()
    {
        $flow = [
            'pending' => 'packing',
            'packing' => 'packed',
            'packed' => 'givedriver',
            'givedriver' => null,
        ];

        return $flow[$this->status] ?? null;
    }

    /**
     * Get progress percentage
     */
    public function getProgressPercentageAttribute()
    {
        $progress = [
            'pending' => 25,
            'packing' => 50,
            'packed' => 75,
            'givedriver' => 100,
        ];

        return $progress[$this->status] ?? 0;
    }
}
