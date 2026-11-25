<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Response;

final class RobotsController
{
    public function __invoke(): Response
    {
        $sitemap = rtrim(url('/'), '/').'/sitemap.xml';

        return response("User-Agent: *\nAllow: /\n\nSitemap: {$sitemap}\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
