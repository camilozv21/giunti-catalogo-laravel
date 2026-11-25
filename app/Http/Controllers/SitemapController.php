<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Catalog\CatalogRepository;
use App\Catalog\Product;
use App\Seo\SocialImage;
use Illuminate\Http\Response;

final readonly class SitemapController
{
    public function __construct(private CatalogRepository $catalogs) {}

    public function __invoke(): Response
    {
        $catalog = $this->catalogs->catalog();
        $siteUrl = rtrim(url('/'), '/');
        $images = [
            $siteUrl.SocialImage::PATH,
            ...array_map(fn (Product $product): string => "{$siteUrl}/images/photos/{$product->image->stem()}-800.jpg", $catalog->products()),
        ];

        return response()
            ->view('sitemap', ['siteUrl' => $siteUrl, 'updatedAt' => $catalog->updatedAt, 'images' => $images])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
