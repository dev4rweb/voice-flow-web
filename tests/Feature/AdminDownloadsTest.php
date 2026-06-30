<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class AdminDownloadsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_page_requires_authentication(): void
    {
        $this->get('/admin/downloads')->assertRedirect('/admin/login');
    }

    public function test_admin_page_shows_recent_downloads(): void
    {
        DB::table('download_events')->insert([
            'filename' => 'VoiceFlow-1.2.9.exe',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'test',
            'site_locale' => 'de',
            'accept_language' => 'de-DE',
            'browser' => 'Chrome 120.0.0.0',
            'os' => 'Windows 10/11',
            'timezone' => 'Europe/Berlin',
            'referer' => 'https://voice-flow.dev4rweb.com/de',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($this->createAdminUser())
            ->get('/admin/downloads')
            ->assertOk()
            ->assertSee('Downloads', false)
            ->assertSee('Europe/Berlin', false)
            ->assertSee('de', false);
    }
}
