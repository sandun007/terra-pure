<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\WholesalerProduct;

class WholesalerProductPolicy
{
    /**
     * Determine if employee can view any products
     */
    public function viewAny(Employee $employee): bool
    {
        return true;
    }

    /**
     * Determine if employee can view specific product
     */
    public function view(Employee $employee, WholesalerProduct $product): bool
    {
        return true;
    }

    /**
     * Determine if employee can update product prices
     */
    public function updatePrice(Employee $employee, WholesalerProduct $product): bool
    {
        // You can add role-based checks here if needed
        // return $employee->role === 'admin' || $employee->role === 'manager';
        return true;
    }

    /**
     * Employees cannot update other product details
     */
    public function update(Employee $employee, WholesalerProduct $product): bool
    {
        return false;
    }

    /**
     * Employees cannot create products
     */
    public function create(Employee $employee): bool
    {
        return false;
    }

    /**
     * Employees cannot delete products
     */
    public function delete(Employee $employee, WholesalerProduct $product): bool
    {
        return false;
    }

    /**
     * Employees cannot restore products
     */
    public function restore(Employee $employee, WholesalerProduct $product): bool
    {
        return false;
    }

    /**
     * Employees cannot permanently delete products
     */
    public function forceDelete(Employee $employee, WholesalerProduct $product): bool
    {
        return false;
    }
}
