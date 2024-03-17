<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Braintree\Gateway;

class PaymentController extends Controller
{
    public function index()
    {
        return view('payment.index');
    }

    public function create(Request $request)
    {
        $gateway = new Gateway([
            'environment' => config('services.braintree.environment'),
            'merchantId' => config('services.braintree.merchant_id'),
            'publicKey' => config('services.braintree.public_key'),
            'privateKey' => config('services.braintree.private_key')
        ]);

        $nonce = $request->input('payment_method_nonce');
        $result = $gateway->transaction()->sale([
            'amount' => '100.00',
            'paymentMethodNonce' => $nonce,
            'options' => ['submitForSettlement' => true],
        ]);

        if ($result->success) {
            Payment::create([
                'amount' => '100.00',
                'status' => 'completed',
                'transaction_id' => $result->transaction->id,
            ]);

            return redirect()->route('payment.index')->with('success', 'Payment successful!');
        } else {
            return back()->with('error', 'Payment failed. Please try again.');
        }
    }
}