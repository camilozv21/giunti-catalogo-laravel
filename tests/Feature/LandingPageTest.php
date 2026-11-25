<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class LandingPageTest extends TestCase
{
    public function test_the_landing_page_is_served_in_italian(): void
    {
        $this->withoutMix();

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('<html lang="it"', false);
        $response->assertSee('<meta name="robots" content="index, follow, max-video-preview:-1, max-image-preview:large, max-snippet:-1">', false);
        $response->assertSee('<link rel="canonical" href="'.url('/').'">', false);
        $response->assertDontSee('noindex', false);
    }

    public function test_the_landing_page_is_stateless_and_cacheable(): void
    {
        $this->withoutMix();

        $response = $this->get('/');

        $response->assertHeaderMissing('Set-Cookie');
        $response->assertHeader('ETag');
        $response->assertHeaderMissing('X-Robots-Tag');

        $cacheControl = (string) $response->headers->get('Cache-Control');
        $this->assertStringContainsString('public', $cacheControl);
        $this->assertStringContainsString('s-maxage=300', $cacheControl);
        $this->assertStringContainsString('stale-while-revalidate=86400', $cacheControl);
    }

    public function test_a_matching_etag_returns_not_modified(): void
    {
        $this->withoutMix();

        $etag = (string) $this->get('/')->headers->get('ETag');

        $this->get('/', ['If-None-Match' => $etag])->assertStatus(304);
    }

    public function test_the_no_js_contact_fallback_stores_nothing_and_returns_to_the_form(): void
    {
        $response = $this->post('/contatto', ['name' => 'Anna', 'email' => 'anna@esempio.it', 'message' => 'Vorrei un preventivo.']);

        $response->assertStatus(303);
        $response->assertRedirect('/#contatti');
        $response->assertHeaderMissing('Set-Cookie');
    }
}
