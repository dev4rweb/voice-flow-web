<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

final class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $baseUrl = rtrim((string) config('voice_flow.domain'), '/');
        $locales = array_keys(config('voice_flow.supported_locales'));
        $urls = '';

        foreach ($locales as $locale) {
            $urls .= "    <url>\n";
            $urls .= '        <loc>'.e("{$baseUrl}/{$locale}")."</loc>\n";

            foreach ($locales as $alternate) {
                $urls .= '        <xhtml:link rel="alternate" hreflang="'.e($alternate).'" href="'.e("{$baseUrl}/{$alternate}")."\" />\n";
            }

            $urls .= '        <xhtml:link rel="alternate" hreflang="x-default" href="'.e("{$baseUrl}/en")."\" />\n";
            $urls .= "        <changefreq>weekly</changefreq>\n";
            $urls .= '        <priority>'.($locale === 'en' ? '1.0' : '0.9')."</priority>\n";
            $urls .= "    </url>\n";
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n"
            .$urls
            .'</urlset>';

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        $baseUrl = rtrim((string) config('voice_flow.domain'), '/');

        return response("User-agent: *\nAllow: /\n\nSitemap: {$baseUrl}/sitemap.xml\n", 200)
            ->header('Content-Type', 'text/plain');
    }
}
