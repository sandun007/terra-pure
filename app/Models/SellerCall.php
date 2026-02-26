<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(SellerProduct::class, 'product_id');
    }

    public function order()
    {
        return $this->hasOne(SellerOrder::class, 'call_id');
    }

    public function employeeDeliveryCall()
    {
        return $this->hasOne(EmployeeDeliveryCall::class, 'call_id');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'Conform');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'Reject');
    }

    public function scopeNotAnswered($query)
    {
        return $query->where('status', 'Not Answer');
    }

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

    public function getFormattedTotalAttribute()
    {
        return 'Rs. ' . number_format($this->total, 2);
    }

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

    public function getIsConvertibleAttribute()
    {
        return $this->status === 'Conform' && !$this->order()->exists();
    }

    public function getIsEditableAttribute()
    {
        return in_array($this->status, ['Pending', 'Not Answer']);
    }

    public function getIsDeletableAttribute()
    {
        return $this->status === 'Pending';
    }

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

    public function markAsDownloaded()
    {
        $this->update(['download_status' => true]);
    }

    public function convertToOrder()
    {
        if (!$this->is_convertible) {
            return false;
        }

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

    public function updateStatus($status, $remark = null)
    {
        $this->update([
            'status' => $status,
            'status_date' => now(),
            'remark' => $remark ?? $this->remark,
        ]);
    }

    /**
     * Sync to employee_delivery_call table
     */
    public function syncToEmployeeDelivery()
    {
        try {
            // Check if already synced
            $existingSync = EmployeeDeliveryCall::where('call_id', $this->id)->first();

            if ($existingSync) {
                // Update existing record
                $existingSync->update([
                    'seller_id' => $this->seller_id,
                    'product_id' => $this->product_id,
                    'order_code' => $this->order_code,
                    'call_number' => $this->call_number,
                    'date' => $this->date,
                    'status_date' => $this->status_date,
                    'customer_name' => $this->customer_name,
                    'customer_phone_01' => $this->customer_phone_01,
                    'customer_phone_02' => $this->customer_phone_02,
                    'customer_address' => $this->customer_address,
                    'city' => $this->city,
                    'district' => $this->district,
                    'item_code' => $this->item_code,
                    'item_name' => $this->item_name,
                    'qty' => $this->qty,
                    'price' => $this->price,
                    'total' => $this->total,
                    'status' => $this->status,
                    'remark' => $this->remark,
                    'platform' => $this->platform,
                    'ad_name' => $this->ad_name,
                    'campaign_name' => $this->campaign_name,
                    'lead_status' => $this->lead_status,
                    'call_duration' => $this->call_duration,
                    'call_recording_url' => $this->call_recording_url,
                    'download_status' => $this->download_status,
                ]);

                Log::info('Updated employee_delivery_call record', [
                    'call_id' => $this->id,
                    'delivery_id' => $existingSync->id
                ]);

                return $existingSync;
            } else {
                // Create new record
                $deliveryCall = EmployeeDeliveryCall::create([
                    'seller_id' => $this->seller_id,
                    'product_id' => $this->product_id,
                    'call_id' => $this->id,
                    'order_code' => $this->order_code,
                    'call_number' => $this->call_number,
                    'date' => $this->date,
                    'status_date' => $this->status_date,
                    'customer_name' => $this->customer_name,
                    'customer_phone_01' => $this->customer_phone_01,
                    'customer_phone_02' => $this->customer_phone_02,
                    'customer_address' => $this->customer_address,
                    'city' => $this->city,
                    'district' => $this->district,
                    'item_code' => $this->item_code,
                    'item_name' => $this->item_name,
                    'qty' => $this->qty,
                    'price' => $this->price,
                    'total' => $this->total,
                    'status' => $this->status,
                    'remark' => $this->remark,
                    'platform' => $this->platform,
                    'ad_name' => $this->ad_name,
                    'campaign_name' => $this->campaign_name,
                    'lead_status' => $this->lead_status,
                    'call_duration' => $this->call_duration,
                    'call_recording_url' => $this->call_recording_url,
                    'download_status' => $this->download_status,
                ]);

                Log::info('Created employee_delivery_call record', [
                    'call_id' => $this->id,
                    'delivery_id' => $deliveryCall->id
                ]);

                return $deliveryCall;
            }
        } catch (\Exception $e) {
            Log::error('Failed to sync to employee_delivery_call', [
                'call_id' => $this->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($call) {
            if (empty($call->call_number)) {
                $call->call_number = self::generateCallNumber();
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
        });

        static::updated(function ($call) {
            // Check if status is changing to Conform
            if ($call->isDirty('status') && $call->status === 'Conform') {
                $call->syncToEmployeeDelivery();
            }

            // Also sync if it's already Conform and other fields are updated
            if ($call->status === 'Conform' && $call->wasChanged()) {
                $call->syncToEmployeeDelivery();
            }
        });

        static::created(function ($call) {
            // If created with Conform status
            if ($call->status === 'Conform') {
                $call->syncToEmployeeDelivery();
            }
        });

        static::deleted(function ($call) {
            // Optionally delete from employee_delivery_call when call is deleted
            if ($call->status === 'Conform') {
                EmployeeDeliveryCall::where('call_id', $call->id)->delete();

                Log::info('Deleted employee_delivery_call record due to call deletion', [
                    'call_id' => $call->id
                ]);
            }
        });
    }
}
