<?php

namespace Database\Factories;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\PaymentItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PaymentItem>
 */
class PaymentItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'payment_id' => Payment::factory(),
            'bill_id' => Bill::factory(),
            'amount' => 350000,
        ];
    }
}
