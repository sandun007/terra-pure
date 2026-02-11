<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema; // Add this import
use Illuminate\Support\Facades\DB;
use App\Models\User; // Add this import

class SellerOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_no',
        'invoice_date',
        'order_date',
        'delivery_date',
        'rescheduling_date',
        'return_date',
        'platform',
        'customer_name',
        'customer_phone',
        'customer_address',
        'district',
        'remark',
        'total_amount',
        'status',
        'seller_id',
        'file_path',
        'original_filename'
    ];

    protected $casts = [
        'order_date' => 'date',
        'invoice_date' => 'date',
        'delivery_date' => 'date',
        'rescheduling_date' => 'date',
        'return_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    // Relationship with seller (user)
    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    // Scope for different order statuses
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

    // Check if seller can place order (deposit check)
    public function canPlaceOrder($seller)
    {
        return $seller->deposit > 0;
    }

    // Generate order ID with table existence check
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
                } else {
                    $lastNumber = $lastOrder->id;
                }
                $newNumber = str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '00001';
            }

            return $prefix . $newNumber;
        } catch (\Exception $e) {
            // Fallback in case of any error
            \Log::error('Error generating order ID: ' . $e->getMessage());
            return $prefix . '00001';
        }
    }
}
