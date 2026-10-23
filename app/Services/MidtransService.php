<?php

namespace App\Services;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class MidtransService
{
    public function enabled(): bool
    {
        return filled(config('services.midtrans.server_key'));
    }

    public function isProduction(): bool
    {
        return (bool) config('services.midtrans.is_production', false);
    }

    public function clientKey(): ?string
    {
        return config('services.midtrans.client_key');
    }

    /**
     * @return array<string, mixed>|null Null means the payment runs in simulation mode.
     */
    public function createSnapTransaction(Payment $payment): ?array
    {
        if (! $this->enabled()) {
            return null;
        }

        $payment->loadMissing('bills');

        $payload = [
            'transaction_details' => [
                'order_id' => $payment->invoice_number,
                'gross_amount' => $payment->amount,
            ],
            'item_details' => $payment->bills->map(fn ($bill) => [
                'id' => (string) $bill->id,
                'price' => (int) $bill->pivot->amount,
                'quantity' => 1,
                'name' => 'SPP '.$bill->periodLabel(),
            ])->values()->all(),
            'customer_details' => [
                'first_name' => $payment->student->name,
                'email' => $payment->student->user?->email,
                'phone' => $payment->student->parent_phone ?? $payment->student->phone,
            ],
            'callbacks' => [
                'finish' => route('portal.payments.finish', $payment),
            ],
        ];

        $response = Http::withBasicAuth((string) config('services.midtrans.server_key'), '')
            ->acceptJson()
            ->asJson()
            ->post($this->baseUrl().'/snap/v1/transactions', $payload);

        if ($response->failed()) {
            throw new RuntimeException(
                'Gagal membuat transaksi Midtrans: '.($response->json('error_messages.0') ?? $response->body())
            );
        }

        return $response->json();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function transactionStatus(Payment $payment): ?array
    {
        if (! $this->enabled()) {
            return null;
        }

        $response = Http::withBasicAuth((string) config('services.midtrans.server_key'), '')
            ->acceptJson()
            ->get($this->apiBaseUrl().'/v2/'.$payment->invoice_number.'/status');

        return $response->failed() ? null : $response->json();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function isValidSignature(array $payload): bool
    {
        $serverKey = (string) config('services.midtrans.server_key');

        if ($serverKey === '' || empty($payload['signature_key'])) {
            return false;
        }

        $expected = hash('sha512', ($payload['order_id'] ?? '')
            .($payload['status_code'] ?? '')
            .($payload['gross_amount'] ?? '')
            .$serverKey);

        return hash_equals($expected, (string) $payload['signature_key']);
    }

    private function baseUrl(): string
    {
        return $this->isProduction()
            ? 'https://app.midtrans.com'
            : 'https://app.sandbox.midtrans.com';
    }

    private function apiBaseUrl(): string
    {
        return $this->isProduction()
            ? 'https://api.midtrans.com'
            : 'https://api.sandbox.midtrans.com';
    }
}
