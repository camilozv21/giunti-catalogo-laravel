<?php

declare(strict_types=1);

namespace App\Catalog;

enum Qualification: string
{
    case A = 'A';
    case B = 'B';
    case C = 'C';
    case HR = 'HR';

    public function label(): string
    {
        return __('ui.card.qualifications.'.$this->value);
    }

    public function canOrder(self $required): bool
    {
        return in_array($required, $this->opens(), true);
    }

    /** @return list<self> */
    private function opens(): array
    {
        return match ($this) {
            self::A => [self::A],
            self::B => [self::A, self::B],
            self::C => [self::A, self::B, self::C, self::HR],
            self::HR => [self::HR],
        };
    }
}
