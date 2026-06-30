<?php

namespace App\Services;

final class LocaleResolver
{
    /**
     * @param array<int, string> $supportedLocales
     */
    public function __construct(
        private readonly array $supportedLocales,
        private readonly string $defaultLocale,
    ) {}

    public function resolve(?string $acceptLanguage): string
    {
        if (! $acceptLanguage) {
            return $this->defaultLocale;
        }

        $candidates = [];

        foreach (explode(',', $acceptLanguage) as $position => $part) {
            $segments = array_map('trim', explode(';', $part));
            $language = strtolower(str_replace('_', '-', $segments[0] ?? ''));

            if ($language === '') {
                continue;
            }

            $quality = 1.0;
            foreach (array_slice($segments, 1) as $segment) {
                if (str_starts_with($segment, 'q=')) {
                    $quality = (float) substr($segment, 2);
                }
            }

            $candidates[] = [
                'locale' => explode('-', $language)[0],
                'quality' => $quality,
                'position' => $position,
            ];
        }

        usort($candidates, fn (array $a, array $b): int => $b['quality'] <=> $a['quality'] ?: $a['position'] <=> $b['position']);

        foreach ($candidates as $candidate) {
            if ($this->isSupported($candidate['locale'])) {
                return $candidate['locale'];
            }
        }

        return $this->defaultLocale;
    }

    public function isSupported(string $locale): bool
    {
        return in_array($locale, $this->supportedLocales, true);
    }
}
