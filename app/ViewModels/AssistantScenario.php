<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Catalog\ProductFilter;

final readonly class AssistantScenario
{
    public function __construct(
        public string $id,
        public string $label,
        public string $message,
        public ProductFilter $filter,
        public string $appliedFilters,
    ) {}
}
