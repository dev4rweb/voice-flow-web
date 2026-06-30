<?php

namespace Tests\Feature;

use Tests\TestCase;

final class SeoTest extends TestCase
{
    public function test_sitemap_and_robots_are_generated(): void
    {
        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertHeader('content-type', 'application/xml')
            ->assertSee('<loc>https://voice-flow.dev4rweb.com/en</loc>', false)
            ->assertSee('hreflang="ar"', false)
            ->assertSee('hreflang="x-default"', false);

        $this->get('/robots.txt')
            ->assertOk()
            ->assertSee('Sitemap: https://voice-flow.dev4rweb.com/sitemap.xml', false);
    }
}
