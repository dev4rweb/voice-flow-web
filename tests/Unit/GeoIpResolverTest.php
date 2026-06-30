<?php

namespace Tests\Unit;

use App\Services\GeoIpResolver;
use Tests\TestCase;

final class GeoIpResolverTest extends TestCase
{
    private string $databasePath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->databasePath = storage_path('app/geoip/GeoLite2-City.mmdb');
        config(['voice_flow.geoip_database_path' => $this->databasePath]);
    }

    public function test_database_file_is_readable(): void
    {
        $this->assertFileExists($this->databasePath);
        $this->assertGreaterThan(1_000_000, filesize($this->databasePath));
    }

    public function test_it_resolves_public_ip_to_country(): void
    {
        if (! is_file($this->databasePath)) {
            $this->markTestSkipped('GeoLite2 database missing.');
        }

        $resolver = new GeoIpResolver();

        $this->assertTrue($resolver->isAvailable());

        $result = $resolver->lookup('8.8.8.8');

        $this->assertSame('US', $result['country_code']);
        $this->assertSame('United States', $result['country_name']);
    }

    public function test_it_skips_private_ip_addresses(): void
    {
        $resolver = new GeoIpResolver();
        $result = $resolver->lookup('127.0.0.1');

        $this->assertNull($result['country_code']);
        $this->assertNull($result['country_name']);
        $this->assertNull($result['city']);
    }
}
