<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
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
            'voice_flow.stats_token' => 'secret-token',
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

    public function test_download_increments_counter_and_stats_are_protected(): void
    {
        File::ensureDirectoryExists(dirname($this->downloadPath));
        File::put($this->downloadPath, 'fake exe');

        $this->get('/download/file')->assertOk()->assertHeader('content-disposition');
        $this->assertDatabaseHas('download_events', ['filename' => 'VoiceFlow-1.2.9.exe']);

        $this->getJson('/stats')->assertForbidden();
        $this->getJson('/stats?token=secret-token')->assertOk()->assertJsonPath('total', 1);
    }

    public function test_external_download_redirects_and_command_outputs_stats(): void
    {
        config(['voice_flow.download_url' => 'https://cdn.example.com/VoiceFlow-1.2.9.exe']);

        $this->get('/download/file')->assertRedirect('https://cdn.example.com/VoiceFlow-1.2.9.exe');
        $this->artisan('downloads:stats')->expectsOutput('Voice Flow downloads')->expectsOutput('Total: 1')->assertExitCode(0);
    }
}
