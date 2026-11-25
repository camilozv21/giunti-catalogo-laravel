<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Support\ItalianDate;
use App\Support\Money;
use Carbon\CarbonImmutable;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures;

final class FormattingTest extends TestCase
{
    /** @return iterable<string, array{int, string}> */
    public static function prices(): iterable
    {
        foreach (Fixtures::load('format')['price'] as $row) {
            yield "{$row['cents']} cents" => [$row['cents'], $row['expected']];
        }
    }

    /** @return iterable<string, array{string, string}> */
    public static function dates(): iterable
    {
        foreach (Fixtures::load('format')['date'] as $row) {
            yield $row['iso'] => [$row['iso'], $row['expected']];
        }
    }

    #[DataProvider('prices')]
    public function test_euro_amounts_use_the_italian_format(int $cents, string $expected): void
    {
        $this->assertSame($expected, Money::fromCents($cents)->format());
    }

    public function test_the_euro_sign_follows_a_no_break_space(): void
    {
        $this->assertStringContainsString("\u{00A0}€", Money::fromCents(125000)->format());
    }

    public function test_amounts_add_up_and_multiply(): void
    {
        $this->assertSame(31700, Money::fromCents(10000)->times(3)->plus(Money::fromCents(1700))->cents);
        $this->assertTrue(Money::fromCents(1)->isLessThan(Money::fromCents(2)));
    }

    #[DataProvider('dates')]
    public function test_dates_are_written_in_italian(string $iso, string $expected): void
    {
        $this->assertSame($expected, ItalianDate::long(CarbonImmutable::parse($iso, 'UTC')));
    }
}
