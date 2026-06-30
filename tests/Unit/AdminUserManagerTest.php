<?php

namespace Tests\Unit;

use App\Services\AdminUserManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class AdminUserManagerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_creates_or_updates_admin_from_config(): void
    {
        config([
            'voice_flow.admin.name' => 'Site Admin',
            'voice_flow.admin.email' => 'admin@voice-flow.test',
            'voice_flow.admin.password' => 'secret-pass',
        ]);

        $user = app(AdminUserManager::class)->ensureFromConfig();

        $this->assertTrue($user->isAdmin());
        $this->assertSame('admin@voice-flow.test', $user->email);

        config(['voice_flow.admin.password' => 'new-secret-pass']);

        $updated = app(AdminUserManager::class)->ensureFromConfig();

        $this->assertSame($user->id, $updated->id);
    }

    public function test_it_requires_email_and_password(): void
    {
        config([
            'voice_flow.admin.email' => '',
            'voice_flow.admin.password' => '',
        ]);

        $this->expectException(\InvalidArgumentException::class);

        app(AdminUserManager::class)->ensureFromConfig();
    }
}
