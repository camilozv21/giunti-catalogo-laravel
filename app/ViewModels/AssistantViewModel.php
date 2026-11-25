<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Catalog\Catalog;
use App\Catalog\CatalogRepository;
use App\Catalog\Product;
use App\Catalog\ProductFilter;

final readonly class AssistantViewModel
{
    private Catalog $catalog;

    /** @var list<AssistantScenario> */
    public array $scenarios;

    /** @var array<string, int> */
    private array $answerOrder;

    public function __construct(CatalogRepository $catalogs)
    {
        $this->catalog = $catalogs->catalog();
        $this->scenarios = array_map($this->scenario(...), __('ui.assistant.scenarios'));
        $answer = $this->catalog->matching($this->firstScenario()->filter);
        $this->answerOrder = array_flip(array_map(fn (Product $product): string => $product->id, $answer));
    }

    public function firstScenario(): AssistantScenario
    {
        return $this->scenarios[0];
    }

    /** @return list<Product> */
    public function products(): array
    {
        return $this->catalog->products();
    }

    public function answerCount(): int
    {
        return count($this->answerOrder);
    }

    public function isInAnswer(Product $product): bool
    {
        return isset($this->answerOrder[$product->id]);
    }

    public function answerPosition(Product $product): int
    {
        return $this->answerOrder[$product->id] ?? 0;
    }

    /** @param array{id: string, label: string, message: string, filters: array{category: string, age: int|null, query: string}} $copy */
    private function scenario(array $copy): AssistantScenario
    {
        $filter = ProductFilter::fromArray($copy['filters']);

        return new AssistantScenario($copy['id'], $copy['label'], $copy['message'], $filter, $this->describe($filter));
    }

    private function describe(ProductFilter $filter): string
    {
        $parts = array_filter([
            $filter->category ? $this->catalog->categoryLabel($filter->category) : null,
            $filter->age === null ? null : __('ui.catalog.age.value', ['age' => $filter->age]),
            $filter->query === '' ? null : "«{$filter->query}»",
        ]);

        return __('ui.assistant.applied', ['filters' => implode(' · ', $parts)]);
    }
}
