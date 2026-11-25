<?php

declare(strict_types=1);

namespace App\Catalog;

use App\Support\Money;
use InvalidArgumentException;

abstract readonly class Product
{
    public function __construct(
        public string $id,
        public string $code,
        public string $name,
        public Category $category,
        public ProductFormat $format,
        public string $summary,
        public Money $price,
        public Money $salePrice,
        public ProductImage $image,
    ) {
        if (! $salePrice->isLessThan($price)) {
            throw new InvalidArgumentException("{$id}: the discounted price must be lower than the list price.");
        }
    }

    abstract public function kind(): ProductKind;

    abstract public function coversAge(int $age): bool;

    abstract public function ecmCredits(): ?int;

    abstract public function byline(): string;

    /** @return list<ProductFact> */
    abstract public function facts(): array;

    abstract public function digest(): string;

    public function isTest(): bool
    {
        return $this->kind() === ProductKind::Test;
    }

    public function discountPercent(): int
    {
        return (int) round((1 - $this->salePrice->cents / $this->price->cents) * 100);
    }

    public function searchText(): string
    {
        return "{$this->code} {$this->name}";
    }
}
