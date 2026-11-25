<?php

declare(strict_types=1);

namespace App\Catalog;

enum ProductKind: string
{
    case Test = 'test';
    case Course = 'course';
}
