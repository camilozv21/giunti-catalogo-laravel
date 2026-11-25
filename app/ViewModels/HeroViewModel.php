<?php

declare(strict_types=1);

namespace App\ViewModels;

final readonly class HeroViewModel
{
    private const array LANDSCAPE_WIDTHS = [640, 768, 1024, 1600, 2400, 3200, 3840];

    private const array PORTRAIT_WIDTHS = [640, 828, 1080, 1215];

    /** @return list<string> */
    public function firstLine(): array
    {
        return explode(' ', __('ui.hero.titleStart'));
    }

    /** @return list<string> */
    public function secondLine(): array
    {
        return explode(' ', __('ui.hero.titleMark'));
    }

    public function landscapeSrcset(): string
    {
        return self::srcset('hero', self::LANDSCAPE_WIDTHS);
    }

    public function portraitSrcset(): string
    {
        return self::srcset('hero-portrait', self::PORTRAIT_WIDTHS);
    }

    /** @param list<int> $widths */
    private static function srcset(string $name, array $widths): string
    {
        return implode(', ', array_map(fn (int $width): string => "/images/hero/{$name}-{$width}.webp {$width}w", $widths));
    }
}
