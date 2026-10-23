<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_login_with_email(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@edupay.test',
        ]);

        $response = $this->post('/login', [
            'login' => 'admin@edupay.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_student_can_login_with_nis(): void
    {
        $class = SchoolClass::factory()->create();
        $user = User::factory()->create(['role' => UserRole::Student]);
        Student::factory()->create([
            'user_id' => $user->id,
            'class_id' => $class->id,
            'nis' => '20261001',
        ]);

        $response = $this->post('/login', [
            'login' => '20261001',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('portal.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_invalid_credentials_are_rejected(): void
    {
        User::factory()->create(['email' => 'admin@edupay.test']);

        $response = $this->from('/login')->post('/login', [
            'login' => 'admin@edupay.test',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('login');
        $this->assertGuest();
    }
}
