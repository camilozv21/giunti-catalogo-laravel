<?php

declare(strict_types=1);

namespace App\Catalog;

use App\Support\Money;

final readonly class TestProduct extends Product
{
    /** @param list<string> $authors */
    public function __construct(
        string $id,
        string $code,
        string $name,
        Category $category,
        ProductFormat $format,
        string $summary,
        Money $price,
        Money $salePrice,
        ProductImage $image,
        public array $authors,
        public int $ageMin,
        public int $ageMax,
        public string $duration,
        public Qualification $qualification,
    ) {
        parent::__construct($id, $code, $name, $category, $format, $summary, $price, $salePrice, $image);
    }

    /** @param array<string, mixed> $data */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            code: $data['code'],
            name: $data['name'],
            category: Category::from($data['category']),
            format: ProductFormat::from($data['format']),
            summary: $data['summary'],
            price: Money::fromCents($data['priceCents']),
            salePrice: Money::fromCents($data['salePriceCents']),
            image: ProductImage::fromArray($data['image']),
            authors: $data['authors'],
            ageMin: $data['ageMin'],
            ageMax: $data['ageMax'],
            duration: $data['duration'],
            qualification: Qualification::from($data['qualification']),
        );
    }

    public function kind(): ProductKind
    {
        return ProductKind::Test;
    }

    public function coversAge(int $age): bool
    {
        return $age >= $this->ageMin && $age <= $this->ageMax;
    }

    public function ecmCredits(): ?int
    {
        return null;
    }

    public function byline(): string
    {
        return __('ui.card.by', ['names' => implode(', ', $this->authors)]);
    }

    public function facts(): array
    {
        return [
            new ProductFact(__('ui.card.age'), $this->ageRange()),
            new ProductFact(__('ui.card.duration'), $this->duration),
            new ProductFact(__('ui.card.qualification'), $this->qualification->label()),
        ];
    }

    public function digest(): string
    {
        return implode(' · ', array_map(fn (ProductFact $fact): string => $fact->value, $this->facts()));
    }

    /** @return list<int> */
    public function minutes(): array
    {
        preg_match_all('/\d+/', $this->duration, $matches);

        return array_map(intval(...), $matches[0]);
    }

    private function ageRange(): string
    {
        return __('ui.card.ageRange', ['min' => $this->ageMin, 'max' => $this->ageMax]);
    }
}
