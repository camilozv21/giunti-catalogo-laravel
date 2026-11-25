<?php

declare(strict_types=1);

namespace App\Catalog;

final readonly class CatalogFacts
{
    /** @param array<string, list<string>> $reach */
    public function __construct(
        public int $minMinutes,
        public int $maxMinutes,
        public int $maxEcm,
        public int $minAge,
        public int $maxAge,
        public int $testCount,
        public array $reach,
    ) {}

    public static function of(Catalog $catalog): self
    {
        $tests = $catalog->tests();
        $minutes = array_merge(...array_map(fn (TestProduct $test): array => $test->minutes(), $tests));
        $credits = array_map(fn (Product $product): int => $product->ecmCredits() ?? 0, $catalog->products());

        return new self(
            minMinutes: min($minutes),
            maxMinutes: max($minutes),
            maxEcm: max($credits),
            minAge: min(array_map(fn (TestProduct $test): int => $test->ageMin, $tests)),
            maxAge: max(array_map(fn (TestProduct $test): int => $test->ageMax, $tests)),
            testCount: count($tests),
            reach: self::reach($tests),
        );
    }

    /** @return list<string> */
    public function testsFor(Qualification $qualification): array
    {
        return $this->reach[$qualification->value];
    }

    /**
     * @param  list<TestProduct>  $tests
     * @return array<string, list<string>>
     */
    private static function reach(array $tests): array
    {
        $reach = [];
        foreach (Qualification::cases() as $qualification) {
            $orderable = array_filter($tests, fn (TestProduct $test): bool => $qualification->canOrder($test->qualification));
            $reach[$qualification->value] = array_values(array_map(fn (TestProduct $test): string => $test->code, $orderable));
        }

        return $reach;
    }
}
