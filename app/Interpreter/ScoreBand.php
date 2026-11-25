<?php

declare(strict_types=1);

namespace App\Interpreter;

enum ScoreBand: string
{
    case MuchBelow = 'molto-inferiore';
    case Below = 'inferiore';
    case Average = 'media';
    case Above = 'superiore';
    case MuchAbove = 'molto-superiore';

    public static function forStandardScore(int $standard): self
    {
        return match (true) {
            $standard < 70 => self::MuchBelow,
            $standard < 85 => self::Below,
            $standard <= 115 => self::Average,
            $standard <= 130 => self::Above,
            default => self::MuchAbove,
        };
    }

    public function label(): string
    {
        return __('ui.interpreter.bands.'.$this->value);
    }
}
