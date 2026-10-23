<?php

namespace Database\Factories;

use App\Enums\BillStatus;
use App\Models\AcademicYear;
use App\Models\Bill;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Bill>
 */
class BillFactory extends Factory
{
    public function definition(): array
    {
        $month = fake()->numberBetween(1, 12);
        $year = (int) now()->format('Y');

        return [
            'student_id' => Student::factory(),
            'academic_year_id' => AcademicYear::factory(),
            'period_month' => $month,
            'period_year' => $year,
            'amount' => 350000,
            'paid_amount' => 0,
            'due_date' => now()->setDate($year, $month, 10),
            'status' => BillStatus::Unpaid,
            'notes' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'paid_amount' => $attributes['amount'] ?? 350000,
            'status' => BillStatus::Paid,
        ]);
    }
}
