<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Catalog\CatalogRepository;
use App\Seo\SocialImage;
use App\Seo\StructuredData;

final readonly class SeoViewModel
{
    public string $siteUrl;

    public string $socialImageUrl;

    private StructuredData $structuredData;

    public function __construct(CatalogRepository $catalogs)
    {
        $this->siteUrl = rtrim(url('/'), '/');
        $this->socialImageUrl = $this->siteUrl.SocialImage::PATH;
        $this->structuredData = new StructuredData($catalogs->catalog(), $this->siteUrl);
    }

    public function socialImageWidth(): int
    {
        return SocialImage::WIDTH;
    }

    public function socialImageHeight(): int
    {
        return SocialImage::HEIGHT;
    }

    public function jsonLd(): string
    {
        return json_encode($this->structuredData->graph(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_THROW_ON_ERROR);
    }
}
