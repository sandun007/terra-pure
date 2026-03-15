<?php
// app/Models/WholesalerOrderExchange.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WholesalerOrderExchange extends Model
{
    use HasFactory;

    protected $table = 'wholesaler_order_exchanges';

    protected $fillable = [
        'source_order_id',
        'source_order_item_id',
        'source_product_id',
        'source_wholesaler_id',
        'source_product_name',
        'source_product_code',
        'source_quantity',
        'source_price_per_unit',
        'source_total_price',
        'destination_order_id',
        'destination_order_item_id',
        'destination_product_id',
        'destination_wholesaler_id',
        'destination_product_name',
        'destination_product_code',
        'destination_quantity',
        'destination_price_per_unit',
        'destination_total_price',
        'exchange_type',
        'exchange_quantity',
        'price_difference',
        'adjustment_amount',
        'payment_method',
        'payment_status',
        'payment_reference',
        'payment_date',
        'exchange_number',
        'status',
        'employee_id',
        'approved_by',
        'approved_at',
        'notes',
        'reason',
        'exchange_data'
    ];

    protected $casts = [
        'exchange_data' => 'array',
        'payment_date' => 'datetime',
        'approved_at' => 'datetime',
        'source_price_per_unit' => 'decimal:2',
        'source_total_price' => 'decimal:2',
        'destination_price_per_unit' => 'decimal:2',
        'destination_total_price' => 'decimal:2',
        'price_difference' => 'decimal:2',
        'adjustment_amount' => 'decimal:2'
    ];

    // Relationships
    public function sourceOrder()
    {
        return $this->belongsTo(Order::class, 'source_order_id');
    }

    public function sourceOrderItem()
    {
        return $this->belongsTo(OrderItem::class, 'source_order_item_id');
    }

    public function sourceProduct()
    {
        return $this->belongsTo(Product::class, 'source_product_id');
    }

    public function sourceWholesaler()
    {
        return $this->belongsTo(Wholesaler::class, 'source_wholesaler_id');
    }

    public function destinationOrder()
    {
        return $this->belongsTo(Order::class, 'destination_order_id');
    }

    public function destinationOrderItem()
    {
        return $this->belongsTo(OrderItem::class, 'destination_order_item_id');
    }

    public function destinationProduct()
    {
        return $this->belongsTo(Product::class, 'destination_product_id');
    }

    public function destinationWholesaler()
    {
        return $this->belongsTo(Wholesaler::class, 'destination_wholesaler_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Generate Exchange Number
    public static function generateExchangeNumber()
    {
        $prefix = 'EXC';
        $year = date('Y');
        $month = date('m');
        $lastExchange = self::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastExchange) {
            $lastNumber = intval(substr($lastExchange->exchange_number, -4));
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return "{$prefix}-{$year}{$month}-{$newNumber}";
    }

    // Update Product Quantities
    public function updateQuantities()
    {
        $sourceProduct = $this->sourceProduct;
        $destProduct = $this->destinationProduct;

        // Reduce source product quantity
        $sourceProduct->decrement('quantity', $this->exchange_quantity);

        // Increase destination product quantity
        $destProduct->increment('quantity', $this->exchange_quantity);

        // Update order items if they exist
        if ($this->source_order_item_id) {
            $sourceItem = OrderItem::find($this->source_order_item_id);
            if ($sourceItem) {
                $sourceItem->decrement('quantity', $this->exchange_quantity);
            }
        }

        if ($this->destination_order_item_id) {
            $destItem = OrderItem::find($this->destination_order_item_id);
            if ($destItem) {
                $destItem->increment('quantity', $this->exchange_quantity);
            }
        }
    }
}
