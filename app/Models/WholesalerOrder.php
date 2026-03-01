<?php
// app/Models/WholesalerOrder.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WholesalerOrder extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wholesaler_orders';

    protected $fillable = [
        'order_number',
        'wholesaler_id',
        'product_id',
        'product_code',
        'product_name',
        'product_description',
        'product_category',
        'product_price',
        'product_image',
        'quantity',
        'customer_name',
        'customer_phone',
        'customer_phone_2',
        'customer_email',
        'customer_address',
        'district',
        'platform',
        'call_status',
        'remark',
        'order_date',
        'delivery_date',
        'rescheduling_date',
        'return_date',
        'invoice_no',
        'invoice_date',
        'subtotal',
        'discount',
        'shipping',
        'total_amount',
        'payment_method',
        'payment_status',
        'refund_status',
        'refund_amount',
        'refunded_at',
        'refunded_by',
        'cancelled_by',
        'refund_notes',
        'status',
        'file_path',
        'original_filename'
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'rescheduling_date' => 'date',
        'return_date' => 'date',
        'invoice_date' => 'date',
        'refunded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'product_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'quantity' => 'integer'
    ];

    /**
     * Get the wholesaler that owns the order
     */
    public function wholesaler()
    {
        return $this->belongsTo(Wholesaler::class);
    }

    /**
     * Get the product associated with the order
     */
    public function product()
    {
        return $this->belongsTo(WholesalerProduct::class, 'product_id');
    }

    /**
     * Get the order items
     */
    public function items()
    {
        return $this->hasMany(WholesalerOrderItem::class, 'wholesaler_order_id');
    }

    /**
     * Scope for pending orders
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed orders
     */
    public function scopeComplete($query)
    {
        return $query->where('status', 'complete');
    }

    /**
     * Scope for returned orders
     */
    public function scopeReturn($query)
    {
        return $query->where('status', 'return');
    }

    /**
     * Scope for rescheduling orders
     */
    public function scopeRescheduling($query)
    {
        return $query->where('status', 'rescheduling');
    }

    /**
     * Generate unique order ID
     */
    public static function generateOrderId(): string
    {
        $prefix = 'ORD/';

        if (!Schema::hasTable('wholesaler_orders')) {
            return $prefix . date('ymd') . '/00001';
        }

        try {
            $lastOrder = DB::table('wholesaler_orders')
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first(['id', 'order_number']);

            if ($lastOrder && $lastOrder->order_number) {
                // Extract the numeric part from the last order number
                $parts = explode('/', $lastOrder->order_number);
                $lastNumber = (int) end($parts);
                $newNumber = $lastNumber + 1;
            } else {
                // Get the highest ID if no order number exists
                $lastOrder = DB::table('wholesaler_orders')
                    ->orderBy('id', 'desc')
                    ->first();
                $newNumber = ($lastOrder ? $lastOrder->id : 0) + 1;
            }

            // Format: ORD/YYMMDD/XXXXX
            return $prefix . date('ymd') . '/' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        } catch (\Exception $e) {
            \Log::error('Error generating order ID: ' . $e->getMessage());
            return $prefix . date('ymdHis');
        }
    }

    /**
     * Get the subtotal amount
     */
    public function getSubtotalAttribute($value)
    {
        return $value ?? ($this->quantity * $this->product_price);
    }

    /**
     * Get the total amount
     */
    public function getTotalAmountAttribute($value)
    {
        return $value ?? ($this->subtotal - ($this->discount ?? 0) + ($this->shipping ?? 0));
    }

    /**
     * Get customer full name
     */
    public function getCustomerFullNameAttribute()
    {
        return $this->customer_name;
    }

    /**
     * Get customer primary phone
     */
    public function getCustomerPrimaryPhoneAttribute()
    {
        return $this->customer_phone;
    }

    /**
     * Get customer secondary phone
     */
    public function getCustomerSecondaryPhoneAttribute()
    {
        return $this->customer_phone_2;
    }

    /**
     * Get all customer phones as array
     */
    public function getCustomerPhonesAttribute(): array
    {
        $phones = [$this->customer_phone];
        if ($this->customer_phone_2) {
            $phones[] = $this->customer_phone_2;
        }
        return $phones;
    }
}
