<?php

namespace Tests\Feature;

use App\Enums\BillStatus;
use App\Models\AcademicYear;
use App\Models\Fee;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_generate_monthly_bills_for_active_students(): void
    {
        $admin = User::factory()->admin()->create();
        $year = AcademicYear::factory()->active()->create();
        $class = SchoolClass::factory()->create([
            'academic_year_id' => $year->id,
            'level' => 'X',
        ]);
        $student = Student::factory()->create(['class_id' => $class->id]);

        Fee::factory()->create([
            'academic_year_id' => $year->id,
            'level' => 'X',
            'amount' => 300000,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.bills.generate.store'), [
            'academic_year_id' => $year->id,
            'month_from' => 7,
            'month_to' => 9,
            'period_year' => 2026,
            'due_date' => '2026-07-10',
        ]);

        $response->assertRedirect(route('admin.bills.index'));

        $this->assertDatabaseCount('bills', 3);
        $this->assertDatabaseHas('bills', [
            'student_id' => $student->id,
            'period_month' => 7,
            'period_year' => 2026,
            'amount' => 300000,
            'status' => BillStatus::Unpaid->value,
        ]);
    }

    public function test_generation_skips_existing_bills(): void
    {
        $admin = User::factory()->admin()->create();
        $year = AcademicYear::factory()->active()->create();
        $class = SchoolClass::factory()->create([
            'academic_year_id' => $year->id,
            'level' => 'XI',
        ]);
        $student = Student::factory()->create(['class_id' => $class->id]);

        Fee::factory()->create([
            'academic_year_id' => $year->id,
            'level' => 'XI',
            'amount' => 350000,
        ]);

        $payload = [
            'academic_year_id' => $year->id,
            'month_from' => 8,
            'month_to' => 8,
            'period_year' => 2026,
            'due_date' => '2026-08-10',
        ];

        $this->actingAs($admin)->post(route('admin.bills.generate.store'), $payload);
        $this->actingAs($admin)->post(route('admin.bills.generate.store'), $payload);

        $this->assertDatabaseCount('bills', 1);
        $this->assertDatabaseHas('bills', ['student_id' => $student->id]);
    }
}
