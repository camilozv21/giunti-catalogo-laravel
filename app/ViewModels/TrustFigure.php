<?php

declare(strict_types=1);

namespace App\ViewModels;

final readonly class TrustFigure
{
    public function __construct(
        public ?int $count,
        public string $value,
        public string $text,
    ) {}

    public static function fromCopy(string $value, string $text): self
    {
        if (preg_match('/^(\d+)(.*)$/u', $value, $match) !== 1) {
            return new self(null, $value, $text);
        }

        return new self((int) $match[1], $match[2], $text);
    }
}
