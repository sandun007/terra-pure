<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerCall extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'seller_calls';

    protected $fillable = [
        'seller_id',
        'product_id',
        'order_code',
        'call_number',
        'date',
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
        'status_date',
        'remark',
        'platform',
        'ad_name',
        'campaign_name',
        'lead_status',
        'download_status',
        'call_duration',
        'call_recording_url',
    ];

    protected $casts = [
        'date' => 'date',
        'status_date' => 'date',
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

    // ─── Relationships ────────────────────────────────────────────────

    /**
     * Get the seller that owns the call
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Get the product associated with the call
     */
    public function product()
    {
        return $this->belongsTo(SellerProduct::class, 'product_id');
    }

    /**
     * Get the associated order if converted
     */
    public function order()
    {
        return $this->hasOne(SellerOrder::class, 'call_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────

    /**
     * Scope to filter pending calls
     */
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    /**
     * Scope to filter confirmed calls
     */
    public function scopeConfirmed($query)
    {
        return $query->where('status', 'Conform');
    }

    /**
     * Scope to filter rejected calls
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'Reject');
    }

    /**
     * Scope to filter not answered calls
     */
    public function scopeNotAnswered($query)
    {
        return $query->where('status', 'Not Answer');
    }

    /**
     * Scope to filter by seller
     */
    public function scopeBySeller($query, $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope to filter not downloaded
     */
    public function scopeNotDownloaded($query)
    {
        return $query->where('download_status', 0);
    }

    // ─── Accessors & Mutators ─────────────────────────────────────────

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return match(strtolower($this->status)) {
            'conform', 'confirm' => 'status-confirm',
            'pending' => 'status-pending',
            'reject' => 'status-reject',
            'not answer', 'not anser' => 'status-not-answer',
            'whatsapp' => 'status-whatsapp',
            'other' => 'status-other',
            default => 'status-pending',
        };
    }

    /**
     * Get formatted total amount
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rs. ' . number_format($this->total, 2);
    }

    /**
     * Get status icon class
     */
    public function getStatusIconAttribute()
    {
        return match(strtolower($this->status)) {
            'conform', 'confirm' => 'fas fa-check-circle',
            'pending' => 'fas fa-clock',
            'reject' => 'fas fa-times-circle',
            'not answer', 'not anser' => 'fas fa-phone-slash',
            'whatsapp' => 'fab fa-whatsapp',
            'other' => 'fas fa-ellipsis-h',
            default => 'fas fa-question-circle',
        };
    }

    /**
     * Check if call is convertible to order
     */
    public function getIsConvertibleAttribute()
    {
        return $this->status === 'Conform' && !$this->order()->exists();
    }

    /**
     * Check if call can be edited
     */
    public function getIsEditableAttribute()
    {
        return in_array($this->status, ['Pending', 'Not Answer']);
    }

    /**
     * Check if call can be deleted
     */
    public function getIsDeletableAttribute()
    {
        return $this->status === 'Pending';
    }

    // ─── Helper Methods ───────────────────────────────────────────────

    /**
     * Generate unique call number
     */
    public static function generateCallNumber()
    {
        $prefix = 'CALL/';
        $lastCall = self::latest('id')->first();

        if ($lastCall && $lastCall->call_number) {
            $lastNumber = (int) str_replace($prefix, '', $lastCall->call_number);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Mark as downloaded
     */
    public function markAsDownloaded()
    {
        $this->update(['download_status' => true]);
    }

    /**
     * Convert call to order
     */
    public function convertToOrder()
    {
        if (!$this->is_convertible) {
            return false;
        }

        // Create order from call data
        $order = SellerOrder::create([
            'seller_id' => $this->seller_id,
            'product_id' => $this->product_id,
            'call_id' => $this->id,
            'order_id' => SellerOrder::generateOrderId(),
            'order_number' => SellerOrder::generateOrderId(),
            'product_code' => $this->item_code,
            'product_name' => $this->item_name,
            'product_price' => $this->price,
            'customer_name' => $this->customer_name,
            'customer_phone' => $this->customer_phone_01,
            'customer_address' => $this->customer_address,
            'district' => $this->city,
            'quantity' => $this->qty,
            'total_amount' => $this->total,
            'subtotal' => $this->total,
            'order_date' => now(),
            'status' => 'pending',
            'payment_method' => 'cash',
            'payment_status' => 'pending',
            'remark' => $this->remark,
        ]);

        return $order;
    }

    /**
     * Update status with timestamp
     */
    public function updateStatus($status, $remark = null)
    {
        $this->update([
            'status' => $status,
            'status_date' => now(),
            'remark' => $remark ?? $this->remark,
        ]);
    }

    // ─── Boot Method ──────────────────────────────────────────────────

    protected static function boot()
    {
        parent::boot();

        // Auto-generate call number if not provided
        static::creating(function ($call) {
            if (empty($call->call_number)) {
                $call->call_number = self::generateCallNumber();
            }

            // Set status date if status is set
            if ($call->status && empty($call->status_date)) {
                $call->status_date = now();
            }

            // Calculate total if not provided
            if (empty($call->total) && $call->qty && $call->price) {
                $call->total = $call->qty * $call->price;
            }
        });

        // Update total when qty or price changes
        static::updating(function ($call) {
            if ($call->isDirty(['qty', 'price'])) {
                $call->total = $call->qty * $call->price;
            }

            // Update status date when status changes
            if ($call->isDirty('status')) {
                $call->status_date = now();
            }
        });
    }
}
