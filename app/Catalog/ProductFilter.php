<?php

declare(strict_types=1);

namespace App\Catalog;

use Illuminate\Support\Str;

final readonly class ProductFilter
{
    private const string ALL_CATEGORIES = 'tutti';

    private const array TYPOGRAPHIC_PUNCTUATION = ['‘' => "'", '’' => "'", 'ʼ' => "'", '`' => "'", '´' => "'", '–' => '-', '—' => '-'];

    public function __construct(
        public ?Category $category = null,
        public ?int $age = null,
        public string $query = '',
    ) {}

    /** @param array{category?: string, age?: int|null, query?: string} $filters */
    public static function fromArray(array $filters): self
    {
        $category = $filters['category'] ?? self::ALL_CATEGORIES;

        return new self(
            category: $category === self::ALL_CATEGORIES ? null : Category::from($category),
            age: $filters['age'] ?? null,
            query: $filters['query'] ?? '',
        );
    }

    public function matches(Product $product): bool
    {
        return $this->matchesCategory($product) && $this->matchesAge($product) && $this->matchesQuery($product);
    }

    private function matchesCategory(Product $product): bool
    {
        return $this->category === null || $product->category === $this->category;
    }

    private function matchesAge(Product $product): bool
    {
        return $this->age === null || $product->coversAge($this->age);
    }

    private function matchesQuery(Product $product): bool
    {
        $query = self::normalize($this->query);

        return $query === '' || str_contains(self::normalize($product->searchText()), $query);
    }

    private static function normalize(string $text): string
    {
        $ascii = Str::ascii(strtr($text, self::TYPOGRAPHIC_PUNCTUATION));

        return trim((string) preg_replace('/\s+/', ' ', mb_strtolower($ascii)));
    }
}
