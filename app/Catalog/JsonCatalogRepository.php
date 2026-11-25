<?php

declare(strict_types=1);

namespace App\Catalog;

final class JsonCatalogRepository implements CatalogRepository
{
    private ?Catalog $catalog = null;

    public function __construct(private readonly string $path) {}

    public function catalog(): Catalog
    {
        return $this->catalog ??= $this->load();
    }

    private function load(): Catalog
    {
        $data = json_decode((string) file_get_contents($this->path), true, flags: JSON_THROW_ON_ERROR);

        return new Catalog(
            updatedAt: $data['updatedAt'],
            categoryLabels: array_column($data['categories'], 'label', 'slug'),
            products: array_map(self::product(...), $data['products']),
        );
    }

    /** @param array<string, mixed> $data */
    private static function product(array $data): Product
    {
        return match (ProductKind::from($data['kind'])) {
            ProductKind::Test => TestProduct::fromArray($data),
            ProductKind::Course => CourseProduct::fromArray($data),
        };
    }
}
