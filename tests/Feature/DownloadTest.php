<?php

namespace Tests\Feature;

use App\Jobs\NotifyDownloadJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

final class DownloadTest extends TestCase
{
    use RefreshDatabase;

    private string $downloadPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->downloadPath = storage_path('framework/testing/VoiceFlow-1.2.9.exe');
        config([
            'voice_flow.download_path' => $this->downloadPath,
            'voice_flow.download_url' => null,
            'voice_flow.telegram.bot_token' => '',
            'voice_flow.telegram.chat_id' => '',
            'voice_flow.telegram.notify_downloads' => false,
        ]);
    }

    protected function tearDown(): void
    {
        File::delete($this->downloadPath);
        parent::tearDown();
    }

    public function test_missing_file_does_not_increment_counter(): void
    {
        $this->get('/download/file')->assertNotFound();
        $this->assertDatabaseCount('download_events', 0);
    }

    public function test_download_records_analytics_context(): void
    {
        File::ensureDirectoryExists(dirname($this->downloadPath));
        File::put($this->downloadPath, 'fake exe');

        $this
            ->withHeaders([
                'Accept-Language' => 'de-DE,en;q=0.8',
                'Referer' => 'https://voice-flow.dev4rweb.com/de',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0 Safari/537.36',
            ])
            ->get('/download/file?locale=de&tz=Europe/Berlin')
            ->assertOk();

        $this->assertDatabaseHas('download_events', [
            'filename' => 'VoiceFlow-1.2.9.exe',
            'site_locale' => 'de',
            'accept_language' => 'de-DE,en;q=0.8',
            'timezone' => 'Europe/Berlin',
            'os' => 'Windows 10/11',
        ]);
    }

    public function test_download_increments_counter_and_admin_can_view_stats(): void
    {
        File::ensureDirectoryExists(dirname($this->downloadPath));
        File::put($this->downloadPath, 'fake exe');

        $this->get('/download/file')->assertOk()->assertHeader('content-disposition');
        $this->assertDatabaseHas('download_events', ['filename' => 'VoiceFlow-1.2.9.exe']);

        $this->get('/stats')->assertNotFound();

        $this->actingAs($this->createAdminUser())
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSee('Total downloads', false);
    }

    public function test_download_dispatches_telegram_job_when_configured(): void
    {
        Queue::fake();

        config([
            'voice_flow.telegram.bot_token' => 'test-token',
            'voice_flow.telegram.chat_id' => '12345',
            'voice_flow.telegram.notify_downloads' => true,
        ]);

        File::ensureDirectoryExists(dirname($this->downloadPath));
        File::put($this->downloadPath, 'fake exe');

        $this->get('/download/file?locale=en')->assertOk();

        Queue::assertPushed(NotifyDownloadJob::class);
    }

    public function test_external_download_redirects_and_command_outputs_stats(): void
    {
        config(['voice_flow.download_url' => 'https://cdn.example.com/VoiceFlow-1.2.9.exe']);

        $this->get('/download/file')->assertRedirect('https://cdn.example.com/VoiceFlow-1.2.9.exe');
        $this->artisan('downloads:stats')->expectsOutput('Voice Flow downloads')->expectsOutput('Total: 1')->assertExitCode(0);
    }
}
