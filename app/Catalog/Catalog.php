<?php

declare(strict_types=1);

namespace App\Catalog;

use OutOfBoundsException;

final readonly class Catalog
{
    /**
     * @param  array<string, string>  $categoryLabels
     * @param  list<Product>  $products
     */
    public function __construct(
        public string $updatedAt,
        private array $categoryLabels,
        private array $products,
    ) {}

    /** @return list<Product> */
    public function products(): array
    {
        return $this->products;
    }

    /** @return list<TestProduct> */
    public function tests(): array
    {
        return array_values(array_filter($this->products, fn (Product $product): bool => $product instanceof TestProduct));
    }

    public function product(string $id): Product
    {
        foreach ($this->products as $product) {
            if ($product->id === $id) {
                return $product;
            }
        }

        throw new OutOfBoundsException("Unknown product {$id}");
    }

    /** @return list<Product> */
    public function matching(ProductFilter $filter): array
    {
        return array_values(array_filter($this->products, $filter->matches(...)));
    }

    /** @return array<string, string> */
    public function categoryLabels(): array
    {
        return $this->categoryLabels;
    }

    public function categoryLabel(Category $category): string
    {
        return $this->categoryLabels[$category->value];
    }

    public function count(?Category $category = null): int
    {
        return count($this->matching(new ProductFilter(category: $category)));
    }

    /** @return array<string, array{code: string, name: string, kind: string, priceCents: int, salePriceCents: int, ecm: int|null}> */
    public function cartItems(): array
    {
        $items = [];
        foreach ($this->products as $product) {
            $items[$product->id] = [
                'code' => $product->code,
                'name' => $product->name,
                'kind' => $product->kind()->value,
                'priceCents' => $product->price->cents,
                'salePriceCents' => $product->salePrice->cents,
                'ecm' => $product->ecmCredits(),
            ];
        }

        return $items;
    }
}
