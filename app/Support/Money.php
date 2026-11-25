<?php

declare(strict_types=1);

namespace App\Support;

final readonly class Money
{
    private function __construct(public int $cents) {}

    public static function fromCents(int $cents): self
    {
        return new self($cents);
    }

    public function times(int $quantity): self
    {
        return new self($this->cents * $quantity);
    }

    public function plus(self $other): self
    {
        return new self($this->cents + $other->cents);
    }

    public function isLessThan(self $other): bool
    {
        return $this->cents < $other->cents;
    }

    // number_format, not NumberFormatter: ICU with CLDR 46+ drops the thousands separator for it_IT.
    public function format(): string
    {
        return number_format($this->cents / 100, 2, ',', '.')."\u{00A0}€";
    }
}
