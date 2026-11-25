<?php

declare(strict_types=1);

namespace App\ViewModels;

use App\Catalog\Product;
use App\Support\Money;

final readonly class QuoteLine
{
    public function __construct(
        public Product $product,
        public int $quantity,
    ) {}

    public function total(): Money
    {
        return $this->product->salePrice->times($this->quantity);
    }
}
