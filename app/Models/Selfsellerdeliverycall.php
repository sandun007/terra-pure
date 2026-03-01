<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SelfSellerDeliveryCall extends Model
{
    use SoftDeletes;

    protected $table = 'self_seller_delivery_calls';

    protected $fillable = [
        'seller_id', 'product_id', 'call_id',
        'call_number', 'order_code', 'date', 'status_date',
        'customer_name', 'customer_phone_01', 'customer_phone_02',
        'customer_address', 'city', 'district',
        'destination_city_id', 'destination_state_name',
        'origin_city_id', 'origin_city_name', 'origin_state_name',
        'curfox_waybill', 'curfox_dispatched_at', 'curfox_error',
        'curfox_business_id', 'curfox_business_name',
        'item_code', 'item_name', 'qty', 'price', 'total',
        'status', 'remark', 'platform', 'ad_name', 'campaign_name',
        'lead_status', 'call_duration', 'call_recording_url', 'download_status',
    ];

    protected $casts = [
        'date'                => 'date',
        'status_date'         => 'datetime',
        'curfox_dispatched_at'=> 'datetime',
        'price'               => 'decimal:2',
        'total'               => 'decimal:2',
        'download_status'     => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function seller()
    {
        return $this->belongsTo(Saler::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // ── Origin helpers (same pattern as EmployeeDeliveryCall) ──────────────

    public function hasCustomOrigin(): bool
    {
        return ! empty($this->origin_city_name);
    }

    public function effectiveOriginCityName(): string
    {
        return $this->origin_city_name ?: 'Colombo 01';
    }

    public function effectiveOriginStateName(): string
    {
        return $this->origin_state_name ?: 'Colombo';
    }

    public function effectiveOriginCityId(): ?int
    {
        return $this->origin_city_id ? (int) $this->origin_city_id : null;
    }

    // ── Call number generator ──────────────────────────────────────────────

    public static function generateCallNumber(int $sellerId): string
    {
        $last = static::withTrashed()
            ->where('seller_id', $sellerId)
            ->orderByDesc('id')
            ->value('call_number');

        $seq = 1;
        if ($last && preg_match('/(\d+)$/', $last, $m)) {
            $seq = (int) $m[1] + 1;
        }

        return 'SS/CALL/' . str_pad($seq, 5, '0', STR_PAD_LEFT);
    }
}
