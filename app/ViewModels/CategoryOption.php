<?php

declare(strict_types=1);

namespace App\ViewModels;

final readonly class CategoryOption
{
    public function __construct(
        public string $value,
        public string $label,
        public int $count,
        public bool $isDefault = false,
    ) {}
}
