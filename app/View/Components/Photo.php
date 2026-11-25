<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

final class Photo extends Component
{
    private const array WIDTHS = [400, 800, 1200];

    private const array FORMATS = ['avif', 'webp'];

    public function __construct(
        public string $stem,
        public string $sizes,
        public string $alt = '',
        public string $imageClass = '',
    ) {}

    /** @return array<string, string> */
    public function sources(): array
    {
        $sources = [];
        foreach (self::FORMATS as $format) {
            $sources["image/{$format}"] = implode(', ', array_map(
                fn (int $width): string => "/images/photos/{$this->stem}-{$width}.{$format} {$width}w",
                self::WIDTHS,
            ));
        }

        return $sources;
    }

    public function fallback(): string
    {
        return "/images/photos/{$this->stem}-800.jpg";
    }

    public function render(): View
    {
        return view('partials.photo');
    }
}
