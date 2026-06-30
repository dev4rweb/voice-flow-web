<?php

namespace Tests\Unit;

use App\Services\DownloadContextResolver;
use App\Services\GeoIpResolver;
use App\Services\LocaleResolver;
use App\Services\UserAgentParser;
use Illuminate\Http\Request;
use Tests\TestCase;

final class DownloadContextResolverTest extends TestCase
{
    private DownloadContextResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->resolver = new DownloadContextResolver(
            new LocaleResolver(['en', 'de', 'fr'], 'en'),
            new UserAgentParser(),
            new GeoIpResolver(),
        );
    }

    public function test_it_prefers_locale_query_param(): void
    {
        $request = Request::create('/download/file', 'GET', ['locale' => 'de', 'tz' => 'Europe/Berlin']);
        $request->headers->set('Accept-Language', 'en-US,en;q=0.9');
        $request->headers->set('User-Agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0');
        $request->headers->set('Referer', 'https://voice-flow.dev4rweb.com/en');

        $event = $this->resolver->resolve($request, 'VoiceFlow-1.2.9.exe');

        $this->assertSame('de', $event->siteLocale);
        $this->assertSame('Europe/Berlin', $event->timezone);
        $this->assertSame('en-US,en;q=0.9', $event->acceptLanguage);
        $this->assertSame('Windows 10/11', $event->os);
        $this->assertStringStartsWith('Chrome', (string) $event->browser);
    }

    public function test_it_reads_locale_from_referer_when_query_missing(): void
    {
        $request = Request::create('/download/file', 'GET');
        $request->headers->set('Referer', 'https://voice-flow.dev4rweb.com/fr');

        $event = $this->resolver->resolve($request, 'VoiceFlow-1.2.9.exe');

        $this->assertSame('fr', $event->siteLocale);
    }

    public function test_it_rejects_invalid_timezone(): void
    {
        $request = Request::create('/download/file', 'GET', ['tz' => 'Bad Zone!']);

        $event = $this->resolver->resolve($request, 'VoiceFlow-1.2.9.exe');

        $this->assertNull($event->timezone);
    }
}
