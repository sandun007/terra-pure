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
        'status',
        'quantity',
        'discount',
        'subtotal',
        'shipping',
        'total_amount',
        'payment_method',
        'payment_status',
        'file_path',
        'original_filename',
    ];

    protected $casts = [
        'order_date'        => 'date',
        'delivery_date'     => 'date',
        'rescheduling_date' => 'date',
        'return_date'       => 'date',
        'invoice_date'      => 'date',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'product_price'     => 'decimal:2',
        'subtotal'          => 'decimal:2',
        'shipping'          => 'decimal:2',
        'total_amount'      => 'decimal:2',
        'discount'          => 'decimal:2',
        'quantity'          => 'integer',
    ];

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(SellerProduct::class, 'product_id');
    }

    public function items()
    {
        return $this->hasMany(SellerOrderItem::class, 'seller_order_id');
    }

    // Scopes
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

    public function scopeByOrderNumber($query, $orderNumber)
    {
        return $query->where('order_number', $orderNumber)
            ->orWhere('order_id', $orderNumber);
    }

    public function getComputedSubtotalAttribute(): float
    {
        return (float) ($this->quantity * $this->product_price);
    }

    public function getDiscountAmountAttribute(): float
    {
        if ($this->discount > 0) {
            return round(($this->computedSubtotal * $this->discount) / 100, 2);
        }
        return 0.0;
    }

    public function getFinalTotalAttribute(): float
    {
        return $this->computedSubtotal - $this->discount_amount;
    }

    public static function generateOrderId(): string
    {
        $prefix = 'ORD/';

        if (!Schema::hasTable('seller_orders')) {
            return $prefix . '00001';
        }

        try {
            $lastOrder = DB::table('seller_orders')
                ->lockForUpdate()
                ->orderBy('id', 'desc')
                ->first(['id', 'order_id', 'order_number']);

            if ($lastOrder) {
                $lastNumber = 0;
                $source = $lastOrder->order_id ?? $lastOrder->order_number ?? '';

                if ($source && preg_match('/(\d+)$/', $source, $matches)) {
                    $lastNumber = (int) $matches[1];
                } elseif ($lastOrder->id) {
                    $lastNumber = (int) $lastOrder->id;
                }

                $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '00001';
            }

            return $prefix . $newNumber;

        } catch (\Exception $e) {
            \Log::error('Error generating order ID: ' . $e->getMessage());
            return $prefix . date('ymdHis');
        }
    }
}
