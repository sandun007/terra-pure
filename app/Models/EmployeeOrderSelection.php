<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeOrderSelection extends Model
{
    use HasFactory;

    protected $table = 'employee_order_selections';

    protected $fillable = [
        'employee_id',
        'order_id',
        'selected_at'
    ];

    protected $casts = [
        'selected_at' => 'datetime'
    ];

    /**
     * Get the employee that selected the order
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the selected order
     */
    public function order()
    {
        return $this->belongsTo(WholesalerOrder::class);
    }

    /**
     * Scope for current employee
     */
    public function scopeForCurrentEmployee($query)
    {
        return $query->where('employee_id', auth()->guard('employee')->id());
    }
}
