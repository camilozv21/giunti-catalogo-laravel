<?php

declare(strict_types=1);

namespace Tests;

final class Fixtures
{
    /** @return array<string, mixed> */
    public static function load(string $name): array
    {
        return json_decode((string) file_get_contents(__DIR__."/fixtures/{$name}.json"), true, flags: JSON_THROW_ON_ERROR);
    }
}
