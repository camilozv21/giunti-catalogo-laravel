<?php

declare(strict_types=1);

namespace App\ViewModels;

final readonly class TrustStripViewModel
{
    /** @return list<TrustFigure> */
    public function figures(): array
    {
        return array_map(
            fn (array $item): TrustFigure => TrustFigure::fromCopy($item['value'], $item['text']),
            __('ui.trust.items'),
        );
    }
}
