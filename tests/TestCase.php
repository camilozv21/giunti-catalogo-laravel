<?php

declare(strict_types=1);

namespace Tests;

use App\Catalog\Catalog;
use App\Catalog\CatalogRepository;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function catalog(): Catalog
    {
        return $this->app->make(CatalogRepository::class)->catalog();
    }
}
