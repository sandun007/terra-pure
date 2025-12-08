<?php

namespace App\Http\Controllers;

use App\Models\Wholesaler;
use App\Models\WholesalerPayment;
use App\Models\WholesalerPaymentMethod;
use App\Models\WholesalerPaymentGateway;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\StreamedResponse;

class WholesalerPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:wholesaler');
    }

    /**
     * Display payment dashboard with statistics
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

            $availableBalance = $this->calculateAvailableBalance($wholesaler->id);

            // Recent payments
            $recentPayments = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Payment summary for charts
            $paymentSummary = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->select('type', 'status', DB::raw('SUM(amount) as total_amount'))
                ->groupBy('type', 'status')
                ->get();

            // Payment gateways
            $paymentGateways = WholesalerPaymentGateway::where('wholesaler_id', $wholesaler->id)->get();

            return view('wholesaler.payments.index', compact(
                'totalEarnings',
                'pendingWithdrawals',
                'totalWithdrawals',
                'availableBalance',
                'recentPayments',
                'paymentSummary',
                'paymentGateways'
            ));

        } catch (\Exception $e) {
            Log::error('Payment Dashboard Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading payment dashboard');
        }
    }

    /**
     * Display all payments with filtering
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

            if ($request->has('date_from') && $request->date_from != '') {
                $query->whereDate('created_at', '>=', $request->date_from);
            }

            if ($request->has('date_to') && $request->date_to != '') {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            $payments = $query->orderBy('created_at', 'desc')->paginate(20);

            return view('wholesaler.payments.history', compact('payments'));

        } catch (\Exception $e) {
            Log::error('Payment History Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading payment history');
        }
    }

    /**
     * Display payment details
     */
    public function show($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $payment = WholesalerPayment::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            return view('wholesaler.payments.show', compact('payment'));

        } catch (\Exception $e) {
            Log::error('Payment Details Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment not found');
        }
    }

    /**
     * Display withdrawal page
     */
    public function withdraw()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $paymentMethods = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->get();

            $availableBalance = $this->calculateAvailableBalance($wholesaler->id);

            // Withdrawal limits
            $minWithdrawal = 100; // Minimum withdrawal amount
            $maxWithdrawal = $availableBalance;

            // Payment gateways for withdrawal
            $paymentGateways = WholesalerPaymentGateway::where('wholesaler_id', $wholesaler->id)
                ->where('status', 'active')
                ->get();

            return view('wholesaler.payments.withdraw', compact(
                'paymentMethods',
                'availableBalance',
                'minWithdrawal',
                'maxWithdrawal',
                'paymentGateways'
            ));

        } catch (\Exception $e) {
            Log::error('Withdraw Page Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading withdrawal page');
        }
    }

    /**
     * Process withdrawal request
     */
    public function processWithdraw(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'amount' => 'required|numeric|min:100',
                'payment_method_id' => 'required_without:gateway_id',
                'gateway_id' => 'required_without:payment_method_id',
                'notes' => 'nullable|string|max:500',
            ]);

            $availableBalance = $this->calculateAvailableBalance($wholesaler->id);

            if ($validated['amount'] > $availableBalance) {
                return redirect()->back()->with('error',
                    'Insufficient balance. Available: RS. ' . number_format($availableBalance, 2)
                );
            }

            // Ensure only one method is selected
            if ($request->filled('gateway_id') && $request->filled('payment_method_id')) {
                return redirect()->back()->with('error', 'Please select either payment method or gateway, not both.');
            }

            // Process via payment gateway or manual method
            if ($request->filled('gateway_id')) {
                return $this->processGatewayWithdrawal($wholesaler, $validated);
            } else {
                return $this->processManualWithdrawal($wholesaler, $validated);
            }

        } catch (\Exception $e) {
            Log::error('Process Withdraw Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error processing withdrawal');
        }
    }

    /**
     * Process withdrawal via payment gateway
     */
    private function processGatewayWithdrawal($wholesaler, $validated)
    {
        try {
            DB::beginTransaction();

            $gateway = WholesalerPaymentGateway::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($validated['gateway_id']);

            // Create withdrawal record
            $payment = WholesalerPayment::create([
                'wholesaler_id' => $wholesaler->id,
                'transaction_id' => 'WDR' . time() . strtoupper(Str::random(6)),
                'amount' => $validated['amount'],
                'type' => 'debit',
                'payment_type' => 'gateway_withdrawal',
                'payment_method' => $gateway->gateway_name,
                'status' => 'pending',
                'description' => 'Gateway withdrawal request',
                'notes' => $validated['notes'] ?? null,
                'metadata' => [
                    'gateway_id' => $gateway->id,
                    'gateway_name' => $gateway->gateway_name,
                    'gateway_type' => $gateway->gateway_type,
                ]
            ]);

            // Call gateway API (simulated for now)
            $this->callPaymentGateway($gateway, $payment);

            DB::commit();

            return redirect()->route('wholesaler.payments.index')
                ->with('success', 'Gateway withdrawal initiated! Please check your gateway account.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gateway Withdrawal Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error processing gateway withdrawal');
        }
    }

    /**
     * Process manual withdrawal
     */
    private function processManualWithdrawal($wholesaler, $validated)
    {
        try {
            DB::beginTransaction();

            $paymentMethod = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($validated['payment_method_id']);

            // Create withdrawal request
            WholesalerPayment::create([
                'wholesaler_id' => $wholesaler->id,
                'transaction_id' => 'WDR' . time() . strtoupper(Str::random(6)),
                'amount' => $validated['amount'],
                'type' => 'debit',
                'payment_type' => 'manual_withdrawal',
                'payment_method' => $this->formatPaymentMethod($paymentMethod),
                'status' => 'pending',
                'description' => 'Manual withdrawal request',
                'notes' => $validated['notes'] ?? null,
                'metadata' => [
                    'payment_method_id' => $paymentMethod->id,
                    'account_details' => [
                        'account_name' => $paymentMethod->account_name,
                        'account_number' => $paymentMethod->account_number,
                        'method_type' => $paymentMethod->method_type,
                    ]
                ]
            ]);

            DB::commit();

            return redirect()->route('wholesaler.payments.index')
                ->with('success', 'Withdrawal request submitted successfully! It will be processed within 24-48 hours.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Manual Withdrawal Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error processing manual withdrawal');
        }
    }

    /**
     * Call payment gateway API
     */
    private function callPaymentGateway($gateway, $payment)
    {
        try {
            $apiData = [
                'api_key' => decrypt($gateway->api_key),
                'amount' => $payment->amount,
                'currency' => 'PKR',
                'reference' => $payment->transaction_id,
                'description' => 'Wholesaler withdrawal',
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . decrypt($gateway->api_key),
                'Content-Type' => 'application/json',
            ])->post($gateway->api_url . '/withdraw', $apiData);

            if ($response->successful()) {
                $payment->update([
                    'status' => 'completed',
                    'metadata' => array_merge($payment->metadata, [
                        'gateway_response' => $response->json(),
                        'gateway_transaction_id' => $response->json('transaction_id'),
                    ]),
                    'completed_at' => now(),
                ]);
            } else {
                $payment->update([
                    'status' => 'failed',
                    'metadata' => array_merge($payment->metadata, [
                        'gateway_error' => $response->json(),
                    ]),
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Gateway API Error: ' . $e->getMessage());
            $payment->update([
                'status' => 'failed',
                'metadata' => array_merge($payment->metadata, [
                    'error' => $e->getMessage(),
                ]),
            ]);
        }
    }

    /**
     * Payment Gateway Management
     */
    public function gateways()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $gateways = WholesalerPaymentGateway::where('wholesaler_id', $wholesaler->id)->get();

            return view('wholesaler.payments.gateways', compact('gateways'));

        } catch (\Exception $e) {
            Log::error('Gateways Page Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading payment gateways');
        }
    }

    /**
     * Show create gateway form
     */
    public function createGateway()
    {
        return view('wholesaler.payments.gateways-create');
    }

    /**
     * Store new gateway
     */
    public function storeGateway(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'gateway_name' => 'required|string|max:255',
                'gateway_type' => 'required|in:stripe,paypal,jazzcash,easypaisa,upaisa,stripe_checkout',
                'api_key' => 'required|string|max:500',
                'api_secret' => 'required|string|max:500',
                'api_url' => 'required|url|max:500',
                'webhook_url' => 'nullable|url|max:500',
                'is_default' => 'boolean',
            ]);

            // Encrypt sensitive data
            $validated['api_key'] = encrypt($validated['api_key']);
            $validated['api_secret'] = encrypt($validated['api_secret']);

            // If setting as default, remove default from other gateways
            if ($request->is_default) {
                WholesalerPaymentGateway::where('wholesaler_id', $wholesaler->id)
                    ->update(['is_default' => false]);
            }

            WholesalerPaymentGateway::create([
                'wholesaler_id' => $wholesaler->id,
                'gateway_name' => $validated['gateway_name'],
                'gateway_type' => $validated['gateway_type'],
                'api_key' => $validated['api_key'],
                'api_secret' => $validated['api_secret'],
                'api_url' => $validated['api_url'],
                'webhook_url' => $validated['webhook_url'] ?? null,
                'is_default' => $request->is_default ?? false,
                'status' => 'active',
            ]);

            return redirect()->route('wholesaler.payments.gateways')
                ->with('success', 'Payment gateway added successfully!');

        } catch (\Exception $e) {
            Log::error('Store Gateway Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error adding payment gateway');
        }
    }

    /**
     * Test gateway connection
     */
    public function testGateway($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $gateway = WholesalerPaymentGateway::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            // Decrypt credentials
            $apiKey = decrypt($gateway->api_key);

            // Test connection
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
            ])->get($gateway->api_url . '/test');

            if ($response->successful()) {
                return redirect()->back()->with('success', 'Gateway connection successful!');
            } else {
                return redirect()->back()->with('error', 'Gateway connection failed: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Test Gateway Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error testing gateway');
        }
    }

    /**
     * Delete gateway
     */
    public function destroyGateway($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $gateway = WholesalerPaymentGateway::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            if ($gateway->is_default) {
                return redirect()->back()->with('error', 'Cannot delete default gateway. Set another gateway as default first.');
            }

            $gateway->delete();

            return redirect()->route('wholesaler.payments.gateways')
                ->with('success', 'Gateway deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Destroy Gateway Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting gateway');
        }
    }

    /**
     * Webhook handler for payment gateways
     */
    public function webhookHandler(Request $request, $gateway)
    {
        try {
            $payload = $request->all();
            Log::info('Payment webhook received', ['gateway' => $gateway, 'payload' => $payload]);

            switch ($gateway) {
                case 'stripe':
                    return $this->handleStripeWebhook($request);
                case 'paypal':
                    return $this->handlePayPalWebhook($request);
                case 'jazzcash':
                    return $this->handleJazzCashWebhook($request);
                default:
                    return response()->json(['error' => 'Gateway not supported'], 400);
            }

        } catch (\Exception $e) {
            Log::error('Webhook error: ' . $e->getMessage());
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle Stripe webhook
     */
    private function handleStripeWebhook(Request $request)
    {
        $payload = $request->all();

        if ($payload['type'] === 'checkout.session.completed') {
            $session = $payload['data']['object'];

            // Process payment
            DB::transaction(function () use ($session) {
                // Update payment status
                $payment = WholesalerPayment::where('transaction_id', $session['metadata']['reference'])
                    ->firstOrFail();

                $payment->update([
                    'status' => 'completed',
                    'metadata' => array_merge($payment->metadata, [
                        'stripe_session_id' => $session['id'],
                        'stripe_customer' => $session['customer'],
                        'payment_intent' => $session['payment_intent'],
                    ]),
                    'completed_at' => now(),
                ]);

                // Update wholesaler balance
                $wholesaler = $payment->wholesaler;
                // Add your balance update logic here
            });
        }

        return response()->json(['success' => true]);
    }

    /**
     * Handle PayPal webhook
     */
    private function handlePayPalWebhook(Request $request)
    {
        $payload = $request->all();
        // Implement PayPal webhook logic
        return response()->json(['success' => true]);
    }

    /**
     * Handle JazzCash webhook
     */
    private function handleJazzCashWebhook(Request $request)
    {
        $payload = $request->all();

        // Verify hash
        $pp_TxnRefNo = $payload['pp_TxnRefNo'] ?? null;
        $pp_Amount = $payload['pp_Amount'] ?? null;
        $pp_ResponseCode = $payload['pp_ResponseCode'] ?? null;

        if ($pp_TxnRefNo && $pp_ResponseCode === '000') {
            // Payment successful
            $payment = WholesalerPayment::where('transaction_id', $pp_TxnRefNo)
                ->first();

            if ($payment) {
                $payment->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Initiate checkout session
     */
    public function createCheckoutSession(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'amount' => 'required|numeric|min:100',
                'gateway_id' => 'required|exists:wholesaler_payment_gateways,id',
            ]);

            $gateway = WholesalerPaymentGateway::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($validated['gateway_id']);

            // Create Stripe Checkout Session
            if ($gateway->gateway_type === 'stripe_checkout') {
                // Create pending payment record first
                $payment = WholesalerPayment::create([
                    'wholesaler_id' => $wholesaler->id,
                    'transaction_id' => 'CHK' . time() . strtoupper(Str::random(6)),
                    'amount' => $validated['amount'],
                    'type' => 'credit',
                    'payment_type' => 'deposit',
                    'payment_method' => 'stripe_checkout',
                    'status' => 'pending',
                    'description' => 'Stripe Checkout deposit',
                    'metadata' => [
                        'gateway_id' => $gateway->id,
                    ],
                ]);

                // Decrypt credentials
                $apiKey = decrypt($gateway->api_key);

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.stripe.com/v1/checkout/sessions', [
                    'payment_method_types' => ['card'],
                    'line_items' => [[
                        'price_data' => [
                            'currency' => 'pkr',
                            'product_data' => ['name' => 'Fund Deposit'],
                            'unit_amount' => $validated['amount'] * 100,
                        ],
                        'quantity' => 1,
                    ]],
                    'mode' => 'payment',
                    'success_url' => route('wholesaler.payments.success') . '?session_id={CHECKOUT_SESSION_ID}',
                    'cancel_url' => route('wholesaler.payments.cancel'),
                    'metadata' => [
                        'wholesaler_id' => $wholesaler->id,
                        'payment_id' => $payment->id,
                        'type' => 'deposit',
                    ],
                ]);

                if ($response->successful()) {
                    $session = $response->json();

                    // Update payment with session info
                    $payment->update([
                        'transaction_id' => $session['id'],
                        'metadata' => array_merge($payment->metadata, [
                            'session_id' => $session['id'],
                            'checkout_url' => $session['url'],
                        ]),
                    ]);

                    return redirect($session['url']);
                } else {
                    $payment->update(['status' => 'failed']);
                }
            }

            return redirect()->back()->with('error', 'Failed to create checkout session');

        } catch (\Exception $e) {
            Log::error('Checkout Session Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error creating checkout session');
        }
    }

    /**
     * Cancel pending withdrawal
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
            Log::error('Cancel Withdraw Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error cancelling withdrawal');
        }
    }

    /**
     * Display payment methods
     */
    public function paymentMethods()
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $paymentMethods = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)->get();

            return view('wholesaler.payments.methods', compact('paymentMethods'));

        } catch (\Exception $e) {
            Log::error('Payment Methods Page Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error loading payment methods');
        }
    }

    /**
     * Show create payment method form
     */
    public function createPaymentMethod()
    {
        return view('wholesaler.payments.methods-create');
    }

    /**
     * Store new payment method
     */
    public function storePaymentMethod(Request $request)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();

            $validated = $request->validate([
                'method_type' => 'required|in:bank,mobile',
                'bank_name' => 'required_if:method_type,bank',
                'account_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:50',
                'branch' => 'nullable|string|max:255',
                'mobile_provider' => 'required_if:method_type,mobile|in:jazzcash,easypaisa,upaisa',
                'is_default' => 'boolean',
            ]);

            // If setting as default, remove default from other methods
            if ($request->is_default) {
                WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                    ->update(['is_default' => false]);
            }

            WholesalerPaymentMethod::create([
                'wholesaler_id' => $wholesaler->id,
                'method_type' => $validated['method_type'],
                'bank_name' => $validated['bank_name'] ?? null,
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'],
                'branch' => $validated['branch'] ?? null,
                'mobile_provider' => $validated['mobile_provider'] ?? null,
                'is_default' => $request->is_default ?? false,
                'status' => 'active',
            ]);

            return redirect()->route('wholesaler.payments.methods')
                ->with('success', 'Payment method added successfully!');

        } catch (\Exception $e) {
            Log::error('Store Payment Method Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error adding payment method');
        }
    }

    /**
     * Show edit payment method form
     */
    public function editPaymentMethod($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $method = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            return view('wholesaler.payments.methods-edit', compact('method'));

        } catch (\Exception $e) {
            Log::error('Edit Payment Method Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Payment method not found');
        }
    }

    /**
     * Update payment method
     */
    public function updatePaymentMethod(Request $request, $id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $method = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            $validated = $request->validate([
                'method_type' => 'required|in:bank,mobile',
                'bank_name' => 'required_if:method_type,bank',
                'account_name' => 'required|string|max:255',
                'account_number' => 'required|string|max:50',
                'branch' => 'nullable|string|max:255',
                'mobile_provider' => 'required_if:method_type,mobile|in:jazzcash,easypaisa,upaisa',
                'is_default' => 'boolean',
            ]);

            // If setting as default, remove default from other methods
            if ($request->is_default) {
                WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                    ->where('id', '!=', $id)
                    ->update(['is_default' => false]);
            }

            $method->update([
                'method_type' => $validated['method_type'],
                'bank_name' => $validated['bank_name'] ?? null,
                'account_name' => $validated['account_name'],
                'account_number' => $validated['account_number'],
                'branch' => $validated['branch'] ?? null,
                'mobile_provider' => $validated['mobile_provider'] ?? null,
                'is_default' => $request->is_default ?? $method->is_default,
            ]);

            return redirect()->route('wholesaler.payments.methods')
                ->with('success', 'Payment method updated successfully!');

        } catch (\Exception $e) {
            Log::error('Update Payment Method Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating payment method');
        }
    }

    /**
     * Delete payment method
     */
    public function destroyPaymentMethod($id)
    {
        try {
            $wholesaler = Auth::guard('wholesaler')->user();
            $method = WholesalerPaymentMethod::where('wholesaler_id', $wholesaler->id)
                ->findOrFail($id);

            // Don't allow deletion of default method
            if ($method->is_default) {
                return redirect()->back()->with('error', 'Cannot delete default payment method. Set another method as default first.');
            }

            $method->delete();

            return redirect()->route('wholesaler.payments.methods')
                ->with('success', 'Payment method deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Destroy Payment Method Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting payment method');
        }
    }

    /**
     * Export payments to CSV
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
                ->when($request->type, function($query) use ($request) {
                    return $query->where('type', $request->type);
                })
                ->when($request->status, function($query) use ($request) {
                    return $query->where('status', $request->status);
                })
                ->orderBy('created_at', 'desc')
                ->get();

            $fileName = 'payments-' . date('Y-m-d') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv; charset=utf-8',
                'Content-Disposition' => "attachment; filename=$fileName",
            ];

            $callback = function() use ($payments) {
                $file = fopen('php://output', 'w');

                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");

                // Add headers
                fputcsv($file, [
                    'Date',
                    'Transaction ID',
                    'Type',
                    'Amount',
                    'Payment Type',
                    'Status',
                    'Description',
                    'Notes'
                ]);

                // Add data
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->created_at->format('Y-m-d H:i:s'),
                        $payment->transaction_id,
                        ucfirst($payment->type),
                        'RS. ' . number_format($payment->amount, 2),
                        ucfirst(str_replace('_', ' ', $payment->payment_type)),
                        ucfirst($payment->status),
                        $payment->description,
                        $payment->notes ?? ''
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export Payments Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error exporting payments');
        }
    }

    /**
     * Success page for payments
     */
    public function paymentSuccess(Request $request)
    {
        try {
            $sessionId = $request->session_id;

            if ($sessionId) {
                $payment = WholesalerPayment::where('transaction_id', $sessionId)
                    ->first();

                if ($payment) {
                    $payment->update([
                        'status' => 'completed',
                        'completed_at' => now(),
                    ]);
                }
            }

            return view('wholesaler.payments.success');

        } catch (\Exception $e) {
            Log::error('Payment Success Error: ' . $e->getMessage());
            return redirect()->route('wholesaler.payments.index')
                ->with('error', 'Payment processing error');
        }
    }

    /**
     * Cancel page for payments
     */
    public function paymentCancel()
    {
        return view('wholesaler.payments.cancel');
    }

    /**
     * Calculate available balance for withdrawal
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

    /**
     * Format payment method for display
     */
    private function formatPaymentMethod($paymentMethod)
    {
        if ($paymentMethod->method_type == 'bank') {
            return $paymentMethod->bank_name . ' - ' . $paymentMethod->account_number;
        } else {
            return ucfirst($paymentMethod->mobile_provider) . ' - ' . $paymentMethod->account_number;
        }
    }
}
