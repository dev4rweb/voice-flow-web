<?php

namespace Tests\Unit;

use App\Services\LocaleResolver;
use PHPUnit\Framework\TestCase;

final class LocaleResolverTest extends TestCase
{
    public function test_it_resolves_best_supported_browser_language(): void
    {
        $resolver = new LocaleResolver(['en', 'es', 'fr', 'de', 'pt', 'zh', 'ar'], 'en');

        $this->assertSame('fr', $resolver->resolve('pl-PL,fr-FR;q=0.9,en;q=0.5'));
        $this->assertSame('pt', $resolver->resolve('pt-BR,es;q=0.8'));
        $this->assertSame('en', $resolver->resolve('ru-RU,uk;q=0.9'));
    }
}
