<?php

namespace App\Services;

final class SeoMetaBuilder
{
    public function landing(string $locale): array
    {
        $baseUrl = rtrim((string) config('voice_flow.domain'), '/');
        $alternates = [];

        foreach (array_keys(config('voice_flow.supported_locales')) as $supportedLocale) {
            $alternates[$supportedLocale] = "{$baseUrl}/{$supportedLocale}";
        }

        return [
            'title' => __('landing.meta.title'),
            'description' => __('landing.meta.description'),
            'canonical' => "{$baseUrl}/{$locale}",
            'ogImage' => "{$baseUrl}/".ltrim((string) config('voice_flow.og_image_path'), '/'),
            'alternates' => $alternates,
            'xDefault' => "{$baseUrl}/".config('voice_flow.default_locale'),
            'jsonLd' => [
                '@context' => 'https://schema.org',
                '@type' => 'SoftwareApplication',
                'name' => config('voice_flow.name'),
                'applicationCategory' => 'UtilitiesApplication',
                'operatingSystem' => 'Windows 10, Windows 11',
                'softwareVersion' => config('voice_flow.version'),
                'description' => __('landing.meta.description'),
                'url' => "{$baseUrl}/{$locale}",
                'image' => "{$baseUrl}/".ltrim((string) config('voice_flow.og_image_path'), '/'),
                'downloadUrl' => route('download.file'),
                'codeRepository' => config('voice_flow.github_url'),
                'fileSize' => config('voice_flow.download_size'),
                'sha256' => config('voice_flow.sha256'),
                'inLanguage' => array_values(array_column(config('voice_flow.supported_locales'), 'name')),
                'offers' => ['@type' => 'Offer', 'price' => '0', 'priceCurrency' => 'USD'],
            ],
        ];
    }
}
