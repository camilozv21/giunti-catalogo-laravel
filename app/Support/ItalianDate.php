<?php

declare(strict_types=1);

namespace App\Support;

use Carbon\CarbonInterface;

final class ItalianDate
{
    public static function long(CarbonInterface $date): string
    {
        return $date->locale('it')->isoFormat('D MMMM YYYY');
    }
}
