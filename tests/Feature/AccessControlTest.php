<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_admin_area(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
    }

    public function test_student_cannot_access_admin_area(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_student_cannot_view_another_students_payment(): void
    {
        $studentA = Student::factory()->create();
        $studentB = Student::factory()->create();
        $userA = User::factory()->create(['name' => $studentA->name]);
        $studentA->update(['user_id' => $userA->id]);

        $payment = Payment::factory()->success()->create([
            'student_id' => $studentB->id,
        ]);

        $this->actingAs($userA)
            ->get(route('portal.payments.show', $payment))
            ->assertNotFound();
    }
}
