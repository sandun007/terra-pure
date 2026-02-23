<?php
// app/Http/Middleware/EmployeeCanUpdatePrice.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeCanUpdatePrice
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('login');
        }

        $employee = Auth::guard('employee')->user();

        // Check if employee has permission to update prices
        // You can add role-based checks here if needed
        if (!$this->canUpdatePrices($employee)) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update product prices'
            ], 403);
        }

        return $next($request);
    }

    private function canUpdatePrices($employee)
    {
        // Add your logic here (e.g., check roles, permissions)
        return true; // Default all employees can update
    }
}
