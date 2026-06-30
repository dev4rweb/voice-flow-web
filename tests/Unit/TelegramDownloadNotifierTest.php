<?php

namespace Tests\Unit;

use App\Services\TelegramDownloadNotifier;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class TelegramDownloadNotifierTest extends TestCase
{
    public function test_it_sends_download_message_to_telegram(): void
    {
        config([
            'voice_flow.telegram.bot_token' => 'test-token',
            'voice_flow.telegram.chat_id' => '12345',
        ]);

        Http::fake([
            'api.telegram.org/*' => Http::response(['ok' => true], 200),
        ]);

        $event = (object) [
            'filename' => 'VoiceFlow-1.2.9.exe',
            'site_locale' => 'de',
            'accept_language' => 'de-DE,en;q=0.8',
            'browser' => 'Chrome 120.0.0.0',
            'os' => 'Windows 10/11',
            'timezone' => 'Europe/Berlin',
            'ip_address' => '8.8.8.8',
            'country_code' => 'US',
            'country_name' => 'United States',
            'city' => 'Mountain View',
            'referer' => 'https://voice-flow.dev4rweb.com/de',
            'created_at' => '2026-06-30 12:00:00',
        ];

        $sent = app(TelegramDownloadNotifier::class)->notify($event, 10);

        $this->assertTrue($sent);
        Http::assertSent(function ($request): bool {
            return str_contains($request->url(), 'bottest-token/sendMessage')
                && $request['chat_id'] === '12345'
                && str_contains($request['text'], 'Voice Flow download')
                && str_contains($request['text'], 'United States');
        });
    }

    public function test_it_skips_when_credentials_missing(): void
    {
        config([
            'voice_flow.telegram.bot_token' => '',
            'voice_flow.telegram.chat_id' => '',
        ]);

        Http::fake();

        $sent = app(TelegramDownloadNotifier::class)->notify((object) ['filename' => 'x.exe'], 1);

        $this->assertFalse($sent);
        Http::assertNothingSent();
    }
}
