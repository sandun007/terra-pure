<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use App\Models\WholesalerPayment;
use App\Models\WholesalerPaymentMethod;
use App\Models\WholesalerOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WholesalerPaymentController extends Controller
{
    /**
     * Display payment dashboard
     */
    public function index()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            // Payment statistics
            $totalEarnings = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->where('type', 'credit')
                ->where('status', 'completed')
                ->sum('amount');

            $pendingWithdrawals = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->where('type', 'debit')
                ->where('status', 'pending')
                ->sum('amount');

            $totalWithdrawals = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->where('type', 'debit')
                ->where('status', 'completed')
                ->sum('amount');

            $netBalance = $totalEarnings - $totalWithdrawals - $pendingWithdrawals;
            $availableBalance = max(0, $netBalance);

            // Recent payments
            $recentPayments = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Payment summary
            $paymentSummary = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->select('type', 'status', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('type', 'status')
                ->get();

            return view('wholesaler.payments.index', compact(
                'totalEarnings',
                'pendingWithdrawals',
                'totalWithdrawals',
                'availableBalance',
                'recentPayments',
                'paymentSummary'
            ));

        } catch (\Exception $e) {
            \Log::error('Payment index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading payments: ' . $e->getMessage());
        }
    }

    /**
     * Display payment history
     */
    public function paymentHistory(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $query = WholesalerPayment::where('wholesaler_id', $wholesaler->id);

            // Apply filters
            if ($request->has('type') && $request->type != '') {
                $query->where('type', $request->type);
            }

            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            if ($request->has('payment_type') && $request->payment_type != '') {
                $query->where('payment_type', $request->payment_type);
            }

            if ($request->has('date_from') && $request->date_from != '') {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to != '') {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $payments = $query->orderBy('created_at', 'desc')->paginate(20);

            return view('wholesaler.payments.history', compact('payments'));

        } catch (\Exception $e) {
            \Log::error('Payment history error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading payment history: ' . $e->getMessage());
        }
    }

    /**
     * Show payment details
     */
    public function show($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $payment = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            return view('wholesaler.payments.show', compact('payment'));

        } catch (\Exception $e) {
            \Log::error('Show payment error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment not found: ' . $e->getMessage());
        }
    }

    /**
     * Display payment methods
     */
    public function paymentMethods()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $paymentMethods = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return view('wholesaler.payments.methods', compact('paymentMethods'));

        } catch (\Exception $e) {
            \Log::error('Payment methods error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading payment methods: ' . $e->getMessage());
        }
    }

    /**
     * Create payment method
     */
    public function createPaymentMethod()
    {
        return view('wholesaler.payments.create-method');
    }

    /**
     * Store payment method
     */
    public function storePaymentMethod(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'method_type' => 'required|in:bank,mobile_money',
                'account_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:255',
                'bank_name' => 'required_if:method_type,bank|string|max:255',
                'branch' => 'nullable|string|max:255',
                'mobile_provider' => 'required_if:method_type,mobile_money|string|max:255',
                'is_default' => 'boolean',
            ]);

            // If setting as default, remove default from other methods
            if ($request->has('is_default') && $request->is_default) {
                WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                    ->update(['is_default' => false]);
            }

            WholesalerPaymentMethod::create([
                'wholesaler_id' => $wholesaler->id,
                'method_type' => $validated['method_type'],
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'],
                'bank_name' => $validated['bank_name'] ?? null,
                'branch' => $validated['branch'] ?? null,
                'mobile_provider' => $validated['mobile_provider'] ?? null,
                'is_default' => $request->has('is_default') ? $request->is_default : false,
                'status' => 'active',
            ]);

            return redirect()->route('wholesaler.payments.methods')
                ->with('success', 'Payment method added successfully!');

        } catch (\Exception $e) {
            \Log::error('Store payment method error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating payment method: ' . $e->getMessage());
        }
    }

    /**
     * Edit payment method
     */
    public function editPaymentMethod($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            return view('wholesaler.payments.edit-method', compact('paymentMethod'));

        } catch (\Exception $e) {
            \Log::error('Edit payment method error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment method not found: ' . $e->getMessage());
        }
    }

    /**
     * Update payment method
     */
    public function updatePaymentMethod(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $validated = $request->validate([
                'method_type' => 'required|in:bank,mobile_money',
                'account_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:255',
                'bank_name' => 'required_if:method_type,bank|string|max:255',
                'branch' => 'nullable|string|max:255',
                'mobile_provider' => 'required_if:method_type,mobile_money|string|max:255',
                'is_default' => 'boolean',
            ]);

            // If setting as default, remove default from other methods
            if ($request->has('is_default') && $request->is_default) {
                WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                    ->where('id', '!=', $id)
                    ->update(['is_default' => false]);
            }

            $paymentMethod->update([
                'method_type' => $validated['method_type'],
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'],
                'bank_name' => $validated['bank_name'] ?? null,
                'branch' => $validated['branch'] ?? null,
                'mobile_provider' => $validated['mobile_provider'] ?? null,
                'is_default' => $request->has('is_default') ? $request->is_default : $paymentMethod->is_default,
            ]);

            return redirect()->route('wholesaler.payments.methods')
                ->with('success', 'Payment method updated successfully!');

        } catch (\Exception $e) {
            \Log::error('Update payment method error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating payment method: ' . $e->getMessage());
        }
    }

    /**
     * Destroy payment method
     */
    public function destroyPaymentMethod($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            if ($paymentMethod->is_default) {
                return redirect()->back()->with('error', 'Cannot delete default payment method.');
            }

            $paymentMethod->delete();

            return redirect()->route('wholesaler.payments.methods')
                ->with('success', 'Payment method deleted successfully!');

        } catch (\Exception $e) {
            \Log::error('Destroy payment method error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting payment method: ' . $e->getMessage());
        }
    }

    /**
     * Display withdraw page
     */
    public function withdraw()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $paymentMethods = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->get();

            $availableBalance = $this->calculateAvailableBalance($wholesaler->id);

            $minWithdrawal = 100;
            $maxWithdrawal = $availableBalance;

            return view('wholesaler.payments.withdraw', compact(
                'paymentMethods',
                'availableBalance',
                'minWithdrawal',
                'maxWithdrawal'
            ));

        } catch (\Exception $e) {
            \Log::error('Withdraw page error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading withdrawal page: ' . $e->getMessage());
        }
    }

    /**
     * Process withdrawal
     */
    public function processWithdraw(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'amount' => 'required|numeric|min:100',
                'payment_method_id' => 'required|exists:wholesaler_payment_methods,id',
                'notes' => 'nullable|string|max:500',
            ]);

            $availableBalance = $this->calculateAvailableBalance($wholesaler->id);

            if ($validated['amount'] > $availableBalance) {
                return redirect()->back()->with('error',
                    'Insufficient balance. Available: Rs. ' . number_format($availableBalance, 2)
                );
            }

            $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($validated['payment_method_id']);

            DB::transaction(function () use ($wholesaler, $validated, $paymentMethod) {
                WholesalerPayment::create([
                    'wholesaler_id' => $wholesaler->id,
                    'transaction_id' => 'WDR' . time() . strtoupper(uniqid()),
                    'amount' => $validated['amount'],
                    'type' => 'debit',
                    'payment_type' => 'withdrawal',
                    'payment_method' => $paymentMethod->method_type . ' - ' . $paymentMethod->account_number,
                    'status' => 'pending',
                    'description' => 'Withdrawal request',
                    'notes' => $validated['notes'],
                    'metadata' => [
                        'payment_method_id' => $paymentMethod->id,
                        'account_details' => [
                            'account_name' => $paymentMethod->account_name,
                            'account_number' => $paymentMethod->account_number,
                            'method_type' => $paymentMethod->method_type,
                        ]
                    ]
                ]);
            });

            return redirect()->route('wholesaler.payments.index')
                ->with('success', 'Withdrawal request submitted successfully! It will be processed within 24-48 hours.');

        } catch (\Exception $e) {
            \Log::error('Process withdraw error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error processing withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Cancel withdrawal
     */
    public function cancelWithdraw($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $withdrawal = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->where('id', $id)
                ->where('type', 'debit')
                ->where('status', 'pending')
                ->firstOrFail();

            $withdrawal->update(['status' => 'cancelled']);

            return redirect()->back()->with('success', 'Withdrawal request cancelled successfully!');

        } catch (\Exception $e) {
            \Log::error('Cancel withdraw error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error cancelling withdrawal: ' . $e->getMessage());
        }
    }

    /**
     * Export payments
     */
    public function exportPayments(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $payments = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->when($request->date_from, function($query) use ($request) {
                    return $query->whereDate('created_at', '>=', $request->date_from);
                })
                ->when($request->date_to, function($query) use ($request) {
                    return $query->whereDate('created_at', '<=', $request->date_to);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $fileName = 'payments-' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=$fileName",
            ];

            $callback = function() use ($payments) {
                $file = fopen('php://output', 'w');

                // Add headers
                fputcsv($file, [
                    'Date',
                    'Transaction ID',
                    'Type',
                    'Amount',
                    'Payment Type',
                    'Status',
                    'Description'
                ]);

                // Add data
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->created_at->format('Y-m-d H:i:s'),
                        $payment->transaction_id,
                        ucfirst($payment->type),
                        'Rs. ' . number_format($payment->amount, 2),
                        ucfirst(str_replace('_', ' ', $payment->payment_type)),
                        ucfirst($payment->status),
                        $payment->description
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            \Log::error('Export payments error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting payments: ' . $e->getMessage());
        }
    }

    /**
     * Calculate available balance
     */
    private function calculateAvailableBalance($wholesalerId)
    {
        $totalCredits = WholesalerPayment::where('wholesaler_id', $wholesalerId)
            ->where('type', 'credit')
            ->where('status', 'completed')
            ->sum('amount');

        $totalCompletedDebits = WholesalerPayment::where('wholesaler_id', $wholesalerId)
            ->where('type', 'debit')
            ->where('status', 'completed')
            ->sum('amount');

        $pendingDebits = WholesalerPayment::where('wholesaler_id', $wholesalerId)
            ->where('type', 'debit')
            ->where('status', 'pending')
            ->sum('amount');

        $available = $totalCredits - $totalCompletedDebits - $pendingDebits;

        return max(0, $available);
    }
}
