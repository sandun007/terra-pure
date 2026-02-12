<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SellerOrder extends Model
{
    use HasFactory;

    protected $table = 'seller_orders';

    protected $fillable = [
        'order_id',
        'order_number',
        'seller_id',
        'product_id',
        'product_name',
        'product_code',
        'product_price',
        'product_image',
        'customer_name',
        'customer_phone',
        'customer_address',
        'district',
        'platform',
        'call_status',
        'remark',
        'order_date',
        'delivery_date',
        'rescheduling_date',
        'return_date',
        'status',
        'quantity',
        'subtotal',
        'shipping',
        'total_amount',
        'payment_method',
        'payment_status',
        'shipping_address',
        'customer_email',
        'file_path',
        'original_filename'
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'rescheduling_date' => 'date',
        'return_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'product_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    /**
     * Relationship with seller (user)
     */
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    /**
     * Relationship with product
     */
    public function product()
    {
        return $this->belongsTo(SellerProduct::class, 'product_id');
    }

    /**
     * Relationship with order items
     */
    public function items()
    {
        return $this->hasMany(SellerOrderItem::class, 'order_id');
    }

    /**
     * Scope for different order statuses
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeComplete($query)
    {
        return $query->where('status', 'complete');
    }

    public function scopeReturn($query)
    {
        return $query->where('status', 'return');
    }

    public function scopeRescheduling($query)
    {
        return $query->where('status', 'rescheduling');
    }

    /**
     * Generate order ID with table existence check
     */
    public static function generateOrderId()
    {
        $prefix = 'ORD/';

        // Check if table exists before querying
        if (!Schema::hasTable('seller_orders')) {
            return $prefix . '00001';
        }

        try {
            $lastOrder = self::orderBy('id', 'desc')->first();

            if ($lastOrder) {
                // Extract number from order_id (e.g., ORD/00001 -> 1)
                $lastNumber = 0;

                if (isset($lastOrder->order_id) && preg_match('/\d+$/', $lastOrder->order_id, $matches)) {
                    $lastNumber = (int) $matches[0];
                } elseif (isset($lastOrder->order_number) && preg_match('/\d+$/', $lastOrder->order_number, $matches)) {
                    $lastNumber = (int) $matches[0];
                } else {
                    $lastNumber = $lastOrder->id ?? 0;
                }

                $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '00001';
            }

            return $prefix . $newNumber;
        } catch (\Exception $e) {
            \Log::error('Error generating order ID: ' . $e->getMessage());
            return $prefix . '00001';
        }
    }

    /**
     * Get order by order number
     */
    public function scopeByOrderNumber($query, $orderNumber)
    {
        return $query->where('order_number', $orderNumber)
            ->orWhere('order_id', $orderNumber);
    }
}
