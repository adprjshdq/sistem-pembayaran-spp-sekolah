<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\MidtransService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentCallbackController extends Controller
{
    public function __invoke(
        Request $request,
        PaymentService $payments,
        MidtransService $midtrans,
    ): JsonResponse {
        $payload = $request->all();

        if (! $midtrans->isValidSignature($payload)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payment = Payment::query()
            ->where('invoice_number', $payload['order_id'] ?? null)
            ->first();

        if (! $payment) {
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $payments->applyGatewayStatus($payment, $payload);

        return response()->json(['message' => 'OK']);
    }
}
