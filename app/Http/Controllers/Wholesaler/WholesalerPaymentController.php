<?php

namespace App\Http\Controllers\Wholesaler;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WholesalerPaymentController extends Controller
{
    // Payments pages
    public function index()
    {
        return view('wholesaler.payments.index');
    }

    public function paymentHistory()
    {
        return view('wholesaler.payments.history');
    }

    public function show($id)
    {
        return view('wholesaler.payments.show', compact('id'));
    }

    // Withdraw
    public function withdraw()
    {
        return view('wholesaler.payments.withdraw');
    }

    public function processWithdraw(Request $request)
    {
        // TODO: add validation + business logic
        return back()->with('success', 'Withdraw request submitted.');
    }

    public function cancelWithdraw($id)
    {
        // TODO: cancel logic
        return back()->with('success', 'Withdraw request cancelled.');
    }

    // Exports
    public function exportPayments()
    {
        // TODO: implement export
        return back()->with('success', 'Export started.');
    }

    // Payment methods
    public function paymentMethods()
    {
        return view('wholesaler.payments.methods.index');
    }

    public function createPaymentMethod()
    {
        return view('wholesaler.payments.methods.create');
    }

    public function storePaymentMethod(Request $request)
    {
        return back()->with('success', 'Payment method saved.');
    }

    public function editPaymentMethod($id)
    {
        return view('wholesaler.payments.methods.edit', compact('id'));
    }

    public function updatePaymentMethod(Request $request, $id)
    {
        return back()->with('success', 'Payment method updated.');
    }

    public function destroyPaymentMethod($id)
    {
        return back()->with('success', 'Payment method deleted.');
    }

    // Gateways
    public function gateways()
    {
        return view('wholesaler.payments.gateways.index');
    }

    public function createGateway()
    {
        return view('wholesaler.payments.gateways.create');
    }

    public function storeGateway(Request $request)
    {
        return back()->with('success', 'Gateway saved.');
    }

    public function testGateway($id)
    {
        return back()->with('success', 'Gateway test executed.');
    }

    public function destroyGateway($id)
    {
        return back()->with('success', 'Gateway deleted.');
    }

    // Checkout + callbacks
    public function createCheckoutSession(Request $request)
    {
        return response()->json(['message' => 'Not implemented'], 501);
    }

    public function paymentSuccess()
    {
        return view('wholesaler.payments.success');
    }

    public function paymentCancel()
    {
        return view('wholesaler.payments.cancel');
    }

    // Webhooks (you referenced webhookHandler in routes)
    public function webhookHandler(Request $request)
    {
        return response()->json(['ok' => true]);
    }
}
