<?php

namespace App\Data;

final readonly class DownloadEventData
{
    public function __construct(
        public string $filename,
        public ?string $ipAddress,
        public ?string $userAgent,
        public ?string $siteLocale,
        public ?string $acceptLanguage,
        public ?string $browser,
        public ?string $os,
        public ?string $timezone,
        public ?string $referer,
        public ?string $countryCode = null,
        public ?string $countryName = null,
        public ?string $city = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toAttributes(): array
    {
        return [
            'filename' => $this->filename,
            'ip_address' => $this->ipAddress,
            'user_agent' => $this->userAgent ? mb_substr($this->userAgent, 0, 500) : null,
            'site_locale' => $this->siteLocale,
            'accept_language' => $this->acceptLanguage ? mb_substr($this->acceptLanguage, 0, 255) : null,
            'browser' => $this->browser,
            'os' => $this->os,
            'timezone' => $this->timezone,
            'referer' => $this->referer ? mb_substr($this->referer, 0, 500) : null,
            'country_code' => $this->countryCode,
            'country_name' => $this->countryName,
            'city' => $this->city,
        ];
    }
}
