<?php

namespace App\Services;

use App\Enums\BillStatus;
use App\Enums\PaymentStatus;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class PaymentService
{
    public function __construct(private readonly MidtransService $midtrans) {}

    /**
     * @param  array<int, int>  $billIds
     */
    public function createPayment(
        Student $student,
        array $billIds,
        string $method = 'midtrans',
        ?string $channel = null,
        ?User $recordedBy = null,
        ?string $notes = null,
        bool $settleImmediately = false,
    ): Payment {
        $bills = $this->resolveBills($student, $billIds);
        $amount = (int) $bills->sum(fn (Bill $bill) => $bill->remainingAmount());

        return DB::transaction(function () use ($student, $bills, $amount, $method, $channel, $recordedBy, $notes, $settleImmediately) {
            $payment = Payment::create([
                'invoice_number' => $this->nextInvoiceNumber(),
                'student_id' => $student->id,
                'amount' => $amount,
                'method' => $method,
                'channel' => $channel,
                'status' => PaymentStatus::Pending,
                'notes' => $notes,
                'recorded_by' => $recordedBy?->id,
            ]);

            foreach ($bills as $bill) {
                $payment->items()->create([
                    'bill_id' => $bill->id,
                    'amount' => $bill->remainingAmount(),
                ]);
            }

            if ($settleImmediately) {
                $this->markSuccess($payment, ['channel' => $channel, 'manual' => true]);
            }

            return $payment->fresh(['student', 'items.bill']);
        });
    }

    /**
     * @param  array<int, int>  $billIds
     * @return array{payment: Payment, snap: array<string, mixed>|null}
     */
    public function createOnlinePayment(Student $student, array $billIds, ?string $channel = null, ?string $notes = null): array
    {
        $payment = $this->createPayment($student, $billIds, 'midtrans', $channel, notes: $notes);

        try {
            $snap = $this->midtrans->createSnapTransaction($payment);
        } catch (RuntimeException $exception) {
            $this->markFailed($payment, ['error' => $exception->getMessage()]);

            throw ValidationException::withMessages([
                'payment' => $exception->getMessage(),
            ]);
        }

        if ($snap === null) {
            $this->markSuccess($payment, ['simulation' => true, 'channel' => 'SIMULASI']);
        }

        return ['payment' => $payment->fresh(['student', 'bills']), 'snap' => $snap];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function markSuccess(Payment $payment, array $payload = []): Payment
    {
        if ($payment->status === PaymentStatus::Success) {
            return $payment;
        }

        DB::transaction(function () use ($payment, $payload) {
            $payment->refresh();

            if ($payment->status === PaymentStatus::Success) {
                return;
            }

            foreach ($payment->items()->with('bill')->get() as $item) {
                $bill = Bill::query()->lockForUpdate()->find($item->bill_id);

                if (! $bill) {
                    continue;
                }

                $bill->paid_amount = min($bill->amount, $bill->paid_amount + $item->amount);
                $bill->status = $bill->paid_amount >= $bill->amount
                    ? BillStatus::Paid
                    : BillStatus::Partial;
                $bill->save();
            }

            $payment->update([
                'status' => PaymentStatus::Success,
                'paid_at' => now(),
                'midtrans_transaction_id' => $payload['transaction_id'] ?? $payment->midtrans_transaction_id,
                'payload' => $payload,
            ]);
        });

        return $payment->fresh(['student', 'bills']);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function markFailed(Payment $payment, array $payload = []): Payment
    {
        if ($payment->status === PaymentStatus::Success) {
            return $payment;
        }

        $payment->update([
            'status' => PaymentStatus::Failed,
            'payload' => $payload,
        ]);

        return $payment->fresh();
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function applyGatewayStatus(Payment $payment, array $payload): Payment
    {
        $status = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;

        $isSuccess = $status === 'settlement'
            || ($status === 'capture' && in_array($fraudStatus, [null, 'accept'], true));

        if ($isSuccess) {
            return $this->markSuccess($payment, $payload);
        }

        if (in_array($status, ['cancel', 'deny', 'expire', 'failure'], true)) {
            return $this->markFailed($payment, $payload);
        }

        return $payment;
    }

    public function refreshStatus(Payment $payment): Payment
    {
        if ($payment->status !== PaymentStatus::Pending) {
            return $payment;
        }

        $payload = $this->midtrans->transactionStatus($payment);

        if ($payload === null) {
            return $payment;
        }

        return $this->applyGatewayStatus($payment, $payload);
    }

    /**
     * @param  array<int, int>  $billIds
     * @return Collection<int, Bill>
     */
    private function resolveBills(Student $student, array $billIds): Collection
    {
        $billIds = array_values(array_unique(array_map('intval', $billIds)));

        if ($billIds === []) {
            throw ValidationException::withMessages([
                'bills' => 'Pilih minimal satu tagihan yang akan dibayar.',
            ]);
        }

        $bills = Bill::query()
            ->where('student_id', $student->id)
            ->whereIn('id', $billIds)
            ->whereIn('status', [BillStatus::Unpaid->value, BillStatus::Partial->value])
            ->get();

        if ($bills->count() !== count($billIds)) {
            throw ValidationException::withMessages([
                'bills' => 'Sebagian tagihan tidak ditemukan atau sudah lunas.',
            ]);
        }

        return $bills;
    }

    private function nextInvoiceNumber(): string
    {
        do {
            $invoice = 'INV-'.now()->format('Ym').'-'.Str::upper(Str::random(6));
        } while (Payment::query()->where('invoice_number', $invoice)->exists());

        return $invoice;
    }
}
