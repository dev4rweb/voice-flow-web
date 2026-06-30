<?php

namespace App\Services;

use App\Data\DownloadEventData;
use Illuminate\Http\Request;

final class DownloadContextResolver
{
    public function __construct(
        private readonly LocaleResolver $localeResolver,
        private readonly UserAgentParser $userAgentParser,
        private readonly GeoIpResolver $geoIpResolver,
    ) {}

    public function resolve(Request $request, string $filename): DownloadEventData
    {
        $userAgent = $request->userAgent();
        $parsed = $this->userAgentParser->parse($userAgent);
        $geo = $this->geoIpResolver->lookup($request->ip());

        return new DownloadEventData(
            filename: $filename,
            ipAddress: $request->ip(),
            userAgent: $userAgent,
            siteLocale: $this->resolveSiteLocale($request),
            acceptLanguage: $this->normalizeHeader($request->header('Accept-Language')),
            browser: $parsed['browser'],
            os: $parsed['os'],
            timezone: $this->resolveTimezone($request),
            referer: $this->normalizeHeader($request->headers->get('referer')),
            countryCode: $geo['country_code'],
            countryName: $geo['country_name'],
            city: $geo['city'],
        );
    }

    private function resolveSiteLocale(Request $request): ?string
    {
        $locale = strtolower(trim((string) $request->query('locale', '')));

        if ($locale !== '' && $this->localeResolver->isSupported($locale)) {
            return $locale;
        }

        $refererLocale = $this->localeFromReferer($request->headers->get('referer'));

        if ($refererLocale !== null && $this->localeResolver->isSupported($refererLocale)) {
            return $refererLocale;
        }

        return null;
    }

    private function localeFromReferer(?string $referer): ?string
    {
        if ($referer === null || $referer === '') {
            return null;
        }

        $path = parse_url($referer, PHP_URL_PATH);

        if (! is_string($path) || preg_match('#^/([a-z]{2})(?:/|$)#', $path, $matches) !== 1) {
            return null;
        }

        return $matches[1];
    }

    private function resolveTimezone(Request $request): ?string
    {
        $timezone = trim((string) $request->query('tz', ''));

        if ($timezone === '' || mb_strlen($timezone) > 64) {
            return null;
        }

        if (preg_match('/^[A-Za-z0-9_+\/-]+$/', $timezone) !== 1) {
            return null;
        }

        return $timezone;
    }

    private function normalizeHeader(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        return $value === '' ? null : $value;
    }
}
