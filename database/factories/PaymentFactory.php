<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Payment;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Payment>
 */
class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_number' => 'INV-'.now()->format('Ym').'-'.Str::upper(Str::random(6)),
            'student_id' => Student::factory(),
            'amount' => 350000,
            'method' => 'cash',
            'channel' => 'Tunai',
            'status' => PaymentStatus::Pending,
            'paid_at' => null,
            'midtrans_transaction_id' => null,
            'notes' => null,
            'recorded_by' => null,
            'payload' => null,
        ];
    }

    public function success(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PaymentStatus::Success,
            'paid_at' => now(),
        ]);
    }
}
