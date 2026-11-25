<?php

declare(strict_types=1);

namespace App\Catalog;

interface CatalogRepository
{
    public function catalog(): Catalog;
}
