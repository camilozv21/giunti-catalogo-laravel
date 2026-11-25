<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Catalog\Catalog;
use App\Catalog\CatalogFacts;
use App\Catalog\CatalogRepository;
use App\Catalog\ProductFormat;
use App\Catalog\TestProduct;
use App\Support\Money;

final readonly class BentoViewModel
{
    private const array SAMPLE_QUOTE = ['orma' => 2, 'fad-2701' => 1];

    public const int RULER_SCALE_MINUTES = 90;

    private Catalog $catalog;

    public CatalogFacts $facts;

    public function __construct(CatalogRepository $catalogs)
    {
        $this->catalog = $catalogs->catalog();
        $this->facts = CatalogFacts::of($this->catalog);
    }

    /** @return list<TestProduct> */
    public function testsByAge(): array
    {
        $tests = $this->catalog->tests();
        usort($tests, fn (TestProduct $a, TestProduct $b): int => [$a->ageMin, $a->ageMax] <=> [$b->ageMin, $b->ageMax]);

        return $tests;
    }

    /** @return list<int> */
    public function ageTicks(): array
    {
        return range(0, 100, 20);
    }

    /** @return list<int> */
    public function rulerMinutes(): array
    {
        return range(0, self::RULER_SCALE_MINUTES, 30);
    }

    /** @return list<ProductFormat> */
    public function formats(): array
    {
        return ProductFormat::cases();
    }

    /** @return list<QuoteLine> */
    public function quoteLines(): array
    {
        $lines = [];
        foreach (self::SAMPLE_QUOTE as $id => $quantity) {
            $lines[] = new QuoteLine($this->catalog->product($id), $quantity);
        }

        return $lines;
    }

    public function quoteTotal(): Money
    {
        return array_reduce(
            $this->quoteLines(),
            fn (Money $total, QuoteLine $line): Money => $total->plus($line->total()),
            Money::fromCents(0),
        );
    }
}
