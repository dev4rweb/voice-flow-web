<?php

namespace Tests\Feature;

use Tests\TestCase;

final class LandingTest extends TestCase
{
    public function test_root_redirects_to_browser_language_or_english_fallback(): void
    {
        $this->withHeader('Accept-Language', 'de-DE,de;q=0.9')->get('/')->assertRedirect('/de');
        $this->withHeader('Accept-Language', 'ru-RU,uk;q=0.9')->get('/')->assertRedirect('/en');
    }

    public function test_landing_page_contains_seo_language_markup_and_rtl(): void
    {
        $this->get('/en')
            ->assertOk()
            ->assertSee('Voice Flow turns speech into text', false)
            ->assertSee('rel="canonical"', false)
            ->assertSee('hreflang="x-default"', false)
            ->assertSee('SoftwareApplication', false)
            ->assertSee('English, Spanish, French, German, Portuguese, Chinese, Arabic', false)
            ->assertSee('data-theme-toggle', false)
            ->assertSee('data-locale-switcher', false)
            ->assertSee('data-nav-toggle', false)
            ->assertSee('data-site-menu', false)
            ->assertSee('data-menu-backdrop', false)
            ->assertSee('data-menu-close', false)
            ->assertSee('id="recognition-languages"', false)
            ->assertSee('Deutsch', false)
            ->assertSee('data-scroll-top', false)
            ->assertSee('data-reveal', false)
            ->assertSee('rel="icon"', false)
            ->assertSee('images/voice-flow-icon.png', false)
            ->assertSee('Free · No ads', false)
            ->assertSee('Is Voice Flow free?', false)
            ->assertSee('Free, ad-free Windows push-to-talk dictation', false)
            ->assertSee('property="og:image"', false);

        $this->get('/ar')->assertOk()->assertSee('lang="ar" dir="rtl"', false);
        $this->get('/es')->assertOk()->assertSee('Funciones principales', false);
        $this->get('/ru')->assertNotFound();
    }
}
