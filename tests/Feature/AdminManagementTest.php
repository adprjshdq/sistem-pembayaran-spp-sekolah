<?php

namespace Tests\Feature;

use App\Models\AcademicYear;
use App\Models\Fee;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_a_school_class(): void
    {
        $admin = User::factory()->admin()->create();
        $year = AcademicYear::factory()->create();

        $this->actingAs($admin)
            ->post(route('admin.classes.store'), [
                'academic_year_id' => $year->id,
                'name' => 'XII RPL 2',
                'level' => 'XII',
                'major' => 'Rekayasa Perangkat Lunak',
                'homeroom_teacher' => 'Budi Santoso, S.Kom.',
            ])
            ->assertRedirect(route('admin.classes.index'));

        $this->assertDatabaseHas('school_classes', [
            'academic_year_id' => $year->id,
            'name' => 'XII RPL 2',
            'level' => 'XII',
        ]);
    }

    public function test_setting_a_new_active_academic_year_deactivates_the_previous_one(): void
    {
        $admin = User::factory()->admin()->create();
        $previous = AcademicYear::factory()->active()->create();

        $this->actingAs($admin)
            ->post(route('admin.academic-years.store'), [
                'name' => '2027/2028',
                'start_date' => '2027-07-01',
                'end_date' => '2028-06-30',
                'is_active' => '1',
            ])
            ->assertRedirect(route('admin.academic-years.index'));

        $this->assertFalse($previous->fresh()->is_active);

        $newYear = AcademicYear::query()->where('name', '2027/2028')->firstOrFail();
        $this->assertTrue($newYear->is_active);
    }

    public function test_admin_can_create_and_update_a_fee(): void
    {
        $admin = User::factory()->admin()->create();
        $year = AcademicYear::factory()->create();

        $this->actingAs($admin)->post(route('admin.fees.store'), [
            'academic_year_id' => $year->id,
            'level' => 'X',
            'amount' => 275000,
        ])->assertRedirect(route('admin.fees.index'));

        $fee = Fee::query()->firstOrFail();
        $this->assertSame(275000, $fee->amount);

        $this->actingAs($admin)->put(route('admin.fees.update', $fee), [
            'academic_year_id' => $year->id,
            'level' => 'X',
            'amount' => 300000,
        ])->assertRedirect(route('admin.fees.index'));

        $this->assertSame(300000, $fee->fresh()->amount);
    }

    public function test_admin_can_update_school_settings(): void
    {
        $admin = User::factory()->admin()->create();
        Setting::set('school_name', 'Sekolah Lama');

        $this->actingAs($admin)
            ->put(route('admin.settings.update'), [
                'school_name' => 'SMA Negeri 1 Baru',
                'school_address' => 'Jl. Baru No. 1',
                'school_phone' => '022-123456',
                'school_email' => 'info@sekolahbaru.sch.id',
                'treasurer_name' => 'Bendahara Baru',
                'treasurer_nip' => '123456789',
                'headmaster_name' => 'Kepala Sekolah Baru',
                'receipt_note' => 'Catatan baru',
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $this->assertSame('SMA Negeri 1 Baru', Setting::get('school_name'));
        $this->assertSame('info@sekolahbaru.sch.id', Setting::get('school_email'));
    }
}
