<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

final class LandingTranslationsTest extends TestCase
{
    public function test_each_locale_has_full_laravel_translation_array(): void
    {
        foreach (['en', 'es', 'fr', 'de', 'pt', 'zh', 'ar'] as $locale) {
            $path = dirname(__DIR__, 2)."/resources/lang/{$locale}/landing.php";
            $translations = require $path;

            $this->assertArrayHasKey('ui', $translations);
            $this->assertArrayHasKey('features', $translations);
            $this->assertArrayHasKey('recognition_languages_title', $translations);
            $this->assertArrayHasKey('faq', $translations);
            $this->assertCount(4, $translations['faq']);
            $this->assertNotEmpty($translations['badges'][0] ?? null);
            $this->assertStringContainsStringIgnoringCase(
                match ($locale) {
                    'en' => 'ad-free',
                    'es' => 'sin anuncios',
                    'fr' => 'sans publicité',
                    'de' => 'ohne Werbung',
                    'pt' => 'sem anúncios',
                    'zh' => '无广告',
                    'ar' => 'بدون إعلانات',
                },
                $translations['meta']['description']
            );
            $this->assertStringNotContainsString("require __DIR__.'/../en/landing.php'", file_get_contents($path));
        }
    }
}
