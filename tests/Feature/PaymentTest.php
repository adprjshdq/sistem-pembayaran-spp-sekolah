<?php

namespace Tests\Feature;

use App\Enums\BillStatus;
use App\Enums\PaymentStatus;
use App\Models\AcademicYear;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_record_manual_payment_and_bill_becomes_paid(): void
    {
        $admin = User::factory()->admin()->create();
        $year = AcademicYear::factory()->active()->create();
        $class = SchoolClass::factory()->create(['academic_year_id' => $year->id]);
        $student = Student::factory()->create(['class_id' => $class->id]);
        $bill = Bill::factory()->create([
            'student_id' => $student->id,
            'academic_year_id' => $year->id,
            'amount' => 400000,
            'paid_amount' => 0,
            'status' => BillStatus::Unpaid,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.payments.store'), [
            'student_id' => $student->id,
            'bill_ids' => [$bill->id],
            'method' => 'cash',
            'channel' => 'Tunai',
        ]);

        $payment = Payment::query()->firstOrFail();

        $response->assertRedirect(route('admin.payments.show', $payment));

        $this->assertSame(PaymentStatus::Success, $payment->status);
        $this->assertSame(400000, $payment->amount);

        $bill->refresh();
        $this->assertSame(400000, $bill->paid_amount);
        $this->assertSame(BillStatus::Paid, $bill->status);

        $this->actingAs($admin)
            ->get(route('admin.payments.receipt', $payment))
            ->assertOk()
            ->assertSee($payment->invoice_number);
    }

    public function test_bill_cannot_be_paid_twice_through_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $student = Student::factory()->create();
        $bill = Bill::factory()->paid()->create([
            'student_id' => $student->id,
            'amount' => 300000,
            'paid_amount' => 300000,
            'status' => BillStatus::Paid,
        ]);

        $this->actingAs($admin)
            ->post(route('admin.payments.store'), [
                'student_id' => $student->id,
                'bill_ids' => [$bill->id],
                'method' => 'cash',
            ])
            ->assertSessionHasErrors('bills');
    }
}
