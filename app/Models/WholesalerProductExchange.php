<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WholesalerProductExchange extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wholesaler_product_exchanges';

    protected $fillable = [
        'source_product_id',
        'source_wholesaler_id',
        'destination_product_id',
        'destination_wholesaler_id',
        'exchange_type',
        'exchange_quantity',
        'source_price_at_time',
        'destination_price_at_time',
        'price_difference',
        'adjustment_amount',
        'payment_method',
        'payment_status',
        'payment_date',
        'transaction_reference',
        'employee_id',
        'status',
        'reason',
        'notes',
        'exchange_data',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'exchange_quantity' => 'decimal:2',
        'source_price_at_time' => 'decimal:2',
        'destination_price_at_time' => 'decimal:2',
        'price_difference' => 'decimal:2',
        'adjustment_amount' => 'decimal:2',
        'exchange_data' => 'array',
        'payment_date' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the source product
     */
    public function sourceProduct()
    {
        return $this->belongsTo(WholesalerProduct::class, 'source_product_id');
    }

    /**
     * Get the source wholesaler
     */
    public function sourceWholesaler()
    {
        return $this->belongsTo(Wholesaler::class, 'source_wholesaler_id');
    }

    /**
     * Get the destination product
     */
    public function destinationProduct()
    {
        return $this->belongsTo(WholesalerProduct::class, 'destination_product_id');
    }

    /**
     * Get the destination wholesaler
     */
    public function destinationWholesaler()
    {
        return $this->belongsTo(Wholesaler::class, 'destination_wholesaler_id');
    }

    /**
     * Get the employee who performed the exchange
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the employee who approved the exchange
     */
    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by');
    }

    /**
     * Scope for pending exchanges
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved exchanges
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for completed exchanges
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for exchanges by source wholesaler
     */
    public function scopeBySourceWholesaler($query, $wholesalerId)
    {
        return $query->where('source_wholesaler_id', $wholesalerId);
    }

    /**
     * Scope for exchanges by destination wholesaler
     */
    public function scopeByDestinationWholesaler($query, $wholesalerId)
    {
        return $query->where('destination_wholesaler_id', $wholesalerId);
    }

    /**
     * Scope for exchanges involving a wholesaler (as source or destination)
     */
    public function scopeForWholesaler($query, $wholesalerId)
    {
        return $query->where('source_wholesaler_id', $wholesalerId)
            ->orWhere('destination_wholesaler_id', $wholesalerId);
    }

    /**
     * Calculate price difference
     */
    public function calculatePriceDifference(): float
    {
        return $this->destination_price_at_time - $this->source_price_at_time;
    }

    /**
     * Check if exchange is equal value
     */
    public function isEqualValue(): bool
    {
        return abs($this->price_difference) < 0.01;
    }

    /**
     * Check if source wholesaler needs to pay
     */
    public function sourceNeedsToPay(): bool
    {
        return $this->price_difference > 0;
    }

    /**
     * Check if destination wholesaler needs to pay
     */
    public function destinationNeedsToPay(): bool
    {
        return $this->price_difference < 0;
    }

    /**
     * Get amount payable by source
     */
    public function getSourcePayableAmount(): float
    {
        return $this->sourceNeedsToPay() ? $this->price_difference : 0;
    }

    /**
     * Get amount payable by destination
     */
    public function getDestinationPayableAmount(): float
    {
        return $this->destinationNeedsToPay() ? abs($this->price_difference) : 0;
    }

    /**
     * Complete the exchange
     */
    public function complete($approvedBy = null)
    {
        $this->status = 'completed';

        if ($approvedBy) {
            $this->approved_by = $approvedBy;
            $this->approved_at = now();
        }

        $this->save();

        // Update source product
        if ($this->sourceProduct) {
            $this->sourceProduct->updateExchangeStats($this);
        }

        // Update destination product
        if ($this->destinationProduct) {
            $this->destinationProduct->updateExchangeStats($this);
        }
    }

    /**
     * Approve the exchange
     */
    public function approve($employeeId)
    {
        $this->status = 'approved';
        $this->approved_by = $employeeId;
        $this->approved_at = now();
        $this->save();
    }

    /**
     * Reject the exchange
     */
    public function reject($reason = null)
    {
        $this->status = 'rejected';
        $this->reason = $reason ?? $this->reason;
        $this->save();
    }

    /**
     * Cancel the exchange
     */
    public function cancel($reason = null)
    {
        $this->status = 'cancelled';
        $this->reason = $reason ?? $this->reason;
        $this->save();
    }

    /**
     * Get formatted exchange summary
     */
    public function getSummaryAttribute(): string
    {
        return "{$this->sourceProduct->name} ({$this->sourceWholesaler->business_name}) → " .
            "{$this->destinationProduct->name} ({$this->destinationWholesaler->business_name})";
    }
}
