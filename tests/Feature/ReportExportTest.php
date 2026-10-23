<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Bill;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_download_filtered_report_as_csv(): void
    {
        $admin = User::factory()->admin()->create();
        $year = AcademicYear::factory()->active()->create();
        $class = SchoolClass::factory()->create([
            'academic_year_id' => $year->id,
            'name' => 'XII RPL 1',
        ]);
        $student = Student::factory()->create([
            'class_id' => $class->id,
            'nis' => '20261001',
            'name' => 'Muhammad Rayhan Pratama',
        ]);

        Bill::factory()->create([
            'student_id' => $student->id,
            'academic_year_id' => $year->id,
            'amount' => 400000,
            'paid_amount' => 0,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.reports.export', [
            'academic_year_id' => $year->id,
        ]));

        $response->assertOk();

        $content = $response->streamedContent();

        $this->assertStringContainsString('NIS,"Nama Siswa",Kelas', $content);
        $this->assertStringContainsString('20261001', $content);
        $this->assertStringContainsString('Muhammad Rayhan Pratama', $content);
        $this->assertStringContainsString('400000', $content);
    }
}
