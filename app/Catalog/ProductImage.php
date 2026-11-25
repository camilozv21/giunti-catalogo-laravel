<?php

declare(strict_types=1);

namespace App\Catalog;

final readonly class ProductImage
{
    public function __construct(
        public string $src,
        public string $alt,
        public int $width,
        public int $height,
    ) {}

    /** @param array{src: string, alt: string, width: int, height: int} $data */
    public static function fromArray(array $data): self
    {
        return new self($data['src'], $data['alt'], $data['width'], $data['height']);
    }

    public function stem(): string
    {
        return pathinfo($this->src, PATHINFO_FILENAME);
    }
}
