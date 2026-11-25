<?php

declare(strict_types=1);

namespace App\Seo;

use App\Catalog\Catalog;
use App\Catalog\CourseProduct;
use App\Catalog\Product;
use App\Catalog\ProductFormat;

final readonly class StructuredData
{
    private const string IN_STOCK = 'https://schema.org/InStock';

    private const int LOGO_SIZE = 512;

    public function __construct(
        private Catalog $catalog,
        private string $siteUrl,
    ) {}

    /** @return array<string, mixed> */
    public function graph(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@graph' => [$this->organization(), $this->website(), $this->webpage(), $this->itemList()],
        ];
    }

    /** @return array<string, mixed> */
    private function organization(): array
    {
        return [
            '@type' => 'Organization',
            '@id' => $this->id('organization'),
            'name' => __('ui.meta.siteName'),
            'url' => $this->siteUrl,
            'logo' => ['@type' => 'ImageObject', 'url' => $this->siteUrl.'/icons/icon-512.png', 'width' => self::LOGO_SIZE, 'height' => self::LOGO_SIZE],
        ];
    }

    /** @return array<string, mixed> */
    private function website(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => $this->id('website'),
            'url' => $this->siteUrl,
            'name' => __('ui.meta.siteName'),
            'description' => __('ui.meta.description'),
            'inLanguage' => 'it-IT',
            'publisher' => ['@id' => $this->id('organization')],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => ['@type' => 'EntryPoint', 'urlTemplate' => $this->siteUrl.'/?q={search_term_string}'],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /** @return array<string, mixed> */
    private function webpage(): array
    {
        return [
            '@type' => 'WebPage',
            '@id' => $this->id('webpage'),
            'url' => $this->siteUrl,
            'name' => __('ui.meta.title'),
            'description' => __('ui.meta.description'),
            'inLanguage' => 'it-IT',
            'dateModified' => $this->catalog->updatedAt,
            'isPartOf' => ['@id' => $this->id('website')],
            'about' => ['@id' => $this->id('organization')],
            'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $this->siteUrl.SocialImage::PATH, 'width' => SocialImage::WIDTH, 'height' => SocialImage::HEIGHT],
            'mainEntity' => ['@id' => $this->id('catalogo')],
        ];
    }

    /** @return array<string, mixed> */
    private function itemList(): array
    {
        $products = $this->catalog->products();

        return [
            '@type' => 'ItemList',
            '@id' => $this->id('catalogo'),
            'name' => __('ui.catalog.title'),
            'numberOfItems' => count($products),
            'itemListElement' => array_map(
                fn (Product $product, int $index): array => ['@type' => 'ListItem', 'position' => $index + 1, 'item' => $this->product($product)],
                $products,
                array_keys($products),
            ),
        ];
    }

    /** @return array<string, mixed> */
    private function product(Product $product): array
    {
        $common = [
            '@id' => $this->id($product->id),
            'name' => "{$product->code} – {$product->name}",
            'description' => $product->summary,
            'image' => $this->siteUrl.'/images/photos/'.$product->image->stem().'-800.jpg',
        ];

        if ($product instanceof CourseProduct) {
            return ['@type' => 'Course', ...$common,
                'courseCode' => $product->code,
                'provider' => ['@id' => $this->id('organization')],
                'offers' => [...$this->offer($product), 'category' => 'Paid'],
                'hasCourseInstance' => $this->courseInstance($product),
            ];
        }

        return ['@type' => 'Product', ...$common,
            'sku' => $product->code,
            'category' => $this->catalog->categoryLabel($product->category),
            'brand' => ['@type' => 'Brand', 'name' => __('ui.meta.siteName')],
            'offers' => $this->offer($product),
        ];
    }

    /** @return array<string, mixed> */
    private function offer(Product $product): array
    {
        $price = number_format($product->salePrice->cents / 100, 2, '.', '');

        return [
            '@type' => 'Offer',
            'url' => $this->siteUrl.'/?q='.rawurlencode($product->code),
            'price' => $price,
            'priceCurrency' => 'EUR',
            'availability' => self::IN_STOCK,
            'priceSpecification' => ['@type' => 'UnitPriceSpecification', 'price' => $price, 'priceCurrency' => 'EUR', 'valueAddedTaxIncluded' => false],
        ];
    }

    /** @return array<string, mixed> */
    private function courseInstance(CourseProduct $course): array
    {
        $isOnsite = $course->format === ProductFormat::InPresenza;
        $instance = [
            '@type' => 'CourseInstance',
            'courseMode' => $isOnsite ? 'Onsite' : 'Online',
            'startDate' => $course->startDate->format('Y-m-d'),
            'courseWorkload' => "PT{$course->hours}H",
            'instructor' => ['@type' => 'Person', 'name' => $course->teacher],
        ];

        if ($isOnsite && $course->location !== null) {
            $instance['location'] = [
                '@type' => 'Place',
                'name' => $course->location,
                'address' => ['@type' => 'PostalAddress', 'addressLocality' => $course->location, 'addressCountry' => 'IT'],
            ];
        }

        return $instance;
    }

    private function id(string $fragment): string
    {
        return "{$this->siteUrl}/#{$fragment}";
    }
}
