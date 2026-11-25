<?php

declare(strict_types=1);

namespace App\Interpreter;

final readonly class ScoreInterpretation
{
    public const float Z_MIN = -3.0;

    public const float Z_MAX = 3.0;

    private const int CHART_SIGMA = 58;

    private const int CHART_AMPLITUDE = 168;

    private const int CHART_CENTER = 232;

    private const int CHART_WIDTH = 464;

    public int $tScore;

    public int $standardScore;

    public float $percentileValue;

    public ScoreBand $band;

    public function __construct(public float $z)
    {
        $this->tScore = (int) round(50 + 10 * $z);
        $this->standardScore = (int) round(100 + 15 * $z);
        $this->percentileValue = self::normalCdf($z) * 100;
        $this->band = ScoreBand::forStandardScore($this->standardScore);
    }

    public function percentile(): string
    {
        return match (true) {
            $this->percentileValue < 0.5 => '<1',
            $this->percentileValue >= 99.5 => '>99',
            default => (string) (int) round($this->percentileValue),
        };
    }

    public function formattedZ(): string
    {
        if (abs($this->z) < 0.05) {
            return '0,0';
        }

        return ($this->z > 0 ? '+' : '−').number_format(abs($this->z), 1, ',', '');
    }

    public function chartStyle(): string
    {
        $lift = self::CHART_AMPLITUDE * (1 - exp(-($this->z ** 2) / 2));
        $cut = (self::CHART_CENTER + $this->z * self::CHART_SIGMA) / self::CHART_WIDTH * 100;
        $fill = ($this->z - self::Z_MIN) / (self::Z_MAX - self::Z_MIN) * 100;

        return sprintf(
            '--z:%s;--lift:%spx;--cut:%s%%;--fill:%s%%;--p:%d',
            rtrim(rtrim(number_format($this->z, 2, '.', ''), '0'), '.'),
            number_format($lift, 2, '.', ''),
            number_format($cut, 2, '.', ''),
            number_format($fill, 2, '.', ''),
            (int) round($this->percentileValue),
        );
    }

    private static function normalCdf(float $z): float
    {
        return 0.5 * (1 + self::erf($z / M_SQRT2));
    }

    // Abramowitz–Stegun 7.1.26, maximum error 1.5e-7: the same approximation as the JavaScript versions.
    private static function erf(float $x): float
    {
        $t = 1 / (1 + 0.3275911 * abs($x));
        $poly = ((((1.061405429 * $t - 1.453152027) * $t + 1.421413741) * $t - 0.284496736) * $t + 0.254829592) * $t;

        return ($x <=> 0) * (1 - $poly * exp(-$x * $x));
    }
}
