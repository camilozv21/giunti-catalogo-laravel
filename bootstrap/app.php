<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

$pageCache = 'cache.headers:public;max_age=0;s_maxage=300;stale_while_revalidate=86400;etag';

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        health: '/up',
        then: function () use ($pageCache): void {
            // Outside the "web" group on purpose: no session or CSRF cookie, so responses stay cacheable.
            Route::middleware($pageCache)->group(base_path('routes/pages.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions()
    ->create();
