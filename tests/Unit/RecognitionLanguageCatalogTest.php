<?php

namespace Tests\Unit;

use App\Services\RecognitionLanguageCatalog;
use PHPUnit\Framework\TestCase;

final class RecognitionLanguageCatalogTest extends TestCase
{
    public function test_catalog_contains_expected_whisper_language_count_and_samples(): void
    {
        $catalog = new RecognitionLanguageCatalog();

        $this->assertGreaterThanOrEqual(90, $catalog->count());
        $this->assertContains('English', $catalog->names());
        $this->assertContains('Deutsch', $catalog->names());
        $this->assertContains('日本語', $catalog->names());
    }
}
