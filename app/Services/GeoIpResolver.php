<?php

namespace App\Services;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Illuminate\Support\Facades\Log;

final class GeoIpResolver
{
    private ?Reader $reader = null;

    public function isAvailable(): bool
    {
        return is_file($this->databasePath());
    }

    /**
     * @return array{country_code: ?string, country_name: ?string, city: ?string}
     */
    public function lookup(?string $ipAddress): array
    {
        $empty = [
            'country_code' => null,
            'country_name' => null,
            'city' => null,
        ];

        if ($ipAddress === null || trim($ipAddress) === '' || ! $this->isAvailable()) {
            return $empty;
        }

        if (filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            return $empty;
        }

        try {
            $record = $this->reader()->city($ipAddress);

            return [
                'country_code' => $record->country->isoCode ?: null,
                'country_name' => $record->country->name ?: null,
                'city' => $record->city->name ?: null,
            ];
        } catch (AddressNotFoundException) {
            return $empty;
        } catch (\Throwable $exception) {
            Log::warning('GeoIP lookup failed.', [
                'ip' => $ipAddress,
                'message' => $exception->getMessage(),
            ]);

            return $empty;
        }
    }

    private function reader(): Reader
    {
        if ($this->reader === null) {
            $this->reader = new Reader($this->databasePath());
        }

        return $this->reader;
    }

    private function databasePath(): string
    {
        return (string) config('voice_flow.geoip_database_path');
    }
}
