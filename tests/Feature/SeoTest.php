<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class SeoTest extends TestCase
{
    private const string IMAGE_SITEMAP_NAMESPACE = 'http://www.google.com/schemas/sitemap-image/1.1';

    public function test_robots_txt_allows_everything_and_points_to_the_sitemap(): void
    {
        $response = $this->get('/robots.txt');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/plain; charset=UTF-8');
        $this->assertSame("User-Agent: *\nAllow: /\n\nSitemap: ".url('/sitemap.xml')."\n", $response->getContent());
    }

    public function test_the_sitemap_lists_the_page_with_its_images(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $xml = simplexml_load_string((string) $response->getContent());
        $this->assertNotFalse($xml);
        $this->assertSame(url('/'), (string) $xml->url->loc);
        $this->assertSame('2026-11-24', (string) $xml->url->lastmod);
        $this->assertCount(13, $xml->url->children(self::IMAGE_SITEMAP_NAMESPACE)->image);
    }

    public function test_structured_data_describes_the_catalogue_with_prices(): void
    {
        $this->withoutMix();

        preg_match('#<script type="application/ld\+json">(.+?)</script>#s', (string) $this->get('/')->getContent(), $match);
        $data = json_decode($match[1] ?? '', true, flags: JSON_THROW_ON_ERROR);
        $catalog = $data['@graph'][3];
        $orma = $catalog['itemListElement'][0]['item'];

        $this->assertSame(['Organization', 'WebSite', 'WebPage', 'ItemList'], array_column($data['@graph'], '@type'));
        $this->assertSame(12, $catalog['numberOfItems']);
        $this->assertSame('Product', $orma['@type']);
        $this->assertSame('1062.50', $orma['offers']['price']);
        $this->assertSame('EUR', $orma['offers']['priceCurrency']);
        $this->assertFalse($orma['offers']['priceSpecification']['valueAddedTaxIncluded']);
    }
}
