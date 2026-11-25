<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Catalog\CatalogFacts;
use App\Catalog\Category;
use App\Catalog\CourseProduct;
use App\Catalog\Product;
use App\Catalog\ProductFilter;
use App\Catalog\Qualification;
use App\Catalog\TestProduct;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures;
use Tests\TestCase;

final class CatalogTest extends TestCase
{
    /** @return iterable<string, array{array{category: string, query: string, age: int|null}, list<string>|string}> */
    public static function filterCases(): iterable
    {
        foreach (Fixtures::load('filters')['cases'] as $case) {
            yield json_encode($case['filters'], JSON_THROW_ON_ERROR) => [$case['filters'], $case['ids']];
        }
    }

    public function test_it_meets_the_brief_8_products_in_3_categories(): void
    {
        $products = $this->catalog()->products();
        $categories = array_unique(array_map(fn (Product $product): string => $product->category->value, $products));

        $this->assertGreaterThanOrEqual(8, count($products));
        $this->assertGreaterThanOrEqual(3, count($categories));
    }

    public function test_tests_and_courses_are_typed_and_counted(): void
    {
        $catalog = $this->catalog();
        $courses = array_filter($catalog->products(), fn (Product $product): bool => ! $product->isTest());

        $this->assertSame(12, $catalog->count());
        $this->assertSame(4, $catalog->count(Category::Formazione));
        $this->assertContainsOnlyInstancesOf(TestProduct::class, $catalog->tests());
        $this->assertContainsOnlyInstancesOf(CourseProduct::class, $courses);
    }

    /**
     * @param  array{category: string, query: string, age: int|null}  $filters
     * @param  list<string>|string  $expected
     */
    #[DataProvider('filterCases')]
    public function test_the_filter_follows_the_shared_rules(array $filters, array|string $expected): void
    {
        $catalog = $this->catalog();
        $all = array_map(fn (Product $product): string => $product->id, $catalog->products());
        $ids = array_map(fn (Product $product): string => $product->id, $catalog->matching(ProductFilter::fromArray($filters)));

        $this->assertSame($expected === 'all' ? $all : $expected, $ids);
    }

    public function test_bento_facts_are_derived_from_the_catalogue(): void
    {
        $facts = CatalogFacts::of($this->catalog());
        $qualifications = Fixtures::load('qualifications');

        $this->assertSame([10, 70, 50, 4, 95], [$facts->minMinutes, $facts->maxMinutes, $facts->maxEcm, $facts->minAge, $facts->maxAge]);
        $this->assertSame($qualifications['total'], $facts->testCount);
        foreach ($qualifications['reach'] as $level => $codes) {
            $this->assertSame($codes, $facts->testsFor(Qualification::from($level)), $level);
        }
    }

    public function test_assistant_scenarios_use_the_grid_filter_rules(): void
    {
        $scenarios = collect(__('ui.assistant.scenarios'))->keyBy('id');

        foreach (Fixtures::load('assistant')['cases'] as $case) {
            $filter = ProductFilter::fromArray($scenarios[$case['scenario']]['filters']);
            $ids = array_map(fn (Product $product): string => $product->id, $this->catalog()->matching($filter));
            $this->assertSame($case['ids'], $ids, $case['scenario']);
        }
    }

    public function test_every_card_renders_with_its_prices(): void
    {
        $this->withoutMix();

        $response = $this->get('/');

        $response->assertSee('data-product-id="orma"', false);
        $response->assertSee("1.062,50\u{00A0}€", false);
        $response->assertSee("1.250,00\u{00A0}€", false);
        $this->assertSame(12, substr_count((string) $response->getContent(), 'data-product-id='));
    }
}
