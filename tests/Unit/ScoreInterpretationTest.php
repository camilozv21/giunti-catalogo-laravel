<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Interpreter\ScoreInterpretation;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Fixtures;

final class ScoreInterpretationTest extends TestCase
{
    /** @return iterable<string, array{array{z: int|float, t: int, standard: int, percentile: string, band: string}}> */
    public static function cases(): iterable
    {
        foreach (Fixtures::load('scores')['cases'] as $case) {
            yield "z = {$case['z']}" => [$case];
        }
    }

    /** @param array{z: int|float, t: int, standard: int, percentile: string, band: string} $case */
    #[DataProvider('cases')]
    public function test_conversions_match_the_shared_fixtures(array $case): void
    {
        $score = new ScoreInterpretation((float) $case['z']);

        $this->assertSame($case['t'], $score->tScore);
        $this->assertSame($case['standard'], $score->standardScore);
        $this->assertSame($case['percentile'], $score->percentile());
        $this->assertSame($case['band'], $score->band->value);
    }

    public function test_z_is_formatted_the_italian_way(): void
    {
        $this->assertSame('+1,0', (new ScoreInterpretation(1))->formattedZ());
        $this->assertSame('−2,5', (new ScoreInterpretation(-2.5))->formattedZ());
        $this->assertSame('0,0', (new ScoreInterpretation(0))->formattedZ());
    }

    public function test_chart_style_matches_the_next_js_version(): void
    {
        $this->assertSame('--z:1;--lift:66.10px;--cut:62.50%;--fill:66.67%;--p:84', (new ScoreInterpretation(1))->chartStyle());
    }
}
