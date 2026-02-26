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

    protected $appends = [
        'status_badge_class',
        'formatted_total',
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

    // Scopes
    public function scopeBySeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeNotDownloaded($query)
    {
        return $query->where('download_status', 0);
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'Pending' => 'bg-warning',
            'packing' => 'bg-info',
            'packed' => 'bg-primary',
            'givedriver' => 'bg-success',
            default => 'bg-secondary',
        };
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rs. ' . number_format($this->total, 2);
    }

    public function getStatusIconAttribute()
    {
        return match($this->status) {
            'Pending' => 'fas fa-clock',
            'packing' => 'fas fa-box-open',
            'packed' => 'fas fa-box',
            'givedriver' => 'fas fa-truck',
            default => 'fas fa-question-circle',
        };
    }

    public function markAsDownloaded()
    {
        $this->update(['download_status' => true]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($call) {
            if (empty($call->call_number)) {
                $call->call_number = 'DEL/' . date('Ymd') . '/' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
            }
            if ($call->status && empty($call->status_date)) {
                $call->status_date = now();
            }
            if (empty($call->total) && $call->qty && $call->price) {
                $call->total = $call->qty * $call->price;
            }
        });

        static::updating(function ($call) {
            if ($call->isDirty(['qty', 'price'])) {
                $call->total = $call->qty * $call->price;
            }
            if ($call->isDirty('status')) {
                $call->status_date = now();
            }
        });
    }
}
