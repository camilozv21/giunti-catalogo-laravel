<?php

declare(strict_types=1);

namespace App\Catalog;

use App\Support\ItalianDate;
use App\Support\Money;
use Carbon\CarbonImmutable;

final readonly class CourseProduct extends Product
{
    private const string ON_DEMAND = 'on-demand';

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
        public string $teacher,
        public string $delivery,
        public CarbonImmutable $startDate,
        public ?string $location,
        public int $hours,
        public ?int $ecm,
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
            teacher: $data['teacher'],
            delivery: $data['delivery'],
            startDate: CarbonImmutable::parse($data['startDate'], 'UTC'),
            location: $data['location'],
            hours: $data['hours'],
            ecm: $data['ecm'],
        );
    }

    public function kind(): ProductKind
    {
        return ProductKind::Course;
    }

    public function coversAge(int $age): bool
    {
        return false;
    }

    public function ecmCredits(): ?int
    {
        return $this->ecm;
    }

    public function byline(): string
    {
        return __('ui.card.teacher', ['name' => $this->teacher]);
    }

    public function facts(): array
    {
        $facts = [
            new ProductFact(__('ui.card.start'), $this->start()),
            new ProductFact(__('ui.card.duration'), __('ui.card.hours', ['count' => $this->hours])),
        ];

        if ($this->ecm !== null) {
            $facts[] = new ProductFact('ECM', __('ui.card.ecmShort', ['count' => $this->ecm]));
        }

        return $facts;
    }

    public function digest(): string
    {
        return ItalianDate::long($this->startDate).' · '.$this->format->label();
    }

    private function start(): string
    {
        $date = ItalianDate::long($this->startDate);

        return $this->delivery === self::ON_DEMAND ? __('ui.card.availableFrom', ['date' => $date]) : $date;
    }
}
