<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPrintLog extends Model
{
    use HasFactory;

    protected $table = 'order_print_logs';

    protected $fillable = [
        'order_id',
        'employee_id',
        'order_type',
        'printed_at',
        'print_count',
        'notes'
    ];

    protected $casts = [
        'printed_at' => 'datetime',
        'print_count' => 'integer'
    ];

    /**
     * Get the order associated with the print log
     */
    public function order()
    {
        return $this->belongsTo(WholesalerOrder::class, 'order_id');
    }

    /**
     * Get the employee who printed the order
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Scope for wholesaler orders
     */
    public function scopeWholesaler($query)
    {
        return $query->where('order_type', 'wholesaler');
    }

    /**
     * Scope for seller orders
     */
    public function scopeSeller($query)
    {
        return $query->where('order_type', 'seller');
    }

    /**
     * Scope for recent prints
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('printed_at', '>=', now()->subDays($days));
    }

    /**
     * Scope for specific employee
     */
    public function scopeByEmployee($query, $employeeId)
    {
        return $query->where('employee_id', $employeeId);
    }

    /**
     * Scope for specific order
     */
    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    /**
     * Get total prints count
     */
    public static function totalPrints($type = null)
    {
        $query = self::query();

        if ($type) {
            $query->where('order_type', $type);
        }

        return $query->sum('print_count');
    }

    /**
     * Get prints by date range
     */
    public static function printsByDateRange($startDate, $endDate, $type = null)
    {
        $query = self::whereBetween('printed_at', [$startDate, $endDate]);

        if ($type) {
            $query->where('order_type', $type);
        }

        return $query->get();
    }

    /**
     * Get employee print statistics
     */
    public static function employeePrintStats($employeeId = null)
    {
        $query = self::query();

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        return [
            'total_prints' => $query->sum('print_count'),
            'total_orders' => $query->count(),
            'avg_prints_per_order' => $query->count() > 0 ?
                $query->sum('print_count') / $query->count() : 0,
            'recent_prints' => $query->recent()->count()
        ];
    }

    /**
     * Log a print action
     */
    public static function logPrint($orderId, $employeeId, $orderType = 'wholesaler', $printCount = 1, $notes = null)
    {
        return self::create([
            'order_id' => $orderId,
            'employee_id' => $employeeId,
            'order_type' => $orderType,
            'printed_at' => now(),
            'print_count' => $printCount,
            'notes' => $notes
        ]);
    }

    /**
     * Check if order was printed today
     */
    public static function wasPrintedToday($orderId)
    {
        return self::where('order_id', $orderId)
            ->whereDate('printed_at', today())
            ->exists();
    }

    /**
     * Get print history for an order
     */
    public static function getPrintHistory($orderId)
    {
        return self::with('employee')
            ->where('order_id', $orderId)
            ->orderBy('printed_at', 'desc')
            ->get();
    }

    /**
     * Get today's print count
     */
    public static function todaysPrintCount($type = null)
    {
        $query = self::whereDate('printed_at', today());

        if ($type) {
            $query->where('order_type', $type);
        }

        return $query->sum('print_count');
    }
}
