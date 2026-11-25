<?php

declare(strict_types=1);

namespace App\Catalog;

enum ProductFormat: string
{
    case Cartaceo = 'cartaceo';
    case Digitale = 'digitale';
    case CorsoOnline = 'corso-online';
    case InPresenza = 'in-presenza';

    public function label(): string
    {
        return __('ui.card.formats.'.$this->value);
    }

    public function icon(): string
    {
        return match ($this) {
            self::Cartaceo => 'book-open',
            self::Digitale => 'monitor',
            self::CorsoOnline => 'circle-play',
            self::InPresenza => 'map-pin',
        };
    }
}
