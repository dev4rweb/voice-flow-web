<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/admin/dashboard')->assertRedirect('/admin/login');
    }

    public function test_admin_can_log_in_and_open_dashboard(): void
    {
        $admin = $this->createAdminUser([
            'email' => 'admin@voice-flow.test',
            'password' => 'secret-pass',
        ]);

        $this->get('/admin/login')->assertOk()->assertSee('Voice Flow Admin', false);

        $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => 'secret-pass',
        ])->assertRedirect('/admin/dashboard');

        $this->actingAs($admin)->get('/admin/dashboard')->assertOk()->assertSee('Overview of landing downloads', false);
    }

    public function test_non_admin_cannot_log_in(): void
    {
        User::factory()->create([
            'email' => 'user@voice-flow.test',
            'password' => 'secret-pass',
            'is_admin' => false,
        ]);

        $this->post('/admin/login', [
            'email' => 'user@voice-flow.test',
            'password' => 'secret-pass',
        ])->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_admin_can_log_out(): void
    {
        $admin = $this->createAdminUser();

        $this->actingAs($admin)
            ->post('/admin/logout')
            ->assertRedirect('/admin/login');

        $this->assertGuest();
    }
}
