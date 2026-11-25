<?php

declare(strict_types=1);

namespace App\Catalog;

final readonly class ProductFact
{
    public function __construct(
        public string $label,
        public string $value,
    ) {}
}
