<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeDeliveryCall extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employee_delivery_call';

    // System-wide fallback when origin fields are null on the record
    public const DEFAULT_ORIGIN_CITY_NAME  = 'Colombo 01';
    public const DEFAULT_ORIGIN_STATE_NAME = 'Colombo';

    protected $fillable = [
        'seller_id', 'product_id', 'call_id',
        'order_code', 'call_number', 'date', 'status_date',
        'customer_name', 'customer_phone_01', 'customer_phone_02',
        'customer_address', 'city', 'district',
        'destination_city_id', 'destination_state_name',
        'curfox_waybill', 'curfox_dispatched_at', 'curfox_error',
        // ── Origin fields (per-call override) ──
        'origin_city_id',
        'origin_city_name',
        'origin_state_name',
        // ────────────────────────────────────────
        'item_code', 'item_name', 'qty', 'price', 'total',
        'status', 'remark', 'platform',
        'ad_name', 'campaign_name', 'lead_status',
        'call_duration', 'call_recording_url', 'download_status',
    ];

    protected $casts = [
        'date'                 => 'date',
        'status_date'          => 'datetime',
        'curfox_dispatched_at' => 'datetime',
        'destination_city_id'  => 'integer',
        'origin_city_id'       => 'integer',
        'qty'                  => 'integer',
        'price'                => 'decimal:2',
        'total'                => 'decimal:2',
        'download_status'      => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────────────

    public function seller()       { return $this->belongsTo(User::class, 'seller_id'); }
    public function product()      { return $this->belongsTo(SellerProduct::class, 'product_id'); }
    public function originalCall() { return $this->belongsTo(SellerCall::class, 'call_id'); }

    // ── Origin helpers (used by dispatchToCurfox) ─────────────────────────

    /** Returns the city ID to use as origin, or null if none set. */
    public function effectiveOriginCityId(): ?int
    {
        return $this->origin_city_id ?? null;
    }

    /** Returns the city name to use as origin. Falls back to system default. */
    public function effectiveOriginCityName(): string
    {
        return $this->origin_city_name ?? self::DEFAULT_ORIGIN_CITY_NAME;
    }

    /** Returns the state name to use as origin. Falls back to system default. */
    public function effectiveOriginStateName(): string
    {
        return $this->origin_state_name ?? self::DEFAULT_ORIGIN_STATE_NAME;
    }

    /** True if the user explicitly set an origin on this record. */
    public function hasCustomOrigin(): bool
    {
        return ! empty($this->origin_city_name);
    }
}
